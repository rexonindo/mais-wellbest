<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>NG Status Pivot By Process</title>

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
            padding: 2px;
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

        .total-row {
            font-weight: bold;
            background-color: #e8e8e8;
        }

        thead {
            display: table-header-group; /* repeat header on each page */
        }
    </style>
</head>
<body>

<h2>NG Status Pivot By Process</h2>

@php
    /* ----------------------------------
       Detect columns dynamically
       ---------------------------------- */
    $columns = $data->isNotEmpty()
        ? array_keys($data->first()->getAttributes())
        : [];

    /* ----------------------------------
       Text columns (not summed)
       ---------------------------------- */
    $textColumns = ['WO NO', 'PART NO', 'TYPE', 'REMARKS NG'];

    /* ----------------------------------
       Prepare totals
       ---------------------------------- */
    $totals = [];

    foreach ($columns as $col) {
        $totals[$col] = 0;
    }
@endphp

<table>

    <!-- HEADER -->
    <thead>
        <tr>
            @foreach ($columns as $col)
                <th>{{ strtoupper(str_replace('_',' ',$col)) }}</th>
            @endforeach
        </tr>
    </thead>

    <!-- BODY -->
    <tbody>

        @foreach ($data as $row)

            @php
                $row = $row->getAttributes();
            @endphp

            <tr>

                @foreach ($columns as $col)

                    @php
                        $value = $row[$col] ?? null;
                        $isNumber = is_numeric($value) && !in_array($col,$textColumns);

                        if ($isNumber) {
                            $totals[$col] += (float)$value;
                        }
                    @endphp

                    <td class="{{ $isNumber ? 'number' : 'text' }}">
                        {{ $isNumber ? number_format((float)$value,0) : $value }}
                    </td>

                @endforeach

            </tr>

        @endforeach

        <!-- TOTAL ROW -->
        <tr class="total-row">

            @foreach ($columns as $index => $col)

                @php
                    $isNumber = !in_array($col,$textColumns);
                @endphp

                <td class="{{ $isNumber ? 'number' : 'text' }}">

                    @if ($index == 0)
                        TOTAL
                    @elseif ($isNumber)
                        {{ number_format($totals[$col],0) }}
                    @endif

                </td>

            @endforeach

        </tr>

    </tbody>

</table>

</body>
</html>