<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>RWK Status Pivot By Process</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 7pt;
            background: #ffffff;
            color: #000000;            
        }

        h2 {
            text-align: left;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;   
        }

        th, td {
            padding: 2px;     /* smaller */
            border: 0.5px solid #444;            
            word-wrap: break-word;
            white-space: nowrap;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }

        td.text {
            text-align: left;
        }

        td.number {
            text-align: right;
        }
    </style>
</head>
<body>

<h2 style="text-align:left;">RWK Status Pivot By Process</h2>

@php
    // Get ONLY actual row data
    $columns = $data->isNotEmpty()
        ? array_keys($data->first()->getAttributes())
        : [];

    $textColumns = ['WO NO', 'PART NO', 'TYPE', 'REMARKS NG'];
@endphp

<table>
    <thead>
        <tr>
            @foreach ($columns as $col)
                <th>{{ strtoupper(str_replace('_', ' ', $col)) }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $row)
            @php $row = $row->getAttributes(); @endphp
            <tr>
                @foreach ($columns as $col)
                    @php
                        $value = $row[$col] ?? null;
                        $isNumber = is_numeric($value) && !in_array($col, $textColumns);
                    @endphp

                    <td class="{{ $isNumber ? 'number' : 'text' }}">
                        {{ $isNumber ? number_format((float) $value, 0) : $value }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>