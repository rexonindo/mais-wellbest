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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($row->start_time); ?></td>
                    <td><?php echo e($row->itm_cd); ?></td>
                    <td><?php echo e($row->itm_type); ?></td>
                    <td><?php echo e($row->proc_nm); ?></td>
                    <td><?php echo e($row->ng_nm); ?></td>
                    <td><?php echo e($row->ng_qty); ?></td>
                    <td><?php echo e($row->emp_id); ?></td>
                    <td><?php echo e($row->mchn_nm); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH D:\website\mais-wellbest\resources\views\pdf\ng-log-report.blade.php ENDPATH**/ ?>