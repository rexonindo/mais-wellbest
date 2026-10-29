<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Barcode Label</title>
    <style>
        html {
            margin: 0;
            padding: 0;
        }        
        body {
            margin: 0;
            padding: 0;
        }
        .label {
            width: 180 /* 198.4pt;   /* 70mm */
            height: 130 /* 141.7pt;  /* 50mm */
            position: relative;
        }
        .content {
            position: absolute;
            top: 0;
            left: 0;
        }
        .barcode img {
            height: 45pt;
            max-width: 160pt;
            display: block; /* important: remove inline whitespace */
        }
        .text p {
            font-family: Arial, sans-serif;
            font-size: 10pt;            
            margin: 0 0 1pt 0;
            line-height: 1.5;
        }
    </style>
</head>
<body>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <table class="label" style="page-break-after: always;">
        <tr>
            <td style="padding:0; position:relative;">
                <div style="position:absolute; top:13; left:13;">
                    <div class="barcode" style="margin-bottom:5pt;">
                        <img src="data:image/png;base64,<?php echo e($machine->barcode); ?>">
                    </div>
                    <div class="text">
                        <p>Machine Code: <?php echo e($machine->mchn_cd); ?></p>
                        <p><?php echo e($machine->dsc); ?></p>
                        <p><?php echo e($machine->mchn_nm); ?></p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</body>
</html>
<?php /**PATH D:\website\mais-wellbest\resources\views\machine\barcode-label-multiple.blade.php ENDPATH**/ ?>