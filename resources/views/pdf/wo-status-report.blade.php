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
    <h2>WO Status Report</h2>
    <table>
        <thead>
            <tr>
                <th>WO No</th>
                <th>Customer Name</th>
                <th>Request Date</th>
                <th>Part No</th>
                <th>Part Type</th>
                <th>Proc Code</th>
                <th>Proc Name</th>
                <th>End Time</th>
                <th>Plan Qty</th>
                <th>Out Qty</th>
                <th>O/S Qty</th>
                <th>Machine</th>
                <th>Employee</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row->wo_no }}</td>
                    <td>{{ $row->cust_nm }}</td>
                    <td>{{ $row->req_dt }}</td>
                    <td>{{ $row->itm_cd }}</td>
                    <td>{{ $row->itm_type }}</td>
                    <td>{{ $row->proc_cd }}</td>
                    <td>{{ $row->proc_nm }}</td>
                    <td>{{ $row->end_time }}</td>
                    <td>{{ $row->plan_qty }}</td>
                    <td>{{ $row->out_qty }}</td>
                    <td>{{ $row->os_qty }}</td>
                    <td>{{ $row->mchn_cd }}</td>
                    <td>{{ $row->emp_nm }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
