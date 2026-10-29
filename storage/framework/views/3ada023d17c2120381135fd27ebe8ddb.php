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
            padding: 2px;     /* smaller */
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
    </style>
</head>
<body>

<h2 style="text-align:left;">NG Detail Pivot By Process</h2>

<?php
    // Get ONLY actual row data
    $columns = $data->isNotEmpty()
        ? array_keys($data->first()->getAttributes())
        : [];

    $textColumns = ['WO NO', 'PART NO', 'TYPE', 'REMARKS NG'];
?>

<table>
    <thead>
        <tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th><?php echo e(strtoupper(str_replace('_', ' ', $col))); ?></th>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tr>
    </thead>

    <tbody>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $row = $row->getAttributes(); ?>
            <tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $value = $row[$col] ?? null;
                        $isNumber = is_numeric($value) && !in_array($col, $textColumns);
                    ?>

                    <td class="<?php echo e($isNumber ? 'number' : 'text'); ?>">
                        <?php echo e($isNumber ? number_format((float) $value, 0) : $value); ?>

                    </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </tbody>
</table>

</body>
</html><?php /**PATH D:\website\mais-wellbest\resources\views\pdf\ng-detail-pivot-report.blade.php ENDPATH**/ ?>