<div class="overflow-x-auto">
    <table class="min-w-full border border-gray-300 rounded-lg text-sm">
        <thead class="bg-gray-100 sticky top-0">
            <tr>
                <th class="px-3 py-2 text-left border-b">Seq No</th>
                <th class="px-3 py-2 text-left border-b">Process Code</th>
                <th class="px-3 py-2 text-left border-b">Process Name</th>
                <th class="px-3 py-2 text-left border-b">Cavity</th>
                <th class="px-3 py-2 text-left border-b">Shoot Qty</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-1 border-b"><?php echo e($row->seq_no); ?></td>
                    <td class="px-3 py-1 border-b"><?php echo e($row->proc_cd); ?></td>
                    <td class="px-3 py-1 border-b"><?php echo e($row->proc_nm); ?></td>
                    <td class="px-3 py-1 border-b text-right"><?php echo e($row->cav); ?></td>
                    <td class="px-3 py-1 border-b text-right"><?php echo e($row->shoot_qty); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
</div>
<?php /**PATH D:\website\mais-wellbest\resources\views/filament/components/show-process-table.blade.php ENDPATH**/ ?>