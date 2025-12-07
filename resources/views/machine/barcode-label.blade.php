<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Barcode Label</title>
    <style>
        html {
            margin: 0;
            padding: 0;
        }        
        body {
            margin: 0;
            padding: 0;
        }
        .label {
            width: 198.4pt;   /* 70mm */
            height: 141.7pt;  /* 50mm */
            position: relative;
        }
        .content {
            position: absolute;
            top: 0;
            left: 0;
        }
        .barcode img {
            height: 45pt;
            max-width: 160pt;
            display: block; /* important: remove inline whitespace */
        }
        .text p {
            font-family: Arial, sans-serif;
            font-size: 10pt;            
            margin: 0 0 1pt 0;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td style="padding:0; position:relative;">
                <div style="position:absolute; top:13; left:13;">
                    <div class="barcode" style="margin-bottom:5pt;">
                        <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode">
                    </div>
                    <div class="text">
                        <p>Machine Code: {{ $machine->mchn_cd }}</p>
                        <p>{{ $machine->dsc }}</p>
                        <p>{{ $machine->mchn_nm }}</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
