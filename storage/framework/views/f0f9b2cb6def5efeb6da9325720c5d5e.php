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
                <th>IN Qty</th>
                <th>Rework Qty</th>
                <th>NG Qty</th>
                <th>OUT Qty</th>
                <th>Machine</th>
                <th>Employee</th>
                <th>Start</th>
                <th>Finish</th>                
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($row->wo_no); ?></td>
                    <td><?php echo e($row->itm_cd); ?></td>
                    <td><?php echo e($row->itm_type); ?></td>
                    <td><?php echo e($row->seq_no); ?></td>
                    <td><?php echo e($row->proc_cd); ?></td>
                    <td><?php echo e($row->proc_nm); ?></td>
                    <td><?php echo e($row->wo_qty); ?></td>
                    <td><?php echo e($row->cav); ?></td>
                    <td><?php echo e($row->in_qty); ?></td>
                    <td><?php echo e($row->rwk_qty); ?></td>
                    <td><?php echo e($row->ng_qty); ?></td>
                    <td><?php echo e($row->out_qty); ?></td>
                    <td><?php echo e($row->mchn_cd); ?></td>
                    <td><?php echo e($row->emp_nm); ?></td>
                    <td><?php echo e($row->start_time); ?></td>
                    <td><?php echo e($row->end_time); ?></td>                    
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH D:\website\mais-wellbest\resources\views/pdf/wo-progress-report.blade.php ENDPATH**/ ?>