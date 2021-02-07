<!DOCTYPE html>
<html>  

    <style type="text/css">

        @import url(https://fonts.googleapis.com/css2?family=Inter&display=swap);
        body {
          font-family: 'Inter', sans-serif;
        }

        html {
        -webkit-print-color-adjust: exact;
        }

        
    </style>
   
    <body style="width: 100%; margin:0; font-family:'Inter';">
        <header style="margin-bottom: 24px;">
           <img src="{{ $logo }}" class="logo" style="height: 32px;">
        </header>
        <main>
            <h1 class="text-2xl" style="font-family:'Inter'; font-weight:700;">{{ $doc_type }} {{ $administration_name }}</h1>
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
                                    <strong>{{$heading['value']}}</strong>
                                </div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lines as $line)
                        <tr>
                            @foreach($line as $cell=>$index)
                            <td style="text-align: {{$headings[$cell]['align']}};">
                                <div style="padding: 4px 8px" class="bg-gray-100">
                                    {!! $line[$cell] === '' ? '&nbsp;' : $line[$cell] !!}
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
            Voor vragen kunt u contact opnemen met {{$contact['name']}} - <a class="font-medium" style="color: {{$account['color']}};" href="mailto:{{$contact['email']}}">{{$contact['email']}}</a> 
        </footer>
    </body>
</html>