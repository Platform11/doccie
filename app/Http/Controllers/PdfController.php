<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateReports;
use App\Models\Administration;
use Illuminate\Support\Facades\Auth;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\File;
use VerumConsilium\Browsershot\Facades\PDF;

class PdfController extends Controller
{
    public function vraagposten()
    {       

        return view('pdf.vraagposten', [
          'administration_name' => 'Testadministratie vraagposten app',
          'doc_type' => 'Vraagposten',
          'date' => date('d-m-Y'),
          'headings' => [
            ['value'=>'Dagboek', 'align'=>'left'],
            ['value'=>'Boekdatum', 'align'=>'left'],
            ['value'=>'Omschrijving', 'align'=>'left'],
            ['value'=>'Bedrag', 'align'=>'right'],
            ['value'=>'Betaling/ontvangst', 'align'=>'left'],
            ['value'=>'Factuurnummer', 'align'=>'left'],
          ],
          'lines' => [
              ['test', 'test', 'test', 'test','testdasdasdasda asdasdas as das as dasd asd asd as','test'],
              ['test', 'test', 'test', 'test','testdasdasdasda asdasdas as das as dasd asd asd as','test'],
          ],
          'contact' => ['name' => 'Esther Roelofs', 'email' => 'esther@oneaccoutants.nl'],
          'logo' => Auth::user()->account->logo,
          'account' => ['color'=>'#77BC1F']
        ]);


        //GenerateReports::dispatch(Administration::find(1), Auth::user());

        return;

        // $connection = new \PhpTwinfield\Secure\WebservicesAuthentication("nretel", "8.Jo9ihLKpXFiUdLy", "one");

        // $office = \PhpTwinfield\Office::fromCode("9999");
        // $officeApi = new \PhpTwinfield\ApiConnectors\OfficeApiConnector($connection);
        // $officeApi->setOffice($office);

        // $connector = new \PhpTwinfield\ApiConnectors\BrowseDataApiConnector($connection);

        // $columns[] = (new \PhpTwinfield\BrowseColumn())
        //     ->setField('fin.trs.line.dim1')
        //     ->setLabel('General ledger')
        //     ->setVisible(false)
        //     ->setAsk(true)
        //     ->setOperator(\PhpTwinfield\Enums\BrowseColumnOperator::BETWEEN())
        //     ->setFrom('2999')
        //     ->setTo('2999');

        // $columns[] = (new \PhpTwinfield\BrowseColumn())
        //     ->setField('fin.trs.head.code')
        //     ->setLabel('Transaction type')
        //     ->setVisible(true);

        // $columns[] = (new \PhpTwinfield\BrowseColumn())
        //     ->setField('fin.trs.head.date')
        //     ->setLabel('Date')
        //     ->setVisible(true);

        // $columns[] = (new \PhpTwinfield\BrowseColumn())
        //     ->setField('fin.trs.line.description')
        //     ->setLabel('Description')
        //     ->setVisible(true);

        // $columns[] = (new \PhpTwinfield\BrowseColumn())
        //     ->setField('fin.trs.line.valuesigned')
        //     ->setLabel('Value')
        //     ->setVisible(true);

        // $columns[] = (new \PhpTwinfield\BrowseColumn())
        //     ->setField('fin.trs.line.invnumber')
        //     ->setLabel('Invoice Number')
        //     ->setVisible(true);

        // $sortFields[] = new \PhpTwinfield\BrowseSortField('fin.trs.head.date');

        // $browseData = $connector->getBrowseData('030_3', $columns, $sortFields);

        // $rows = $browseData->getRows();

        // $lines = [];

        // foreach($rows as $row)
        // {
        //   $line = [];
          
        //   foreach($row->getCells() as $cell)
        //   {
        //     if($cell->getType() == "Date")
        //     {	
        //         $value = $cell->getValue();
        //       $line[] = $value->format('d-m-Y');
        //     }
        //     else
        //     {
        //       $line[] = $cell->getValue();
        //     }
        //   }

        //   $lines[] = $line;
        // }

        // $lines = $this->transformLines($lines);

        //   $data = [
        //   'administration_name' => 'Testadministratie vraagposten app',
        //   'doc_type' => 'Vraagposten',
        //   'date' => date('d-m-Y'),
        //   'headings' => [
        //     ['value'=>'Dagboek', 'align'=>'left'],
        //     ['value'=>'Boekdatum', 'align'=>'left'],
        //     ['value'=>'Omschrijving', 'align'=>'left'],
        //     ['value'=>'Bedrag', 'align'=>'right'],
        //     ['value'=>'Betaling/ontvangst', 'align'=>'left'],
        //     ['value'=>'Factuurnummer', 'align'=>'left'],
        //   ],
        //   'lines' => $lines,
        //   'contact' => ['name' => 'Esther Roelofs', 'email' => 'esther@oneaccoutants.nl'],
        //   'logo_path' => asset('logo-oneaccountants.svg'),
        //   'account' => ['color'=>'#77BC1F']
        // ];

        // //return view('pdf.vraagposten', $data);

        //   //$html = \View::make('pdf.vraagposten', $data)->render();

        //   $name = '19999-ov' . strtotime(now());

        //   $pdf = PDF::loadView('pdf.vraagposten', $data)
        //   ->format('A4')
        //   ->setOption('addStyleTag', json_encode(['content' => file_get_contents('css/app.css')]))
        //   ->margins(20, 20, 20, 20)
        //   ->landscape(true)
        //   ->storeAs($name.'/', 'Overzicht openstaande vraagposten - 27-01-01.pdf');

          //Browsershot::html($html)

          //->save(\Storage::disk('local')'/storage/app/example.pdf');
    }
}
