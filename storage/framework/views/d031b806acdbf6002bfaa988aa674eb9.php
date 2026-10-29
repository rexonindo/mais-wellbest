<?php if (isset($component)) { $__componentOriginal166a02a7c5ef5a9331faf66fa665c256 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.page.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="ng-report-layout">

        
        <div class="ng-report-selectors">

            
            <div class="ng-report-field">
                <label for="reportCategory">REPORT BY :</label>

                
                <select id="reportCategory" wire:model="reportCategory">
                    <option value="DIVISION">DIVISION</option>
                    <option value="PROCESS">PROCESS</option>
                </select>
            </div>

            
            <div class="ng-report-field">
                <label for="reportType">Start From Process :</label>

                <select id="reportType" wire:model="reportType">
                    <option value="CUTTING">CUTTING</option>
                    <option value="TEST">TEST</option>
                </select>

                <button
                    type="button"
                    class="ng-report-ok"
                    wire:click="applyReport"
                    wire:loading.attr="disabled"
                    wire:target="applyReport"
                >
                    <span wire:loading.remove wire:target="applyReport">OK</span>
                    <span wire:loading wire:target="applyReport">Loading...</span>
                </button>
            </div>

        </div>

        
        <div class="ng-report-filters">
            <?php echo e($this->form); ?>

        </div>

        
        <div class="ng-report-table">
            <?php echo e($this->table); ?>

        </div>

    </div>

    <style>
        .ng-report-layout {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .ng-report-selectors {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            align-items: center;
            gap: 24px;
            margin: 0 !important;
            padding: 0 !important;
        }

        .ng-report-field {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 !important;
            padding: 0 !important;
        }

        .ng-report-field label {
            font-weight: 600;
            white-space: nowrap;
            margin: 0;
        }

        .ng-report-field select {
            width: 180px;
            padding: 5px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: white;
            color: #111827;
        }

        .ng-report-ok {
            padding: 5px 18px;
            border-radius: 6px;
            background: #059669;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        .ng-report-ok:hover { background: #047857; }
        .ng-report-ok:disabled { opacity: .6; cursor: wait; }

        .ng-report-filters {
            margin: 0 !important;
            padding: 0 !important;
        }

        .ng-report-filters > * {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        .ng-report-table { margin-top: 0 !important; }

        .fi-ta-content {
            max-height: calc(100vh - 250px);
            overflow-y: auto;
        }

        .fi-ta-table thead th {
            position: sticky;
            top: 0;
            z-index: 1;
            background: white;
        }

        .fi-page-content { gap: 4px !important; }
        .fi-page-content > * { margin-block: 0 !important; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                if (window.Alpine && Alpine.store('sidebar')) {
                    Alpine.store('sidebar').isOpen = false;
                }
            }, 200);
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $attributes = $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $component = $__componentOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?><?php /**PATH D:\website\mais-wellbest\resources\views/filament/pages/ng-detail-pivot-report.blade.php ENDPATH**/ ?>