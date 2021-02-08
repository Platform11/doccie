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
           <img src="{{ $logo }}" class="logo" style="height: 32px;">
        </header>
        <main>
            <h1 class="text-2xl" style="font-family:'Inter var';">{{ $doc_type }} {{ $administration_name }}</h1>
            Datum: {{$date}}

            <div class="mt-4">
                <p>Graag verzoek ik u om de missende documenten voor de vraagposten te uploaden in Basecone.</p>
            </div>

            <div style="margin-top: 12px;">
                <table cellspacing="0" cellpadding="1" width="100%" >
                    <thead style="text-align: left;">
                        <tr>
                            @foreach($headings as $heading)
                            <th>
                                <div style="padding: 4px 8px;">
                                    {{$heading['value']}}
                                </div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lines as $line)
                        <tr>
                            @foreach($line as $cell=>$index)
                            <td valign="top" style="text-align: {{$headings[$cell]['align']}}; height: 1px; {{$headings[$cell]['value'] == 'Boekdatum' ? 'width: 120px;':''}}">
                                <div class="inline-block w-full h-full leading-none bg-gray-100">
                                    <div style="padding: 4px 8px">
                                    {!! $line[$cell] === '' ? '&nbsp;' : $line[$cell] !!}
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
            Voor vragen kunt u contact opnemen met {{$contact['name']}} - <a class="font-medium" style="font-style:bold;" href="mailto:{{$contact['email']}}">{{$contact['email']}}</a> 
        </footer>
    </body>
</html>