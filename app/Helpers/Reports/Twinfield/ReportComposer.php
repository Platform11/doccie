<?php

namespace App\Helpers\Reports\Twinfield;

use App\Models\Report;
use App\Helpers\PdfGenerator;
use App\Helpers\NumberFormatter;
use App\Helpers\TwinfieldConnector;

class ReportComposer
{

    public $report;

    public $rows;

    public $total;


    /**
     * @param Report $report
     *
     * @return static
     */
    public static function compose(Report $report)
    {   
        return (new static)->start($report);
    }

    public function start($report)
    {   
        
        $this->report = $report;

        //Fetch and process data to generate a PDF
        $this->fetchBrowseData();
        $this->extractValues();
        $this->transformValues();
        $this->orderRowsByDate();

        if(array_key_exists('group_by_column', $this->report->configuration))
        {
          $this->group_by_column();
          $this->sum_column();
        }

        //Set path where the PDF should be stored (temporarily, gets deleted once it has been sent succesfully - see:  App\Listeners\LogSentMessage)
        $this->path = storage_path() . '/app/reports/' . $this->report->id . strtotime('now').'.pdf';

        PdfGenerator::generate($this);
        $this->report->addMedia($this->path)->toMediaCollection();

        return $this;
    }

    public function fetchBrowseData()
    {   
        $this->rows = TwinfieldConnector::browseData($this);

        return $this;
    }

    public function extractValues()
    {   
        $this->rows = $this->extractBrowseDataValues();
      
        return $this;
    }

    public function transformValues()
    {   
        $this->rows = $this->transformRowValues();

        return $this;
    }

    private function extractBrowseDataValues()
    {   
        
        $extracted_browse_data_values_rows = [];

        foreach($this->rows as $row)
        { 
          $extracted_browse_data_values_row = [];

          foreach($row->getCells() as $cell)
          {
            if($cell->getType() == "Date")
            {	
              $value = $cell->getValue();
              $extracted_browse_data_values_row[] = $value->format('d-m-Y');
            }
            else
            {
              $extracted_browse_data_values_row[] = $cell->getValue();
            }
          }

          if($this->report->type == 'debtors' || $this->report->type == 'creditors')
          {
            if((int)$extracted_browse_data_values_row[count($extracted_browse_data_values_row) - 1] !== 0)
            {
              array_shift($extracted_browse_data_values_row);
              $extracted_browse_data_values_rows[] = $extracted_browse_data_values_row;
            }
          }
          else {
            array_shift($extracted_browse_data_values_row);
            $extracted_browse_data_values_rows[] = $extracted_browse_data_values_row;
          }  
        }
        
        return $extracted_browse_data_values_rows;
    }

    public function orderRowsByDate()
    {
        usort($this->rows, function($element1, $element2){
          $datetime1 = strtotime($element1[1]); 
          $datetime2 = strtotime($element2[1]); 
          return $datetime1 - $datetime2; 
        }); 

        return $this;
    }

    public function group_by_column()
    {
        $group_by_column = $this->report->configuration['group_by_column'];

        $groups = [];

        foreach($this->rows as $row)
        {   
            $group_identifier = $row[$group_by_column];

            if(!array_key_exists($group_identifier, $groups))
            {   
                $groups[$group_identifier]['name'] = $row[1];
                $groups[$group_identifier]['rows'] = [];
            }

            //remove relation_id
            array_shift($row);
            //remove relation name
            array_shift($row);

            $groups[$group_identifier]['rows'][] = $row;
        }

        //sort groups by key (relation id)
        ksort($groups);

        $this->rows = $groups;
    }

    public function sum_column()
    {
        $sum_column = $this->report->configuration['sum_column'];

        foreach($this->rows as $index=>$row_group)
        {   
            $sum = 0;
            foreach($row_group['rows'] as $row)
            {
                $fmt = new \NumberFormatter( 'nl_NL', \NumberFormatter::CURRENCY );
                $sum += $fmt->parseCurrency($row[$sum_column-1], $curr);
            }
            $this->rows[$index]['total'] = NumberFormatter::format($sum);
        }

        $total = 0;
        foreach($this->rows as $row)
        {
          $fmt = new \NumberFormatter( 'nl_NL', \NumberFormatter::CURRENCY );
          $total += $fmt->parseCurrency($row['total'], $curr);
        }

        $this->total = NumberFormatter::format($total);
    }

    private function transformRowValues()
    {
        $transformed_rows = [];

        foreach($this->rows as $row)
        {
          $i = 0;
          $transformed_row = [];

          foreach($row as $value)
          {
            $transformed_row[] =  $this->transformValue($value, $i);

            if($i == 3 && $this->report->type == 'call_posts')
            {
              $value = '&nbsp;';
              if(strpos($row[0], 'BNK') !== false)
              {
                if((float)$row[3] > 0)
                {
                  $value = "Betaling";
                }
                if((float)$row[3] < 0)  {
                  $value = "Ontvangst";
                }
              }
              $transformed_row[] = $value;  
            }

            $i++;
          }
          $transformed_rows[] = $transformed_row;
        }
        
        return $transformed_rows;
    }

    private function transformJournalValue($value)
    {
      if(
          strpos($value, 'BNK') !== false || 
          strpos($value, 'ING') !== false || 
          strpos($value, 'ABN') !== false || 
          strpos($value, 'RABO') !== false
      )
      {
        return 'Bankmutatie';
      }

      if($value === 'KAS')
      {
        return 'Kasboek';
      }

      if($value === 'INK')
      {
        return 'Inkoopfactuur';
      }

      if($value === 'MEMO')
      {
        return 'Memoriaalboeking';
      }

      if($value === 'VRK')
      {
        return 'Verkoopfactuur';
      }

      if(strpos($value, 'JAAREIND') !== false)
      {
        return 'Eindbalans';
      }

      return $value;
    }

    private function transformValue($value, $index)
    {
      if($this->report->configuration['columns'][$index+1]['label'] == 'Dagboek')
      {
        return $this->transformJournalValue($value);
      }

      if($this->report->configuration['columns'][$index+1]['label'] == 'Bedrag' && $this->report->type == 'call_posts')
      {
        return NumberFormatter::format($value, 'to_positive');
      }
      else if($this->report->type == "creditors" && ($this->report->configuration['columns'][$index+1]['label'] == 'Bedrag' || $this->report->configuration['columns'][$index+1]['label'] == 'Openstaand'))
      {
        return NumberFormatter::format($value, 'flip');
      }
      else if($this->report->configuration['columns'][$index+1]['label'] == 'Bedrag' || $this->report->configuration['columns'][$index+1]['label'] == 'Openstaand') {
        return NumberFormatter::format($value);
      }

      return $value;
    }

}