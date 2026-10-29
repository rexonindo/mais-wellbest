<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>WO Progress Pivot By Process</title>

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
            padding: 2px;
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

        .total-row {
            font-weight: bold;
            background-color: #e8e8e8;
        }

        thead {
            display: table-header-group; /* repeat header on each page */
        }
    </style>
</head>
<body>

<h2>WO Progress Pivot By Process</h2>

<?php
    /* ----------------------------------
       Detect columns dynamically
       ---------------------------------- */
    $columns = $data->isNotEmpty()
        ? array_keys($data->first()->getAttributes())
        : [];

    /* ----------------------------------
       Text columns (not summed)
       ---------------------------------- */
    $textColumns = ['WO NO', 'PART NO', 'TYPE', 'REMARKS NG'];

    /* ----------------------------------
       Prepare totals
       ---------------------------------- */
    $totals = [];

    foreach ($columns as $col) {
        $totals[$col] = 0;
    }
?>

<table>

    <!-- HEADER -->
    <thead>

    <?php
        $baseColumns = ['WO NO', 'PART NO', 'TYPE', 'END DATE', 'WO QTY', 'CAV'];

        $groups = [];
        $order = [];

        foreach ($columns as $col) {

            // Fixed columns
            if (in_array($col, $baseColumns)) {
                $groups[$col] = [$col];
                $order[] = $col;
                continue;
            }

            // Process columns
            if (preg_match('/^(.*)_(IN|OK|RWK|NG|TTL)_QTY$/', $col, $m)) {

                $process = strtoupper(str_replace('_', ' ', $m[1]));

                if (!isset($groups[$process])) {
                    $groups[$process] = [];
                    $order[] = $process;
                }

                $groups[$process][] = $col;

            } else {
                $groups[$col] = [$col];
                $order[] = $col;
            }
        }
    ?>

    <!-- TOP HEADER -->
    <tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php $cols = $groups[$key]; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($cols) === 1 && in_array($cols[0], $baseColumns)): ?>
                <th rowspan="2">
                    <?php echo e(strtoupper(str_replace('_',' ',$cols[0]))); ?>

                </th>
            <?php else: ?>
                <th colspan="<?php echo e(count($cols)); ?>">
                    <?php echo e($key); ?>

                </th>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </tr>

    <!-- SUB HEADER -->
    <tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php $cols = $groups[$key]; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!(count($cols) === 1 && in_array($cols[0], $baseColumns))): ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $cols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php
                        preg_match('/_(IN|OK|RWK|NG|TTL)_QTY$/', $col, $m);
                        $type = $m[1] ?? '';
                    ?>

                    <th><?php echo e($type); ?> QTY</th>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </tr>

    </thead>

    <!-- BODY -->
    <tbody>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php
                $row = $row->getAttributes();
            ?>

            <tr>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php
                        $value = $row[$col] ?? null;
                        $isNumber = is_numeric($value) && !in_array($col,$textColumns);

                        if ($isNumber) {
                            $totals[$col] += (float)$value;
                        }
                    ?>

                    <td class="<?php echo e($isNumber ? 'number' : 'text'); ?>">
                        <?php echo e($isNumber ? number_format((float)$value,0) : $value); ?>

                    </td>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </tr>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- TOTAL ROW -->
        <tr class="total-row">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php
                    $isNumber = !in_array($col,$textColumns);
                ?>

                <td class="<?php echo e($isNumber ? 'number' : 'text'); ?>">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($index == 0): ?>
                        TOTAL
                    <?php elseif($isNumber): ?>
                        <?php echo e(number_format($totals[$col],0)); ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </td>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </tr>

    </tbody>

</table>

</body>
</html><?php /**PATH D:\website\mais-wellbest\resources\views\pdf\wo-progress-pivot-report.blade.php ENDPATH**/ ?>