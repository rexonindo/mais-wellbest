<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>WO Progress Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; font-size: 10pt; }
        th, td { border: 1px solid #444; padding: 4px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2>WO Progress Report</h2>
    <table>
        <thead>
            <tr>
                <th>WO No</th>
                <th>Item Code</th>
                <th>Item Type</th>
                <th>Seq No</th>
                <th>Proc Code</th>
                <th>Proc Name</th>
                <th>In Qty</th>
                <th>NG Qty</th>
                <th>Out Qty</th>
                <th>Machine</th>
                <th>Employee</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row->wo_no }}</td>
                    <td>{{ $row->itm_cd }}</td>
                    <td>{{ $row->itm_type }}</td>
                    <td>{{ $row->seq_no }}</td>
                    <td>{{ $row->proc_cd }}</td>
                    <td>{{ $row->proc_nm }}</td>
                    <td>{{ $row->in_qty }}</td>
                    <td>{{ $row->ng_qty }}</td>
                    <td>{{ $row->out_qty }}</td>
                    <td>{{ $row->mchn_cd }}</td>
                    <td>{{ $row->emp_nm }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
