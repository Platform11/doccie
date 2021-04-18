<!DOCTYPE html>
<html>  
    <head>
        <meta charset="UTF-8">
        {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
    </head>

    <style type="text/css">
        @font-face {
            font-family: "Inter var";
            font-weight: 100 900;
            font-display: swap;
            font-style: normal;
            font-named-instance: "Regular";
            src: url("data:application/font-woff2;charset=utf-8;base64,{{base64_encode(file_get_contents(public_path('fonts/Inter-roman.var.woff2')))}}") format("woff2");
        }

        @font-face {
            font-family: "Inter var";
            font-weight: 100 900;
            font-display: swap;
            font-style: italic;
            font-named-instance: "Italic";
            src: url("data:application/font-woff2;charset=utf-8;base64,{{base64_encode(file_get_contents(public_path('fonts/Inter-italic.var.woff2')))}}") format("woff2");
        }

        html {
        -webkit-print-color-adjust: exact;
        }
    </style>
   
    <body style="width: 100%; margin:0; font-family:'Inter var';">
        <header style="margin-bottom: 24px;">
           <img src="{{ $report->overview->administration->account->logo }}" class="logo" style="height: 32px;">
        </header>
        <main>
            <h1 class="text-2xl" style="font-family:'Inter var';">{{ 'Debiteurenoverzicht ' . $report->overview->administration->name }}</h1>
            
            <div class="mt-4 mb-4">
                Datum: {{date('d-m-Y - H:i')}}<br>

                @php
                    $total_amount = $data->reduce(function ($carry, $group) {
                        return $carry + $group['total'];
                    });
  
                    $formatter = new \NumberFormatter('nl_NL', \NumberFormatter::CURRENCY);
                @endphp
                Totaal openstaand:  {{ $formatter->formatCurrency((float)$total_amount, 'EUR')}}
            </div>
            
            @php
                $i = 0;
                $visible_columns = $report->visible_columns_pdf_export();
            @endphp

            @foreach($data as $group)
            <div style="margin-top: 14px;">
                
                <table cellspacing="0" cellpadding="1" width="100%" >
                    @if($i == 0)
                        <thead style="text-align: left;">
                            <tr>
                                @foreach($visible_columns as $column)
                                    <th style="text-align: {{$column['align']}};">
                                        <div style="padding: 4px 8px 8px;">
                                            {{$column['label']}}
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                    @endif
                    <tbody>
                        <tr>
                            <td colspan="{{count($visible_columns)}}">
                                <div class="flex justify-between w-full h-full leading-none bg-gray-100">
                                    <div style="padding: 4px 8px">
                                        <h3>{{$group['name']}}</h3>
                                    </div>
                                    <div style="padding: 4px 8px">
                                        <h3>Totaal: {{ $formatter->formatCurrency((float)$group['total'], 'EUR') }}</h3>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @foreach($group['rows'] as $row)
                        <tr>
                            @php
                                $cells_to_show = $row->reject(function($cell) use ($data){
                                    return collect($report->configuration()['hidden_columns_in_pdf_export'])->contains($cell['id']);
                                });

                                ray($cells_to_show);
                            @endphp
                            @foreach($cells_to_show as $cell)
                          
                            @php
                                switch ($cell['id'])
                                {
                                    case 'journal':
                                        $width = '150px';
                                        break;
                                     case 'date':
                                        $width = '130px';
                                        break;
                                    case 'total_amount':
                                        $width = '150px';
                                        break;
                                    case 'open_amount':
                                        $width = '250px';
                                        break;
                                    default:
                                        $width = '';
                                }
                            @endphp
    
                            <td valign="top" style="text-align: {{$cell['align']}}; height: 1px; {{!empty($width) ? 'width: '.$width.';':''}}">
                                <div class="inline-block w-full h-full leading-none bg-gray-100">
                                    <div style="padding: 4px 8px">
                                        @if($cell['id'] == 'date')
                                            {{ $cell['value']->format('m-d-Y') }}
                                        @elseif(collect(['total_amount', 'open_amount'])->contains($cell['id']))
                                            {{ $formatter->formatCurrency((float)$cell['value'], 'EUR') }}
                                        @else
                                            {!! $cell['value'] === '' ? '&nbsp;' : $cell['value'] !!}
                                        @endif
                                    </div>
                                </div>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- <div class="w-full font-medium text-right" style="padding: 4px 8px">
                   
                </div> --}}
            </div>
            @php($i++)
        @endforeach

        </main>
        <footer style="text-align:right; margin-top:24px;">
            Voor vragen kunt u contact opnemen met {{$report->overview->author->first_name}} {{$report->overview->author->last_name}} - <a class="font-medium" style="font-style:bold;" href="mailto:{{$report->overview->author->email}}">{{$report->overview->author->email}}</a> 
        </footer>
    </body>
</html>