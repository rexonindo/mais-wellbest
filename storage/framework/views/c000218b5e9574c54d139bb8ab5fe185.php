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
        /* Main dashboard wrapper */
        .cd-wrap {
            padding: 0 8px 5px;
            margin-top: -10px;
        }

        .cd-dashboard-heading {
            margin-top: 0;
            padding-top: 0;
            margin-bottom: 5px;
        }

        /* Compact dashboard title */
        .cd-main-title {
            position: relative;
            text-align: center;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;

            background: linear-gradient(
                90deg,
                #1e40af 0%,
                #2563eb 45%,
                #0891b2 100%
            );

            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;

            padding: 0;
            margin: 0 0 5px;

            filter: drop-shadow(0 1px 1px rgba(37, 99, 235, 0.15));
        }

        .cd-main-title::after {
            content: "";
            display: block;
            width: 110px;
            height: 3px;
            margin: 4px auto 0;
            border-radius: 10px;
            background: linear-gradient(90deg, #2563eb, #06b6d4);
        }

        .cd-main-subtitle {
            text-align: center;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            color: #64748b;
            margin: 0 0 7px;
        }

        /* Section frame */
        .cd-section {
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 10px;
            background: #f9fafb;
        }
        /* Section heading */
        .cd-section-header {
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        .cd-section-title {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .04em;
            color: #2563eb;
        }
        .cd-section-caption {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }

        .cd-section-single,
        .cd-section-double,
        .cd-section-wo {
            padding: 7px 10px;
            margin-bottom: 6px;
        }

        .cd-section-header {
            margin-bottom: 5px;
            padding-bottom: 4px;
        }

        /* KPI grid */
        .cd-grid.cd-g5 {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 8px;
        }
        /* KPI card */
        .cd-card {
            padding: 8px 10px;
            min-height: 65px;
            border-radius: 8px;
        }
        /* KPI label */
        .cd-label {
            font-size: 11px;
            margin-bottom: 4px;
        }
        /* KPI value */
        .cd-value {
            font-size: 20px;
            font-weight: 700;
        }
        /* Smaller screen */
        @media (max-width: 768px) {
            .cd-grid.cd-g5 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        } 

        /* =========================================
        MAIN DASHBOARD TITLE
        ========================================= */

        .cd-main-title {
            position: relative;
            text-align: center;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;

            background: linear-gradient(
                90deg,
                #1e40af 0%,
                #2563eb 45%,
                #0891b2 100%
            );

            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;

            padding: 8px 0 12px;
            margin: 0 0 14px;

            filter: drop-shadow(0 2px 2px rgba(37, 99, 235, 0.15));
        }

        /* Decorative underline */
        .cd-main-title::after {
            content: "";
            display: block;
            width: 150px;
            height: 4px;
            margin: 9px auto 0;
            border-radius: 10px;

            background: linear-gradient(
                90deg,
                #2563eb,
                #06b6d4
            );

            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
        }

        /* Small subtitle */
        .cd-main-subtitle {
            text-align: center;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2px;
            color: #64748b;
            margin-top: -8px;
            margin-bottom: 12px;
        }

        /* Dark mode */
        .dark .cd-main-subtitle {
            color: #94a3b8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .cd-main-title {
                font-size: 18px;
                letter-spacing: .5px;
            }

            .cd-main-title::after {
                width: 100px;
            }
        }        

        /* =========================================
        SECTION COLOR THEMES
        ========================================= */

        /* SINGLE SIDE - BLUE */
        .cd-section-single {
            --section-color: #2563eb;
            --section-bg: #eff6ff;
            --card-bg: #f8fbff;
            --card-border: #bfdbfe;
        }

        /* DOUBLE SIDE - GREEN */
        .cd-section-double {
            --section-color: #059669;
            --section-bg: #ecfdf5;
            --card-bg: #f6fef9;
            --card-border: #a7f3d0;
        }

        /* WORK ORDER - AMBER */
        .cd-section-wo {
            --section-color: #d97706;
            --section-bg: #fffbeb;
            --card-bg: #fffdf5;
            --card-border: #fde68a;
        }

        /* Section frame */
        .cd-section-single,
        .cd-section-double,
        .cd-section-wo {
            border: 1px solid var(--card-border);
            border-left: 4px solid var(--section-color);
            background: var(--section-bg);
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 10px;
        }

        /* Section title */
        .cd-section-single .cd-section-title,
        .cd-section-double .cd-section-title,
        .cd-section-wo .cd-section-title {
            color: var(--section-color);
        }

        /* KPI cards */
        .cd-section-single .cd-card,
        .cd-section-double .cd-card,
        .cd-section-wo .cd-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 8px;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        /* Hover effect */
        .cd-section-single .cd-card:hover,
        .cd-section-double .cd-card:hover,
        .cd-section-wo .cd-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,0,0,.08);
        }

        /* KPI values */
        .cd-section-single .cd-value {
            color: #1d4ed8;
        }

        .cd-section-double .cd-value {
            color: #047857;
        }

        .cd-section-wo .cd-value {
            color: #b45309;
        }

        /* Dark mode */
        .dark .cd-section-single {
            --section-bg: #172554;
            --card-bg: #1e293b;
            --card-border: #1e40af;
        }

        .dark .cd-section-double {
            --section-bg: #022c22;
            --card-bg: #12352c;
            --card-border: #047857;
        }

        .dark .cd-section-wo {
            --section-bg: #422006;
            --card-bg: #3b2a12;
            --card-border: #92400e;
        }

        .dark .cd-section-single .cd-label,
        .dark .cd-section-double .cd-label,
        .dark .cd-section-wo .cd-label {
            color: #e5e7eb;
        }

        .cd-btn-row { flex-basis: 100%; display: flex; flex-wrap: wrap; gap: .75rem; }
        .cd-grid { display: grid; gap: 1rem; }
        .cd-g2 { grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); }
        .cd-g5 { grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); }
        .cd-g6 { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); }
        .cd-g7 { grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); }  
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

    <?php if (! $__env->hasRenderedOnce('7ede002c-2a72-4080-bed8-97b42c875a50')): $__env->markAsRenderedOnce('7ede002c-2a72-4080-bed8-97b42c875a50'); ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/flatpickr.min.css')); ?>">
        <script src="<?php echo e(asset('js/flatpickr.min.js')); ?>"></script>
        <script src="<?php echo e(asset('js/chart.umd.min.js')); ?>"></script>        
    <?php endif; ?>

    <script>

        /* =========================================================
        Dashboard rotator: switch to the next page on a fixed timer.
        ========================================================= */
        document.addEventListener('DOMContentLoaded', function () {

            // Clear stale values left by the old timer script
            try {
                localStorage.removeItem('dashboardLastSwitch');
                localStorage.removeItem('dashboardLastRefresh');
            } catch (e) {}

            const dashboardPages = [
                '/wellbest/company-dashboard-a',
                '/wellbest/company-dashboard-b',
                '/wellbest/company-dashboard-c',
                '/wellbest/company-dashboard-d',
                '/wellbest/company-dashboard-e',
                '/wellbest/company-dashboard-f'
            ];

            const SWITCH_MS = 30 * 1000;  // time on each page before switching

            const currentPath  = window.location.pathname.replace(/\/+$/, '');
            const currentIndex = dashboardPages.indexOf(currentPath);

            if (currentIndex === -1) return;

            setTimeout(function () {
                window.location.href = dashboardPages[(currentIndex + 1) % dashboardPages.length];
            }, SWITCH_MS);
        });
        
        /*
        document.addEventListener('livewire:init', () => {
            setInterval(() => {
                // Refresh current Filament / Livewire page
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').$refresh();
            }, 5000); // 5 seconds
        });
        */
       
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                if (window.Alpine) {
                    Alpine.store('sidebar').isOpen = false;
                }
            }, 200);
        });
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
                            { type: 'bar',  label: 'Output (pcs)', data: d.daily.output, backgroundColor: '#3b82f6', yAxisID: 'y', order: 2 },
                            { type: 'line', label: 'NG rate (%)',  data: d.daily.rate, borderColor: '#dc2626', backgroundColor: '#dc2626', yAxisID: 'y1', tension: .3, order: 1 },
                            { type: 'line', label: 'RWK rate (%)', data: d.daily.rwk_rate, borderColor: '#f59e0b', backgroundColor: '#f59e0b', yAxisID: 'y1', tension: .3, order: 1 },
                        ],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            y:  { beginAtZero: true, title: { display: true, text: 'pcs' } },
                            y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'NG / RWK %' } },                        
                        },
                    },
                });

                make('cdProcessOutput', {
                    type: 'bar',
                    data: {
                        labels: d.processOutput.labels,
                        datasets: [
                            { type: 'bar',  label: 'In Qty (pcs)',  data: d.processOutput.in,  backgroundColor: '#60a5fa', yAxisID: 'y',  order: 3 },
                            { type: 'bar',  label: 'Out Qty (pcs)', data: d.processOutput.out, backgroundColor: '#34d399', yAxisID: 'y',  order: 3 },
                            { type: 'line', label: 'NG ratio (%)',  data: d.processOutput.ng_rate,  borderColor: '#dc2626', backgroundColor: '#dc2626', yAxisID: 'y1', tension: .3, order: 1 },
                            { type: 'line', label: 'RWK ratio (%)', data: d.processOutput.rwk_rate, borderColor: '#f59e0b', backgroundColor: '#f59e0b', yAxisID: 'y1', tension: .3, order: 1 },
                        ],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            y:  { beginAtZero: true, position: 'left', title: { display: true, text: 'pcs' } },
                            y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'Ratio %' } },
                        },
                    },
                });

                make('cdItems', {
                    type: 'bar',
                    data: { labels: d.items.labels, datasets: [{ label: 'Output (pcs)', data: d.items.output, backgroundColor: '#10b981' }] },
                    options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } },
                });

                // ---- NG Ratio By Category (per NG name, bars coloured by category) ----
                const catPalette = ['#3b82f6', '#f97316', '#10b981', '#8b5cf6', '#eab308', '#06b6d4', '#ef4444', '#64748b'];
                const catColor = {};
                (d.ngCategory.cats || []).forEach(c => {
                    if (!(c in catColor)) catColor[c] = catPalette[Object.keys(catColor).length % catPalette.length];
                });

                make('cdNgCategory', {
                    type: 'bar',
                    data: {
                        labels: d.ngCategory.labels,
                        datasets: [
                            { type: 'bar',  label: 'NG ratio (%)', data: d.ngCategory.ratio,
                              backgroundColor: d.ngCategory.cats.map(c => catColor[c]), order: 2 },
                            { type: 'line', label: 'Standard (0.90%)', data: d.ngCategory.std,
                              borderColor: '#dc2626', backgroundColor: '#dc2626',
                              pointStyle: 'rect', pointRadius: 3, tension: 0, order: 1 },
                        ],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: { callbacks: {
                                title: (items) => `${d.ngCategory.cats[items[0].dataIndex]} - ${items[0].label}`,
                                label: (c) => `${c.dataset.label}: ${Number(c.parsed.y).toFixed(2)}%`,
                                afterLabel: (c) => c.datasetIndex === 0
                                    ? `NG Qty: ${Number(d.ngCategory.qty[c.dataIndex]).toLocaleString()}` : '',
                            } },
                        },
                        scales: {
                            x: { ticks: { autoSkip: false, maxRotation: 60, minRotation: 30 } },
                            y: { beginAtZero: true, title: { display: true, text: 'NG ratio %' },
                                 ticks: { callback: v => v + '%' } },
                        },
                    },
                });

                // ---- NG Ratio By Category Sum ----
                make('cdNgCategorySum', {
                    type: 'bar',
                    data: {
                        labels: d.ngCategorySum.labels,
                        datasets: [
                            { type: 'bar',  label: 'NG ratio (%)', data: d.ngCategorySum.ratio, backgroundColor: '#3b82f6', order: 2 },
                            { type: 'line', label: 'Standard (0.90%)', data: d.ngCategorySum.std,
                              borderColor: '#dc2626', backgroundColor: '#dc2626',
                              pointStyle: 'rect', pointRadius: 3, tension: 0, order: 1 },
                        ],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: { callbacks: {
                                label: (c) => `${c.dataset.label}: ${Number(c.parsed.y).toFixed(2)}%`,
                                afterLabel: (c) => c.datasetIndex === 0
                                    ? `NG Qty: ${Number(d.ngCategorySum.qty[c.dataIndex]).toLocaleString()}` : '',
                            } },
                        },
                        scales: {
                            y: { beginAtZero: true, title: { display: true, text: 'NG ratio %' },
                                 ticks: { callback: v => v + '%' } },
                        },
                    },
                });                

                make('cdPcbGroup', {
                    type: 'bar',
                    data: {
                        labels: d.pcbGroup.labels,
                        datasets: [
                            { type: 'bar',  label: 'NG (pcs)',     data: d.pcbGroup.ng,       backgroundColor: '#f87171', yAxisID: 'y',  order: 2 },
                            { type: 'line', label: 'NG ratio (%)',  data: d.pcbGroup.rate,     borderColor: '#7f1d1d', backgroundColor: '#7f1d1d', yAxisID: 'y1', tension: .3, order: 1 },
                            { type: 'line', label: 'RWK ratio (%)', data: d.pcbGroup.rwk_rate, borderColor: '#f59e0b', backgroundColor: '#f59e0b', yAxisID: 'y1', tension: .3, order: 1 },
                        ],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            y:  { beginAtZero: true, position: 'left', title: { display: true, text: 'pcs' } },
                            y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'NG / RWK %' } },
                        },
                    },
                });                

                make('cdProcess', {
                    type: 'bar',
                    data: {
                        labels: d.process.labels,
                        datasets: [
                            { type: 'bar',  label: 'NG (pcs)',      data: d.process.ng,       backgroundColor: '#f87171', yAxisID: 'y',  order: 2 },
                            { type: 'line', label: 'NG rate (%)',   data: d.process.rate,     borderColor: '#7f1d1d', backgroundColor: '#7f1d1d', yAxisID: 'y1', order: 1 },
                            { type: 'line', label: 'RWK rate (%)',  data: d.process.rwk_rate, borderColor: '#f59e0b', backgroundColor: '#f59e0b', yAxisID: 'y1', order: 1 },
                        ],
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: {
                            y:  { beginAtZero: true },
                            y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'NG / RWK %' } },
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
        window.cdDatePicker = function (initial) {
            return {
                value: initial,
                fp: null,
                init() {
                    const start = () => {
                        if (!window.flatpickr) { return setTimeout(start, 100); }
                        this.fp = flatpickr(this.$refs.input, {
                            dateFormat: 'Y-m-d',   // value sent to Livewire
                            altInput: true,
                            altFormat: 'd-M-Y',    // what the user sees
                            allowInput: false,
                            defaultDate: this.value || null,
                            onChange: (dates, str) => { this.value = str; },
                        });
                        // keeps the picker in sync when setRange() changes the value
                        this.$watch('value', v => this.fp.setDate(v || null, false));
                    };
                    start();
                },
            };
        };        
    </script>

    
    <div class="cd-wrap" x-data="cdCharts(<?php echo \Illuminate\Support\Js::from($charts)->toHtml() ?>)" x-init="init()" @dashboard-updated.window="update($event.detail.charts)">
        <div class="cd-dashboard-heading">
            <h2 class="cd-main-title">
                PRODUCTION OUTPUT BY PROCESS
            </h2>
            <div class="cd-main-subtitle">
                CURRENT MONTH PRODUCTION MONITORING
            </div>
        </div>

        <div class="cd-card">
            <div class="cd-title">Output By Process</div>
            <div class="cd-chart" wire:ignore><canvas id="cdProcessOutput"></canvas></div>
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
<?php /**PATH D:\website\mais-wellbest\resources\views/filament/pages/company-dashboard-c.blade.php ENDPATH**/ ?>