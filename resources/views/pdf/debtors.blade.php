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
           <img src="{{ $data->report->overview->administration->account->logo }}" class="logo" style="height: 32px;">
        </header>
        <main>
            <h1 class="text-2xl" style="font-family:'Inter var';">{{ 'Debiteurenoverzicht ' . $data->report->overview->administration->name }}</h1>
            
            <div class="mt-4 mb-4">
                Datum: {{date('d-m-Y - H:i')}}<br>
                Totaal openstaand:  {{$data->total}}
            </div>
            @php 
                $i = 0;
                $visible_columns = $data->report->visible_columns();
            @endphp
            @foreach($data->rows as $index_debtor=>$debtor)
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
                            <td colspan="{{count($debtor['rows'][0])}}">
                                <div class="flex justify-between w-full h-full leading-none bg-gray-100">
                                    <div style="padding: 4px 8px">
                                        <h3>{{$debtor['name']}}</h3>
                                    </div>
                                    <div style="padding: 4px 8px">
                                        <h3>Totaal: {{$debtor['total']}}</h3>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @foreach($debtor['rows'] as $line)
                        <tr>
                            @foreach($line as $index=>$cell)

                            @php
                                $label = $visible_columns[$index]['label'];
                                $width = '';
                                if($label == 'Dagboek')
                                {
                                    $width = '150px';
                                }
                                if($label == 'Datum')
                                {
                                    $width = '130px';
                                }
                                if($label == 'Bedrag')
                                {
                                    $width = '150px';
                                }
                                if($label == 'Openstaand')
                                {
                                    $width = '250px';
                                }
                            @endphp
    
                            <td valign="top" style="text-align: {{$visible_columns[$index]['align']}}; height: 1px; {{!empty($width) ? 'width: '.$width.';':''}}">
                                <div class="inline-block w-full h-full leading-none bg-gray-100">
                                    <div style="padding: 4px 8px">
                                    {!! $cell === '' ? '&nbsp;' : $cell !!}
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
            @php $i++; @endphp
            @endforeach
        </main>
        <footer style="text-align:right; margin-top:24px;">
            Voor vragen kunt u contact opnemen met {{$data->report->overview->author->first_name}} {{$data->report->overview->author->last_name}} - <a class="font-medium" style="font-style:bold;" href="mailto:{{$data->report->overview->author->email}}">{{$data->report->overview->author->email}}</a> 
        </footer>
    </body>
</html>