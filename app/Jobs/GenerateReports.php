<?php

namespace App\Jobs;

use App\Models\Administration;
use App\Models\User;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use VerumConsilium\Browsershot\Facades\PDF;
use App\Notifications\SendReports;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class GenerateReports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\User
     */
    protected $authenticated_user;

    /**
     * @var \App\Models\Administration
     */
    protected $administration;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Administration $administration, User $authenticated_user)
    {
        $this->authenticated_user = $authenticated_user;
        $this->administration = $administration;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        $this->administration->status = "in_progress";
        $this->administration->error_reason = null;
        $this->administration->save();

        $connector = $this->setupConnector();
        $columns = $this->setupColumnsVraagposten();
        $sortFields[] = new \PhpTwinfield\BrowseSortField('fin.trs.head.date');

        if($connector)
        {
          try {
              $browseData = $connector->getBrowseData('030_3', $columns, $sortFields);
          } catch (\Exception $e) {
            sleep(5);
            try {
              $browseData = $connector->getBrowseData('030_3', $columns, $sortFields);
            } catch (\Exception $e) {
                $this->administration->status = "error";
                $this->administration->save();
                return;
            }
          }
        }
        else {
          throw new \Exception('office_does_not_exist');
        }

        $connection = new \PhpTwinfield\Secure\WebservicesAuthentication(
            $this->authenticated_user->twinfield_username,
            $this->authenticated_user->twinfield_password,
            $this->authenticated_user->account->twinfield_office_code,
        );

        $headings = $this->setupHeadings();
        $rows = $this->processBrowseData($browseData, $headings);

        $data_vraagposten = $this->constructDataVraagposten($rows, $headings); 

        $folder_name = $this->administration->id.strtotime(now());

        Storage::makeDirectory($folder_name);

        $file_name = 'Vraagposten - '.date('d-m-Y').'.pdf';
        $path_to_store = storage_path() . '/app/' . $folder_name . '/'. $file_name;

        Browsershot::html(view('pdf.vraagposten', $data_vraagposten)->render())
        // ->setOption('addStyleTag', json_encode(['content' => file_get_contents(public_path('css/app.css'))]))
        ->format('A4')
        ->margins(20, 20, 20, 20)
        ->landscape(true)
        ->save($path_to_store);

        // PDF::loadView('pdf.vraagposten', $data_vraagposten)
        // ->format('A4')
        // ->addChromiumArguments([
        //     'font-render-hinting' => 'none',
        // ])
        // ->setOption('addStyleTag', json_encode(['content' => file_get_contents(public_path('css/app.css'))]))
        // ->margins(20, 20, 20, 20)
        // ->landscape(true)
        // ->storeAs($folder_name.'/', $file_name);

        $report = new Report();
        $report->title = "Vraagposten";
        $report->administration_id = $this->administration->id;
        $report->author_id = $this->authenticated_user->id;
        $report->data = json_encode(array('transactions' => count($rows)));
        $report->save();

        try{
            $this->authenticated_user->notify(new SendReports([$path_to_store], [$folder_name], count($rows), $this->administration, $report));
        }
        catch(\Exception $e){ // Using a generic exception
            dump('Mail not sent');
            $this->administration->status = "error";
            $this->administration->save();
        }
    }

    private function setupConnector()
    {
        $connection = new \PhpTwinfield\Secure\WebservicesAuthentication(
            $this->authenticated_user->twinfield_username,
            $this->authenticated_user->twinfield_password,
            $this->authenticated_user->account->twinfield_office_code,
        );

        $office = \PhpTwinfield\Office::fromCode($this->administration->code);
        $officeApi = new \PhpTwinfield\ApiConnectors\OfficeApiConnector($connection);

        if(!$officeApi->setOffice($office))
        {
          return false;
        }

        $connector = new \PhpTwinfield\ApiConnectors\BrowseDataApiConnector($connection);

        return $connector;
    }

    private function setupColumnsVraagposten()
    {
        $columns[] = (new \PhpTwinfield\BrowseColumn())
            ->setField('fin.trs.line.dim1')
            ->setLabel('General ledger')
            ->setVisible(false)
            ->setAsk(true)
            ->setOperator(\PhpTwinfield\Enums\BrowseColumnOperator::BETWEEN())
            ->setFrom('2999')
            ->setTo('2999');

        $columns[] = (new \PhpTwinfield\BrowseColumn())
            ->setField('fin.trs.head.code')
            ->setLabel('Transaction type')
            ->setVisible(true);

        $columns[] = (new \PhpTwinfield\BrowseColumn())
            ->setField('fin.trs.head.date')
            ->setLabel('Date')
            ->setVisible(true);

        $columns[] = (new \PhpTwinfield\BrowseColumn())
            ->setField('fin.trs.line.description')
            ->setLabel('Description')
            ->setVisible(true);

        $columns[] = (new \PhpTwinfield\BrowseColumn())
            ->setField('fin.trs.line.valuesigned')
            ->setLabel('Value')
            ->setVisible(true);

        $columns[] = (new \PhpTwinfield\BrowseColumn())
            ->setField('fin.trs.line.invnumber')
            ->setLabel('Invoice Number')
            ->setVisible(true);

        return $columns;
    }

    private function processBrowseData($browseData, $headings)
    {
        $rows = $browseData->getRows();

        $lines = [];

        foreach($rows as $row)
        {
          $line = [];
          
          foreach($row->getCells() as $cell)
          {
            if($cell->getType() == "Date")
            {	
              $value = $cell->getValue();
              $line[] = $value->format('d-m-Y');
            }
            else
            {
              $line[] = $cell->getValue();
            }
          }

          $lines[] = $line;
        }

        return $this->transformLines($lines, $headings);
    }

    private function transformLines($lines, $headings)
    {
        $transformed_lines = [];

        foreach($lines as $line)
        {
           $i = 0;
          $transformed_line = [];

          foreach($line as $value)
          {
            $transformed_line[] = $this->transformValue($value, $i, $headings);

            if($i == 3)
            {
              $value = '&nbsp;';
              if($line[0] == 'BNK')
              {
                if((float)$line[3] > 0)
                {
                  $value = "Betaling";
                }
                if((float)$line[3] < 0)  {
                  $value = "Ontvangst";
                }
              }
              $transformed_line[] = $value;  
            }

            $i++;
          }
          $transformed_lines[] = $transformed_line;
        }
        
        return $transformed_lines;
    }

    private function constructDataVraagposten($rows, $headings){
      return [
          'administration_name' => $this->administration->name,
          'doc_type' => 'Vraagposten',
          'date' => date('d-m-Y - H:i'),
          'headings' => $headings,
          'lines' => $rows,
          'contact' => [
              'name' => $this->authenticated_user->first_name. ' '. $this->authenticated_user->last_name, 
              'email' => $this->authenticated_user->email
            ],
          'logo' => $this->authenticated_user->account->logo,
          'account' => ['color'=>'#77BC1F']
        ];
    }

    private function setupHeadings()
    {
      return [
            ['value'=>'Dagboek', 'align'=>'left'],
            ['value'=>'Boekdatum', 'align'=>'left'],
            ['value'=>'Omschrijving', 'align'=>'left'],
            ['value'=>'Bedrag', 'align'=>'right'],
            ['value'=>'Betaling/ontvangst', 'align'=>'left'],
            ['value'=>'Factuurnummer', 'align'=>'left'],
      ];
      
    }

    private function transformValue($value, $index, $headings)
    {
      if($headings[$index]['value'] == 'Dagboek')
      {
        return $this->transformDagboekValue($value);
      }

      if($headings[$index]['value'] == 'Bedrag')
      {
        return $this->transformBetalingOntvangstValue($value);
      }

      if($headings[$index]['value'] == 'Bedrag')
      {
        return $this->transformBetalingOntvangstValue($value);
      }

      return $value;
    }

    private function transformDagboekValue($value)
    {
      if($value == 'BNK')
      {
        return 'Bankmutatie';
      }

      if($value == 'INK')
      {
        return 'Inkoopfactuur';
      }

      if($value == 'MEMO')
      {
        return 'Memoriaalboeking';
      }

      if($value == 'VRK')
      {
        return 'Verkoopfactuur';
      } 

      return $value;
    }

    private function transformBetalingOntvangstValue($value)
    { 
      $fmt = new \NumberFormatter('nl_NL', \NumberFormatter::CURRENCY);
      return $value < 0 ? $fmt->formatCurrency((float)$value*-1, 'EUR') : $fmt->formatCurrency((float)$value, 'EUR');
    }

    public function failed($e)
    {   
        $this->administration->status = "error";

        if($e->getMessage() == 'office_does_not_exist')
        {
          $this->administration->error_reason = 'Administratie kan niet gevonden worden in Twinfield. Controleer of de administratie in Twinfield bestaat en dat je de juiste rechten hebt.';
        }

        if($e->getMessage() == 'Your logon credentials are not valid anymore. Try to log on again.')
        {
          $this->administration->error_reason = 'De ingestelde inloggegevens werden niet geaccepteerd door Twinfield. Probeer het opnieuw of controleer de Twinfield connectie in je profiel';
        }

        if($e->getMessage() == 'Failed logging in using the credentials, result was "Invalid".')
        {
          $this->administration->error_reason = 'De ingestelde inloggegevens werden niet geaccepteerd door Twinfield. Controleer de Twinfield connectie in je profiel';
        }

        if($e->getMessage() == 'App\Jobs\GenerateReports has been attempted too many times or run too long. The job may have previously timed out.')
        {
          $this->administration->error_reason = 'Er is een fout opgetreden bij het genereren van het rapport. Probeert het nogmaals of neem contact op met de systeembeheerder.';
        }
        
        $this->administration->save();
    }
}
