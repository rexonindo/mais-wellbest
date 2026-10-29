<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Status WO Report</title>
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

<h3>Inventory Status WO Report</h3>
<p>Generated: <?php echo e(now()->format('Y-m-d H:i:s')); ?></p>

<table>
    <thead>
        <tr>
            <th>WO No</th>
            <th>Part No</th>
            <th>WIP Code</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($row->wo_no); ?></td>
                <td><?php echo e($row->itm_cd); ?></td>
                <td><?php echo e($row->wip_cd); ?></td>
                <td class="qty"><?php echo e(number_format($row->qty, 0)); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </tbody>
</table>

</body>
</html>
<?php /**PATH D:\website\mais-wellbest\resources\views\pdf\iv-status-wo-report.blade.php ENDPATH**/ ?>