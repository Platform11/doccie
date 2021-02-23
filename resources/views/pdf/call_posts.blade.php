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
            <h1 class="text-2xl" style="font-family:'Inter var';">{{ 'Vraagposten ' . $data->report->overview->administration->name }}</h1>
            
            <div class="mt-4">
                Datum: {{date('d-m-Y - H:i')}}
            </div>
            <div class="mt-4">
                <p>Graag verzoek ik u om de missende documenten voor de vraagposten te uploaden in Basecone.</p>
            </div>
            
            @php
               $visible_columns = $data->report->visible_columns();
            @endphp

            <div style="margin-top: 12px;">
                <table cellspacing="0" cellpadding="1" width="100%" >
                    <thead style="text-align: left;">
                        <tr>
                            @foreach($visible_columns as $column)
                                <th>
                                    <div style="padding: 4px 8px;">
                                        {{$column['label']}}
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->rows as $row)
                        <tr>
                            @foreach($row as $index=>$cell)
                            <td valign="top" style="text-align: {{$visible_columns[$index]['align']}}; height: 1px; {{$visible_columns[$index]['label'] == 'Boekdatum' ? 'width: 120px;':''}}">
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
            </div>
        </main>
        <footer style="text-align:right; margin-top:24px;">
            Voor vragen kunt u contact opnemen met {{$data->report->overview->author->first_name}} {{$data->report->overview->author->last_name}} - <a class="font-medium" style="font-style:bold;" href="mailto:{{$data->report->overview->author->email}}">{{$data->report->overview->author->email}}</a> 
        </footer>
    </body>
</html>