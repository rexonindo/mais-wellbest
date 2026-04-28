
<?php $__env->startSection('title', 'Production Log'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">Add Production Log</div>
        <div class="card-body">
            <form action="<?php echo e(route('prdlog.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label>Work Order</label>
                        <select name="wo_no" class="form-select" required>
                            <option value="">-- Select WO --</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $workOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($wo); ?>"><?php echo e($wo); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Item</label>
                        <select name="itm_cd" class="form-select" required>
                            <option value="">-- Select Item --</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cd => $nm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cd); ?>"><?php echo e($nm); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Process</label>
                        <select name="proc_cd" class="form-select" required>
                            <option value="">-- Select Process --</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $processes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cd => $nm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cd); ?>"><?php echo e($nm); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Machine</label>
                        <select name="mchn_cd" class="form-select">
                            <option value="">-- Select Machine --</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cd => $nm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cd); ?>"><?php echo e($nm); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Employee</label>
                        <select name="emp_id" class="form-select" required>
                            <option value="">-- Select Operator --</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($id); ?>"><?php echo e($nm); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Start Time</label>
                        <input type="datetime-local" name="start_time" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>End Time</label>
                        <input type="datetime-local" name="end_time" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label>In Qty</label>
                        <input type="number" step="0.01" name="in_qty" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>Out Qty</label>
                        <input type="number" step="0.01" name="out_qty" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>NG Qty</label>
                        <input type="number" step="0.01" name="ng_qty" class="form-control" value="0">
                    </div>

                    <div class="col-md-12">
                        <label>Remarks</label>
                        <textarea name="rmks" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="col-md-12 text-end">
                        <button class="btn btn-success mt-2">Save Log</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">Production Logs</div>
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>WO No</th>
                        <th>Item</th>
                        <th>Process</th>
                        <th>Machine</th>
                        <th>Employee</th>
                        <th>Out Qty</th>
                        <th>NG Qty</th>
                        <th>Start</th>
                        <th>End</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($log->wo_no); ?></td>
                            <td><?php echo e($log->item->itm_nm ?? '-'); ?></td>
                            <td><?php echo e($log->process->proc_nm ?? '-'); ?></td>
                            <td><?php echo e($log->machine->mchn_nm ?? '-'); ?></td>
                            <td><?php echo e($log->employee->emp_nm ?? '-'); ?></td>
                            <td><?php echo e($log->out_qty); ?></td>
                            <td><?php echo e($log->ng_qty); ?></td>
                            <td><?php echo e($log->start_time); ?></td>
                            <td><?php echo e($log->end_time); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted">No records yet.</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\website\mais-wellbest\resources\views\production\log.blade.php ENDPATH**/ ?>