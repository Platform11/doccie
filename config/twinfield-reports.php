<?php

return [
   'unspecified_posts' => [
        'columns' => [
            ['id'=> 'status', 'label'=>'Status', 'twinfield_column' => 'fin.trs.head.status', 'filter_by' => 'temporary'],
            ['id'=> 'journal', 'label'=>'Dagboek', 'twinfield_column' => 'fin.trs.head.code', 'align'=>'left'],
            ['id'=> 'date', 'label'=>'Boekdatum', 'twinfield_column' => 'fin.trs.head.date', 'align'=>'left'],
            ['id'=> 'description', 'label'=>'Omschrijving', 'twinfield_column' => 'fin.trs.line.description', 'align'=>'left'],
            ['id'=> 'total_amount', 'label'=>'Bedrag', 'twinfield_column' => 'fin.trs.line.valuesigned', 'align'=>'right', 'transform' => 'to_positive'],
            ['id'=> 'amount_type', 'label'=>'Ontvangst/Betaling', 'twinfield_column' => '', 'align'=>'left'],
            ['id'=> 'invoice_number', 'label'=>'Factuurnummer', 'twinfield_column' => 'fin.trs.line.invnumber', 'align'=>'left'],        
        ],
        'hidden_columns_in_pdf_export' => ['status'],
        'browse_definition' => '030_3',
        'view' => 'pdf.unspecified_posts',
    ],

   'debtors' => [
        'columns' => [
            ['id'=> 'status', 'label'=>'Status', 'twinfield_column' => 'fin.trs.line.matchstatus', 'filter_by' => 'available'],
            ['id'=> 'relation', 'label'=>'Relatie', 'twinfield_column' => 'fin.trs.line.dim2'],
            ['id'=> 'relation_name', 'label'=>'Relatie', 'twinfield_column' => 'fin.trs.line.dim2name'],
            ['id'=> 'journal', 'label'=>'Dagboek', 'twinfield_column' => 'fin.trs.head.code', 'align'=>'left'],
            ['id'=> 'date', 'label'=>'Datum', 'twinfield_column' => 'fin.trs.head.date', 'align'=>'left'],
            ['id'=> 'invoice_number', 'label'=>'Factuurnummer', 'twinfield_column' => 'fin.trs.line.invnumber', 'align'=>'left'],
            ['id'=> 'total_amount', 'label'=>'Bedrag', 'twinfield_column' => 'fin.trs.line.valuesigned', 'align'=>'right', 'transform' => 'negate'],
            ['id'=> 'open_amount', 'label'=>'Openstaand', 'twinfield_column' => 'fin.trs.line.openbasevaluesigned', 'align'=>'right'],
        ],
        'hidden_columns_in_pdf_export' => ['status', 'relation', 'relation_name'],
        'group_by' => 'relation',
        'group_label' => 'relation_name',
        'browse_definition' => '100',
        'view' => 'pdf.debtors',
    ],

   'creditors' => [
        'columns' => [
            ['id'=> 'status', 'label'=>'Status', 'twinfield_column' => 'fin.trs.line.matchstatus', 'filter_by' => 'available'],
            ['id'=> 'relation', 'label'=>'Relatie', 'twinfield_column' => 'fin.trs.line.dim2'],
            ['id'=> 'relation_name', 'label'=>'Relatie', 'twinfield_column' => 'fin.trs.line.dim2name'],
            ['id'=> 'journal', 'label'=>'Dagboek', 'twinfield_column' => 'fin.trs.head.code', 'align'=>'left'],
            ['id'=> 'date', 'label'=>'Datum', 'twinfield_column' => 'fin.trs.head.date', 'align'=>'left'],
            ['id'=> 'invoice_number', 'label'=>'Factuurnummer', 'twinfield_column' => 'fin.trs.line.invnumber', 'align'=>'left'],
            ['id'=> 'total_amount', 'label'=>'Bedrag', 'twinfield_column' => 'fin.trs.line.valuesigned', 'align'=>'right', 'transform' => 'flip'],
            ['id'=> 'open_amount', 'label'=>'Openstaand', 'twinfield_column' => 'fin.trs.line.openbasevaluesigned', 'align'=>'right', 'transform' => 'flip'],
        ],
        'hidden_columns_in_pdf_export' => ['status', 'relation', 'relation_name'],
        'group_by' => 'relation',
        'group_label' => 'relation_name',
        'browse_definition' => '200',
        'view' => 'pdf.creditors',
    ],
];