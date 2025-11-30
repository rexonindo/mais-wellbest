<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Barcode Labels</title>
    <style>
        body { margin:0; padding:0; font-family: Arial, sans-serif; font-size:8pt; }
        table { width:198.4pt; height:141.7pt; border-collapse:collapse; page-break-after:always; }
        td { padding:2pt 2pt 0 2pt; vertical-align:top; }
        .barcode img { display:block; height:30pt; max-width:135pt; margin-bottom:2pt; }
        .text p { margin:0 0 1pt 0; line-height:1.1; }
    </style>
</head>
<body>
<?php $__currentLoopData = $processes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $process): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <table>
        <tr>
            <td>
                <div class="barcode">
                    <img src="data:image/png;base64,<?php echo e((new Milon\Barcode\DNS1D())->getBarcodePNG($process->proc_cd, 'C39')); ?>" alt="Barcode">
                </div>
                <div class="text">
                    <p>Process Code: <?php echo e($process->proc_cd); ?></p>
                    <p>Process Name: <?php echo e($process->proc_nm); ?></p>
                </div>
            </td>
        </tr>
    </table>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</body>
</html>
<?php /**PATH D:\website\mais-wellbest\resources\views/process/barcode-label-multiple.blade.php ENDPATH**/ ?>