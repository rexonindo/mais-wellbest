<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>WO Status Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; font-size: 10pt; }
        th, td { border: 1px solid #444; padding: 4px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2>NG Log Report</h2>
    <table>
        <thead>
            <tr>
                <th>Process Date</th>
                <th>Part No</th>
                <th>Part Type</th>
                <th>Proc Name</th>
                <th>NG Name</th>
                <th>Qty NG</th>
                <th>Operator</th>                
                <th>Machine</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row->start_time }}</td>
                    <td>{{ $row->itm_cd }}</td>
                    <td>{{ $row->itm_type }}</td>
                    <td>{{ $row->proc_nm }}</td>
                    <td>{{ $row->ng_nm }}</td>
                    <td>{{ $row->ng_qty }}</td>
                    <td>{{ $row->emp_id }}</td>
                    <td>{{ $row->mchn_nm }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
