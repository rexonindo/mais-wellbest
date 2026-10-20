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

    <style>
        .cd-wrap { display: flex; flex-direction: column; gap: 1.25rem; }
        .cd-grid { display: grid; gap: 1rem; }
        .cd-g5 { grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); }
        .cd-g2 { grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); }
        .cd-card { background: #fff; border: 1px solid #e5e7eb; border-radius: .75rem; padding: 1rem 1.25rem; }
        .dark .cd-card { background: #18181b; border-color: #27272a; }
        .cd-label { font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; color: #6b7280; }
        .cd-value { font-size: 1.75rem; font-weight: 700; margin-top: .25rem; }
        .cd-sub { font-size: .75rem; color: #6b7280; margin-top: .15rem; }
        .cd-title { font-weight: 600; margin-bottom: .75rem; }
        .cd-chart { position: relative; height: 300px; }
        .cd-bad { color: #dc2626; }
        .cd-ok { color: #16a34a; }
        .cd-filters { display: flex; flex-wrap: wrap; gap: .75rem; align-items: end; }
        .cd-filters label { font-size: .75rem; color: #6b7280; display: block; margin-bottom: .2rem; }
        .cd-filters input, .cd-filters select {
            border: 1px solid #d1d5db; border-radius: .5rem; padding: .35rem .6rem; background: transparent; font-size: .875rem;
        }
        .cd-btn { border: 1px solid #d1d5db; border-radius: .5rem; padding: .35rem .8rem; font-size: .8rem; cursor: pointer; background: transparent; }
        .cd-btn:hover { background: rgba(59,130,246,.1); }
        .cd-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
        .cd-table th { text-align: left; color: #6b7280; font-weight: 600; padding: .4rem .5rem; border-bottom: 1px solid #e5e7eb; }
        .cd-table td { padding: .4rem .5rem; border-bottom: 1px solid #f3f4f6; }
        .dark .cd-table td { border-color: #27272a; }
        .cd-table .num { text-align: right; font-variant-numeric: tabular-nums; }
        .cd-row-alert { background: rgba(220,38,38,.08); }
    </style>

    <?php if (! $__env->hasRenderedOnce('386f0e29-3d24-430f-a3f8-36233029daed')): $__env->markAsRenderedOnce('386f0e29-3d24-430f-a3f8-36233029daed'); ?>
        <script src="<?php echo e(asset('js/chart.umd.min.js')); ?>"></script>
    <?php endif; ?>

    <script>
        window.cdCharts = function (initial) {
            let charts = {}; // kept outside Alpine reactivity on purpose

            const make = (id, config) => {
                const el = document.getElementById(id);
                if (!el) return;
                if (charts[id]) charts[id].destroy();
                charts[id] = new Chart(el, config);
            };

            const render = (d) => {
                make('cdDaily', {
                    type: 'bar',
                    data: {
                        labels: d.daily.labels,
                        datasets: [
                            { type: 'bar', label: 'Output (pcs)', data: d.daily.output, backgroundColor: '#3b82f6', yAxisID: 'y', order: 2 },
                            { type: 'line', label: 'NG rate (%)', data: d.daily.rate, borderColor: '#dc2626', backgroundColor: '#dc2626', yAxisID: 'y1', tension: .3, order: 1 },
                        ],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            y:  { beginAtZero: true, title: { display: true, text: 'pcs' } },
                            y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'NG %' } },
                        },
                    },
                });

                make('cdItems', {
                    type: 'bar',
                    data: { labels: d.items.labels, datasets: [{ label: 'Output (pcs)', data: d.items.output, backgroundColor: '#10b981' }] },
                    options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } },
                });

                make('cdProcess', {
                    type: 'bar',
                    data: {
                        labels: d.process.labels,
                        datasets: [
                            { type: 'bar', label: 'NG (pcs)', data: d.process.ng, backgroundColor: '#f87171', yAxisID: 'y' },
                            { type: 'line', label: 'NG rate (%)', data: d.process.rate, borderColor: '#7f1d1d', backgroundColor: '#7f1d1d', yAxisID: 'y1' },
                        ],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: {
                            y:  { beginAtZero: true },
                            y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } },
                        },
                    },
                });

                make('cdNgItems', {
                    type: 'doughnut',
                    data: {
                        labels: d.ngItems.labels,
                        datasets: [{ data: d.ngItems.values, backgroundColor: ['#ef4444', '#f97316', '#eab308', '#8b5cf6', '#06b6d4', '#64748b'] }],
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } },
                });

                make('cdCustomer', {
                    type: 'bar',
                    data: {
                        labels: d.customer.labels,
                        datasets: [
                            { type: 'bar', label: 'Output (pcs)', data: d.customer.output, backgroundColor: '#6366f1', yAxisID: 'y' },
                            { type: 'line', label: 'NG rate (%)', data: d.customer.rate, borderColor: '#dc2626', backgroundColor: '#dc2626', yAxisID: 'y1' },
                        ],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: {
                            y:  { beginAtZero: true },
                            y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } },
                        },
                    },
                });

            };

            return {
                init() {
                    const start = () => {
                        if (window.Chart) { render(initial); }
                        else { setTimeout(start, 100); }
                    };
                    start();
                },
                update(d) { render(d); },
            };
        };
    </script>

    
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

    
    <div class="flex justify-center gap-6">

        <?php if (isset($component)) { $__componentOriginal9b945b32438afb742355861768089b04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9b945b32438afb742355861768089b04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <div class="flex flex-col items-center justify-center text-center h-full py-1">
                <h2 class="text-sm font-medium text-gray-500">Total Running Product</h2>
                <p class="mt-1 text-xl font-bold text-gray-900">
                    <?php echo e(number_format($ttlProduct ?? 0, 0, '.', ',')); ?>

                </p>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9b945b32438afb742355861768089b04)): ?>
<?php $attributes = $__attributesOriginal9b945b32438afb742355861768089b04; ?>
<?php unset($__attributesOriginal9b945b32438afb742355861768089b04); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9b945b32438afb742355861768089b04)): ?>
<?php $component = $__componentOriginal9b945b32438afb742355861768089b04; ?>
<?php unset($__componentOriginal9b945b32438afb742355861768089b04); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal9b945b32438afb742355861768089b04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9b945b32438afb742355861768089b04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <div class="flex flex-col items-center justify-center text-center h-full py-1">
                <h2 class="text-sm font-medium text-gray-500">Total Running Machine</h2>
                <p class="mt-1 text-xl font-bold text-gray-900">
                    <?php echo e(number_format($ttlMachine ?? 0, 0, '.', ',')); ?>

                </p>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9b945b32438afb742355861768089b04)): ?>
<?php $attributes = $__attributesOriginal9b945b32438afb742355861768089b04; ?>
<?php unset($__attributesOriginal9b945b32438afb742355861768089b04); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9b945b32438afb742355861768089b04)): ?>
<?php $component = $__componentOriginal9b945b32438afb742355861768089b04; ?>
<?php unset($__componentOriginal9b945b32438afb742355861768089b04); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal9b945b32438afb742355861768089b04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9b945b32438afb742355861768089b04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <div class="flex flex-col items-center justify-center text-center h-full py-1">
                <h2 class="text-sm font-medium text-gray-500">Total Plan Qty</h2>
                <p class="mt-1 text-xl font-bold text-gray-900">
                    <?php echo e(number_format($ttlPlanQty ?? 0, 0, '.', ',')); ?>

                </p>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9b945b32438afb742355861768089b04)): ?>
<?php $attributes = $__attributesOriginal9b945b32438afb742355861768089b04; ?>
<?php unset($__attributesOriginal9b945b32438afb742355861768089b04); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9b945b32438afb742355861768089b04)): ?>
<?php $component = $__componentOriginal9b945b32438afb742355861768089b04; ?>
<?php unset($__componentOriginal9b945b32438afb742355861768089b04); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal9b945b32438afb742355861768089b04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9b945b32438afb742355861768089b04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <div class="flex flex-col items-center justify-center text-center h-full py-1">
                <h2 class="text-sm font-medium text-gray-500">Total Output Qty</h2>
                <p class="mt-1 text-xl font-bold text-gray-900">
                    <?php echo e(number_format($ttlOutQty ?? 0, 0, '.', ',')); ?>

                </p>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9b945b32438afb742355861768089b04)): ?>
<?php $attributes = $__attributesOriginal9b945b32438afb742355861768089b04; ?>
<?php unset($__attributesOriginal9b945b32438afb742355861768089b04); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9b945b32438afb742355861768089b04)): ?>
<?php $component = $__componentOriginal9b945b32438afb742355861768089b04; ?>
<?php unset($__componentOriginal9b945b32438afb742355861768089b04); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal9b945b32438afb742355861768089b04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9b945b32438afb742355861768089b04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <div class="flex flex-col items-center justify-center text-center h-full py-1">
                <h2 class="text-sm font-medium text-gray-500">Total Outstanding Qty</h2>
                <p class="mt-1 text-xl font-bold text-gray-900">
                    <?php echo e(number_format($ttlOSQty ?? 0, 0, '.', ',')); ?>

                </p>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9b945b32438afb742355861768089b04)): ?>
<?php $attributes = $__attributesOriginal9b945b32438afb742355861768089b04; ?>
<?php unset($__attributesOriginal9b945b32438afb742355861768089b04); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9b945b32438afb742355861768089b04)): ?>
<?php $component = $__componentOriginal9b945b32438afb742355861768089b04; ?>
<?php unset($__componentOriginal9b945b32438afb742355861768089b04); ?>
<?php endif; ?>

    </div>

    
    <div class="cd-wrap" x-data="cdCharts(<?php echo \Illuminate\Support\Js::from($charts)->toHtml() ?>)" x-init="init()" @dashboard-updated.window="update($event.detail.charts)">

        <h2 class="text-center text-xl font-bold mt-6">Production Output &amp; NG Analysis</h2>

        
        <div class="cd-card">
            <div class="cd-filters">
                <div><label>From</label><input type="date" wire:model.live="dateFrom"></div>
                <div><label>To</label><input type="date" wire:model.live="dateTo"></div>
                <div>
                    <label>Product</label>
                    <select wire:model.live="itemFilter">
                        <option value="">All products</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $itemOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($opt['code']); ?>"><?php echo e($opt['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>

                <div>
                    <label>Customer</label>
                    <select wire:model.live="customerFilter">
                        <option value="">All customers</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $customerOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($c); ?>"><?php echo e($c); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>                

                <button type="button" class="cd-btn" wire:click="setRange(1)">Today</button>
                <button type="button" class="cd-btn" wire:click="setRange(7)">7 days</button>
                <button type="button" class="cd-btn" wire:click="setRange(30)">30 days</button>
                <button type="button" class="cd-btn" wire:click="setRange(90)">90 days</button>
            </div>
        </div>

        
        <div class="cd-grid cd-g5">
            <div class="cd-card">
                <div class="cd-label">Factory Output</div>
                <div class="cd-value"><?php echo e(number_format($kpi['output'])); ?></div>
                <div class="cd-sub">pcs, at last process of each product</div>
            </div>
            <div class="cd-card">
                <div class="cd-label">Avg Daily Output</div>
                <div class="cd-value"><?php echo e(number_format($kpi['avg_daily'])); ?></div>
                <div class="cd-sub">pcs per production day</div>
            </div>
            <div class="cd-card">
                <div class="cd-label">Total NG</div>
                <div class="cd-value cd-bad"><?php echo e(number_format($kpi['ng'])); ?></div>
                <div class="cd-sub">pcs, all processes</div>
            </div>
            <div class="cd-card">
                <div class="cd-label">NG Rate</div>
                <div class="cd-value <?php echo e($kpi['ng_rate'] > $kpi['alert_pct'] ? 'cd-bad' : 'cd-ok'); ?>"><?php echo e(number_format($kpi['ng_rate'], 2)); ?>%</div>
                <div class="cd-sub">NG ÷ input, target ≤ <?php echo e($kpi['alert_pct']); ?>%</div>
            </div>
            <div class="cd-card">
                <div class="cd-label">Products Produced</div>
                <div class="cd-value"><?php echo e($kpi['items']); ?></div>
                <div class="cd-sub">in selected period</div>
            </div>
        </div>

        
        <div class="cd-card">
            <div class="cd-title">Daily Output &amp; NG Rate</div>
            <div class="cd-chart" wire:ignore><canvas id="cdDaily"></canvas></div>
        </div>

        <div class="cd-grid cd-g2">
            <div class="cd-card">
                <div class="cd-title">Output by Product</div>
                <div class="cd-chart" wire:ignore><canvas id="cdItems"></canvas></div>
            </div>
            <div class="cd-card">
                <div class="cd-title">NG Share by Product</div>
                <div class="cd-chart" wire:ignore><canvas id="cdNgItems"></canvas></div>
            </div>
        </div>

        <div class="cd-card">
            <div class="cd-title">NG by Process</div>
            <div class="cd-chart" wire:ignore><canvas id="cdProcess"></canvas></div>
        </div>

        
        <div class="cd-grid cd-g2">
            <div class="cd-card">
                <div class="cd-title">Output &amp; NG Rate by Customer</div>
                <div class="cd-chart" wire:ignore><canvas id="cdCustomer"></canvas></div>
            </div>

            <div class="cd-card">
                <div class="cd-title">Customer Summary</div>
                <table class="cd-table">
                    <thead><tr><th>Customer</th><th class="num">Products</th><th class="num">Output</th><th class="num">NG</th><th class="num">NG %</th></tr></thead>
                    <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $customerRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="<?php echo e($r['ng_rate'] > $kpi['alert_pct'] ? 'cd-row-alert' : ''); ?>">
                            <td><?php echo e($r['name']); ?></td>
                            <td class="num"><?php echo e($r['products']); ?></td>
                            <td class="num"><?php echo e(number_format($r['output'])); ?></td>
                            <td class="num"><?php echo e(number_format($r['ng'])); ?></td>
                            <td class="num"><?php echo e(number_format($r['ng_rate'], 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5">No data in this period.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="cd-grid cd-g2">
            <div class="cd-card">
                <div class="cd-title">Product Summary</div>
                <table class="cd-table">
                    <thead><tr><th>Product</th><th>Last process</th><th class="num">Output</th><th class="num">NG</th><th class="num">NG %</th></tr></thead>
                    <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $itemRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="<?php echo e($r['ng_rate'] > $kpi['alert_pct'] ? 'cd-row-alert' : ''); ?>">
                            <td><?php echo e($r['name']); ?></td>
                            <td><?php echo e($r['final']); ?></td>
                            <td class="num"><?php echo e(number_format($r['output'])); ?></td>
                            <td class="num"><?php echo e(number_format($r['ng'])); ?></td>
                            <td class="num"><?php echo e(number_format($r['ng_rate'], 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5">No data in this period.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="cd-card">
                <div class="cd-title">Process Summary</div>
                <table class="cd-table">
                    <thead><tr><th>Process</th><th class="num">Input</th><th class="num">Output</th><th class="num">NG</th><th class="num">NG %</th></tr></thead>
                    <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $processRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="<?php echo e($r['ng_rate'] > $kpi['alert_pct'] ? 'cd-row-alert' : ''); ?>">
                            <td><?php echo e($r['code']); ?> <?php echo e($r['name']); ?></td>
                            <td class="num"><?php echo e(number_format($r['in'])); ?></td>
                            <td class="num"><?php echo e(number_format($r['out'])); ?></td>
                            <td class="num"><?php echo e(number_format($r['ng'])); ?></td>
                            <td class="num"><?php echo e(number_format($r['ng_rate'], 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5">No data in this period.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
<?php /**PATH D:\website\mais-wellbest\resources\views/filament/pages/company-dashboard.blade.php ENDPATH**/ ?>