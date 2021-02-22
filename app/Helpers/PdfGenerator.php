<?php

namespace App\Helpers;

use Spatie\Browsershot\Browsershot;

class PdfGenerator
{
    public static function generate($data)
    {
        logger($data->report->type);
        Browsershot::html(view($data->report->configuration['view'], ['data' => $data])->render())
        ->addChromiumArguments([
            'font-render-hinting' => 'none',
        ])
        ->setOption('addStyleTag', json_encode(['content' => file_get_contents(public_path('css/app.css'))]))
        ->format('A4')
        ->margins(20, 20, 20, 20)
        ->landscape(true)
        ->save($data->path);
    }
}