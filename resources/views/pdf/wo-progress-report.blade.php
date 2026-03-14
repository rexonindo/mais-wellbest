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
                <th>Part No</th>
                <th>Part Type</th>
                <th>Seq No</th>
                <th>Process Code</th>
                <th>Process Name</th>
                <th>WO Qty</th>
                <th>Cavity</th>
                <th>OUT Qty</th>
                <th>Rework Qty</th>
                <th>NG Qty</th>
                <th>OK Qty</th>
                <th>Total Qty</th>
                <th>Total Qty (Shoot)</th>
                <th>On-Hand Qty</th>
                <th>Machine</th>
                <th>Employee</th>
                <th>Start</th>
                <th>Finish</th>                
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
                    <td>{{ $row->wo_qty }}</td>
                    <td>{{ $row->cav }}</td>
                    <td>{{ $row->in_qty }}</td>
                    <td>{{ $row->rwk_qty }}</td>
                    <td>{{ $row->ng_qty }}</td>
                    <td>{{ $row->out_qty }}</td>
                    <td>{{ $row->ttl_qty }}</td>
                    <td>{{ $row->ttl_qty_shoot }}</td>
                    <td>{{ $row->onhand_qty }}</td>
                    <td>{{ $row->mchn_cd }}</td>
                    <td>{{ $row->emp_nm }}</td>
                    <td>{{ $row->start_time }}</td>
                    <td>{{ $row->end_time }}</td>                    
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
