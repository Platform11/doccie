<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\ModelStatus\HasStatuses;
use PhpTwinfield\BrowseColumn;
use PhpTwinfield\Enums\BrowseColumnOperator;


class Report extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, LogsActivity, InteractsWithMedia, HasStatuses;

    protected $casts = [
        'status' => 'array',
    ];

    protected $fillable = [
        'status',
        'overview_id',
        'type',
    ];

    public function getCreatedAtAttribute($value)
    {   
        $time_string = strtotime($value);

        return date('d-m-Y - H:i', $time_string);
    }

    public function getConfigurationAttribute()
    {   
        return $this->configuration();
    }

    public function overview(): BelongsTo {
        return $this->belongsTo('App\Models\Overview');
    }

    public function configuration()
    {   
        switch ($this->type) {
            case 'call_posts':
                return $this->call_posts_configuration();
                break;
            case 'debtors':
                return $this->debtors_configuration();
                break;
            case 'creditors':
                return $this->creditors_configuration();
                break;
        }
    }

    public function visible_columns()
    {
        $columns = [];

        foreach($this->configuration['columns'] as $column)
        {
            if(!array_key_exists('hide', $column))
            {
                $columns[] = $column;
            }
        }

        return $columns;
    }

    private function call_posts_configuration()
    {
        $configuration = [
                            'columns' => [
                                ['label'=>'Status', 'twinfield_column' => 'fin.trs.head.status', 'hide' => true],
                                ['label'=>'Dagboek', 'twinfield_column' => 'fin.trs.head.code', 'align'=>'left'],
                                ['label'=>'Boekdatum', 'twinfield_column' => 'fin.trs.head.date', 'align'=>'left'],
                                ['label'=>'Omschrijving', 'twinfield_column' => 'fin.trs.line.description', 'align'=>'left'],
                                ['label'=>'Bedrag', 'twinfield_column' => 'fin.trs.line.valuesigned', 'align'=>'right'],
                                ['label'=>'Ontvangst/Betaling', 'twinfield_column' => '', 'align'=>'left'],
                                ['label'=>'Factuurnummer', 'twinfield_column' => 'fin.trs.line.invnumber', 'align'=>'left'],        
                            ],
                            'browse_definition' => '030_3',
                            'view' => 'pdf.call_posts',
                        ];
        
        return $configuration;
    }

    private function debtors_configuration()
    {
        $configuration = [
                            'columns' => [
                                ['label'=>'Status', 'twinfield_column' => 'fin.trs.line.matchstatus', 'hide' => true],
                                ['label'=>'Relatie', 'twinfield_column' => 'fin.trs.line.dim2', 'hide' => true],
                                ['label'=>'Relatie', 'twinfield_column' => 'fin.trs.line.dim2name', 'hide' => true],
                                ['label'=>'Dagboek', 'twinfield_column' => 'fin.trs.head.code', 'align'=>'left'],
                                ['label'=>'Datum', 'twinfield_column' => 'fin.trs.head.date', 'align'=>'left'],
                                ['label'=>'Factuurnummer', 'twinfield_column' => 'fin.trs.line.invnumber', 'align'=>'left'],
                                ['label'=>'Bedrag', 'twinfield_column' => 'fin.trs.line.valuesigned', 'align'=>'right'],
                                ['label'=>'Openstaand', 'twinfield_column' => 'fin.trs.line.openbasevaluesigned', 'align'=>'right'],
                            ],
                            'group_by_column' => 0,
                            'sum_column' => 5,
                            'browse_definition' => '100',
                            'view' => 'pdf.debtors',
                        ];
        
        return $configuration;
    }

    private function creditors_configuration()
    {
        $configuration = [
                            'columns' => [
                                ['label'=>'Status', 'twinfield_column' => 'fin.trs.line.matchstatus', 'hide' => true],
                                ['label'=>'Relatie', 'twinfield_column' => 'fin.trs.line.dim2', 'hide' => true],
                                ['label'=>'Relatie', 'twinfield_column' => 'fin.trs.line.dim2name', 'hide' => true],
                                ['label'=>'Dagboek', 'twinfield_column' => 'fin.trs.head.code', 'align'=>'left'],
                                ['label'=>'Datum', 'twinfield_column' => 'fin.trs.head.date', 'align'=>'left'],
                                ['label'=>'Factuurnummer', 'twinfield_column' => 'fin.trs.line.invnumber', 'align'=>'left'],
                                ['label'=>'Bedrag', 'twinfield_column' => 'fin.trs.line.valuesigned', 'align'=>'right'],
                                ['label'=>'Openstaand', 'twinfield_column' => 'fin.trs.line.openbasevaluesigned', 'align'=>'right'],
                            ],
                            'group_by_column' => 0,
                            'sum_column' => 5,
                            'browse_definition' => '200',
                            'view' => 'pdf.creditors',
                        ];
        
        return $configuration;
    }

    public function twinfield_columns()
    {   
        $configuration_columns = $this->configuration()['columns'];
        $twinfield_columns = [];

        foreach($configuration_columns as $column)
        {   
            if($column['twinfield_column'] !== '')
            {
                $twinfield_columns[] = (new BrowseColumn())
                    ->setField($column['twinfield_column'])
                    ->setLabel($column['label'])
                    ->setVisible(true);
            }
        }

        return $twinfield_columns;
    }
}
