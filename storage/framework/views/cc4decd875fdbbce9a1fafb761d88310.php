

<?php $__env->startSection('title', 'Production Actual Input'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            Add Production Input
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('production.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Process Name</label>
                        <input type="text" name="process_name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Machine Code</label>
                        <input type="text" name="machine_code" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Part No</label>
                        <input type="text" name="product_code" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Target Qty</label>
                        <input type="number" name="target_qty" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Actual Qty</label>
                        <input type="number" name="actual_qty" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Production Date</label>
                        <input type="date" name="production_date" class="form-control" required>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-success w-100">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            Production Records
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Process</th>
                        <th>Machine</th>
                        <th>Product</th>
                        <th>Target</th>
                        <th>Actual</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $inputs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $input): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($input->process_name); ?></td>
                            <td><?php echo e($input->machine_code); ?></td>
                            <td><?php echo e($input->product_code); ?></td>
                            <td><?php echo e($input->target_qty); ?></td>
                            <td><?php echo e($input->actual_qty); ?></td>
                            <td><?php echo e($input->production_date); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No data yet.</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\website\mais-wellbest\resources\views\production\index.blade.php ENDPATH**/ ?>