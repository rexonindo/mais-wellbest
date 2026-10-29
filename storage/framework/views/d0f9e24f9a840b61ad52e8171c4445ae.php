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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($row->wo_no); ?></td>
                    <td><?php echo e($row->cust_nm); ?></td>
                    <td><?php echo e($row->req_dt); ?></td>
                    <td><?php echo e($row->itm_cd); ?></td>
                    <td><?php echo e($row->itm_type); ?></td>
                    <td><?php echo e($row->proc_cd); ?></td>
                    <td><?php echo e($row->proc_nm); ?></td>
                    <td><?php echo e($row->end_time); ?></td>
                    <td><?php echo e($row->plan_qty); ?></td>
                    <td><?php echo e($row->out_qty); ?></td>
                    <td><?php echo e($row->os_qty); ?></td>
                    <td><?php echo e($row->mchn_cd); ?></td>
                    <td><?php echo e($row->emp_nm); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH D:\website\mais-wellbest\resources\views\pdf\wo-status-report.blade.php ENDPATH**/ ?>