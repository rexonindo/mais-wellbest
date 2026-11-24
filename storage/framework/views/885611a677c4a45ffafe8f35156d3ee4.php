<?php if (isset($component)) { $__componentOriginalbe23554f7bded3778895289146189db7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbe23554f7bded3778895289146189db7 = $attributes; } ?>
<?php $component = Filament\View\LegacyComponents\Page::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Filament\View\LegacyComponents\Page::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <div class="text-center mb-8">
        <img src="<?php echo e(asset('images/logo-wellbest-with-name.png')); ?>" alt="Company Logo" class="h-32 mx-auto object-contain">
    </div>
    <div class="text-center mb-8 text-gray-700 text-base leading-relaxed">

        <p>&nbsp;&nbsp;</p>       
        <p>
            <h1><strong>Manufacturing Actual Input System (MAIS)</strong></h1>   
            is a system used to record and monitor the real-time inputs of manufacturing process&nbsp;such as raw materials, labor, machine usage, etc.</br>
            It focuses on tracking what is truly used, rather than what was planned or estimated, to improve accuracy, efficiency, and cost control.
        </p>
        <p>&nbsp;</p>
        <p><strong>Benefits of this system :</strong></p>
        <ul>
        <li><strong>Real-time accuracy</strong>: Tracks actual usage of materials, labor, and energy, reducing discrepancies between planned and actual costs.</li>
        <li><strong>Improved cost control</strong>: Helps identify waste, overuse, or inefficiencies, enabling better budgeting and resource allocation.</li>
        <li><strong>Enhanced productivity</strong>: Pinpoints bottlenecks or underperforming areas by comparing actual input data with expected benchmarks.</li>
        <li><strong>Better decision-making</strong>: Provides reliable data for managers to make informed adjustments to processes or supply chains.</li>
        <li><strong>Quality assurance</strong>: Ensures that inputs meet required standards, supporting consistent product quality.</li>
        <li><strong>Compliance and traceability</strong>: Maintains detailed records of inputs for audits, certifications, and regulatory compliance</li>
        </ul>
        <p></br></br>Thank you</p>
        <p><strong>Management</strong></p>
    </div> 
    </br>    

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbe23554f7bded3778895289146189db7)): ?>
<?php $attributes = $__attributesOriginalbe23554f7bded3778895289146189db7; ?>
<?php unset($__attributesOriginalbe23554f7bded3778895289146189db7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbe23554f7bded3778895289146189db7)): ?>
<?php $component = $__componentOriginalbe23554f7bded3778895289146189db7; ?>
<?php unset($__componentOriginalbe23554f7bded3778895289146189db7); ?>
<?php endif; ?>
<?php /**PATH D:\website\mais-wellbest\resources\views/filament/pages/company-welcome.blade.php ENDPATH**/ ?>