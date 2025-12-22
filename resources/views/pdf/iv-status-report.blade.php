<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Status Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td {
            border: 1px solid #000;
            padding: 6px;
        }
        th { background: #f2f2f2; }
        td.qty { text-align: right; }
    </style>
</head>
<body>

<h3>Inventory Status Report</h3>
<p>Generated: {{ now()->format('Y-m-d H:i:s') }}</p>

<table>
    <thead>
        <tr>
            <th>Part No</th>
            <th>WIP Code</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                <td>{{ $row->itm_cd }}</td>
                <td>{{ $row->wip_cd }}</td>
                <td class="qty">{{ number_format($row->qty, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
