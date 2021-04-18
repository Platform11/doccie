<?php

namespace App\Services;

use App\Models\Report;
use App\Services\PdfGenerator;
use App\Services\NumberFormatter;
use App\Services\TwinfieldConnector;
use Spatie\Browsershot\Browsershot;

class TwinfieldReportComposer
{

    private $report;
    private $data;

    /**
     * @param Report $report
     *
     * @return static
     */
    public static function compose(Report $report)
    {   
        return (new static)->start($report);
    }

    private function start(Report $report)
    {   
      $this->report = $report;

      $this->fetchData()
      ->mapData()
      ->transformData()
      ->orderDataRowsByDate()
      ->groupDataRows()
      ->exportToPdf();

      return $this;
    }

    public function fetchData()
    {   
        $this->data = TwinfieldConnector::fetchData($this->report);

        return $this;
    }

    private function mapData()
    {   
        if($this->report->type == 'unspecified_posts')
        {
          $this->data = $this->data->flatMap(function ($values) {
              return $values;
          });
        }

        $this->data = $this->data->map(function($row){
          return $this->mapDataValuesWithReportConfiguration($row);
        });

        return $this;
    }

    private function mapDataValuesWithReportConfiguration($row)
    {
        return collect($row->getCells())->map(function($cell, $key) {
            return collect($this->report->configuration()['columns'][$key])->merge(['value' => $cell->getValue()]);
        });

        return $this;
    }

    private function transformData()
    {   
        $this->data = $this->data->map(function($row){
          return $this->transformRow($row);
        });

        return $this;
    }

    private function transformRow($row)
    {
      return $row->map(function($cell) use ($row) {
          return $this->transformCellValue($cell, $row);
      });
    }

    private function transformCellValue($cell, $row)
    { 
      $tranformed_value = match ($cell->get('id')) {
        'journal' => $this->transformJournalValue($cell->get('value')),
        collect(['total_amount', 'open_amount'])->contains($cell->get('id')) => NumberFormatter::format($cell->get('value')),
        'amount_type' => $this->determineAmountType($row),
        default => $cell->get('value')
      };

      return $cell->put('value', $tranformed_value);
    }

    private function transformJournalValue($value)
    {
      return match ($value) {
        'BNK', 
        strpos($value, 'ING') !== false, 
        strpos($value, 'ABN') !== false, 
        strpos($value, 'RABO') !== false => 'Bankmutatie',
        'KAS' => 'Kasboek',
        'INK' => 'Inkoopfactuur',
        'MEMO' => 'Memoboeking',
        'VRK' => 'Verkoopfactuur',
        strpos($value, 'JAAREIND') !== false => 'Eindbalans',
        default => $value,
      };
    }

    private function determineAmountType($row)
    {
      $total_amount = $row->filter(function ($cell){
        return $cell->get('id') == 'total_amount';
      })->pluck('value')
      ->first();

      return $total_amount < 0 ? 'Betaling' : 'Ontvangst';
    }

    private function orderDataRowsByDate()
    { 
      $this->data = $sorted = $this->data->sortBy(function ($row) {
        return $row->filter(function ($cell) {
          return $cell->get('id') == 'date';
        })->pluck('value')
        ->first();
      });

      return $this;
    }

    private function groupDataRows()
    {
      if(!array_key_exists('group_by', $this->report->configuration()))
      {
        return $this;
      }

      $this->data = $this->data->groupBy(function ($row, $key) {
        return $row->filter(function ($cell) {
          return $cell->get('id') == $this->report->configuration()['group_by'];
        })->pluck('value')
        ->first();
      })->sort();

      $this->data->transform(function ($rows){
        return [
          'rows' => $rows, 
          'name'=> $this->groupLabel($rows[0]),
          'total'=> $this->calculateGroupTotals($rows)
        ];
      });

      return $this;
    }

    private function groupLabel($row)
    {
      return $row->filter(function($cell){
        return $cell->contains($this->report->configuration()['group_label']);
      })->pluck('value')->flatten()->first();
    }

    private function calculateGroupTotals($rows)
    {
      return $rows->flatMap(function ($row) {
           return $row;
      })->filter(function($cell){
        return collect(['open_amount'])->contains($cell->get('id'));
      })->pluck('value')
      ->reduce(function ($carry, $amount) {
        return $carry + $amount;
      });
    }

    private function exportToPdf()
    {   
        $path = storage_path('app/reports') . $this->report->id . strtotime('now').'.pdf';

        Browsershot::html(view($this->report->configuration()['view'], ['report' => $this->report, 'data' => $this->data])->render())
        ->addChromiumArguments([
            'font-render-hinting' => 'none',
        ])
        ->setOption('addStyleTag', json_encode(['content' => file_get_contents(public_path('css/app.css'))]))
        ->format('A4')
        ->margins(20, 20, 20, 20)
        ->landscape(true)
        ->save($path);

        $this->report->addMedia($path)->toMediaCollection();

        return $this;
    }
}