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

    <?php if (! $__env->hasRenderedOnce('71792133-f124-4b21-9074-2b5ba2fa2f50')): $__env->markAsRenderedOnce('71792133-f124-4b21-9074-2b5ba2fa2f50'); ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/flatpickr.min.css')); ?>">
        <script src="<?php echo e(asset('js/flatpickr.min.js')); ?>"></script>
        <script src="<?php echo e(asset('js/chart.umd.min.js')); ?>"></script>        
    <?php endif; ?>

    <script>
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
                PRODUCTION OUTPUT AND WORK ORDER
            </h2>
            <div class="cd-main-subtitle">
                CURRENT MONTH PRODUCTION MONITORING
            </div>
        </div>


        
        <div class="cd-section cd-section-single">    
            <div class="cd-section-header">
                <div class="cd-section-title">
                    SINGLE SIDE PRODUCTION
                </div>
                <div class="cd-section-caption">
                    Production performance and work order summary for Single Side PCB
                </div>
            </div>
            <div class="cd-grid cd-g5">
                <div class="cd-card">
                    <div class="cd-label">Running Part No</div>
                    <div class="cd-value"><?php echo e(number_format($ttlProductSINGLE ?? 0, 0, '.', ',')); ?></div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Running Machine</div>
                    <div class="cd-value"><?php echo e(number_format($ttlMachineSINGLE ?? 0, 0, '.', ',')); ?></div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Input Qty</div>
                    <div class="cd-value"><?php echo e(number_format($ttlInQtySINGLE ?? 0, 0, '.', ',')); ?></div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Delivery Qty</div>
                    <div class="cd-value"><?php echo e(number_format($ttlOutQtySINGLE ?? 0, 0, '.', ',')); ?></div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">WIP Qty</div>
                    <div class="cd-value"><?php echo e(number_format($ttlWIPQtySINGLE ?? 0, 0, '.', ',')); ?></div>
                </div>
            </div>
        </div>

        
        <div class="cd-section cd-section-double">
            <div class="cd-section-header">
                <div class="cd-section-title">
                    DOUBLE SIDE PRODUCTION
                </div>
                <div class="cd-section-caption">
                    Production performance and work order summary for Double Side PCB
                </div>
            </div>
            <div class="cd-grid cd-g5">
                <div class="cd-card">
                    <div class="cd-label">Running Part No</div>
                    <div class="cd-value"><?php echo e(number_format($ttlProductDOUBLE ?? 0, 0, '.', ',')); ?></div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Running Machine</div>
                    <div class="cd-value"><?php echo e(number_format($ttlMachineDOUBLE ?? 0, 0, '.', ',')); ?></div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Input Qty</div>
                    <div class="cd-value"><?php echo e(number_format($ttlInQtyDOUBLE ?? 0, 0, '.', ',')); ?></div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Delivery Qty</div>
                    <div class="cd-value"><?php echo e(number_format($ttlOutQtyDOUBLE ?? 0, 0, '.', ',')); ?></div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">WIP Qty</div>
                    <div class="cd-value"><?php echo e(number_format($ttlWIPQtyDOUBLE ?? 0, 0, '.', ',')); ?></div>
                </div>
            </div>
        </div>        

        
        <div class="cd-section cd-section-wo">
            <div class="cd-section-header">
                <div class="cd-section-title">
                    WORK ORDER PROGRESS
                </div>
                <div class="cd-section-caption">
                    Work Order real-time tracking performance
                </div>
            </div>
            <div class="cd-grid cd-g5">
                <div class="cd-card">
                    <div class="cd-label">Plan WO Qty</div>
                    <div class="cd-value"><?php echo e(number_format($ttlPlanQtyWO ?? 0, 0, '.', ',')); ?></div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Input Qty</div>
                    <div class="cd-value"><?php echo e(number_format($ttlInQtyWO ?? 0, 0, '.', ',')); ?></div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Delivery Qty</div>
                    <div class="cd-value"><?php echo e(number_format($ttlOutQtyWO ?? 0, 0, '.', ',')); ?></div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Outstanding WO Qty</div>
                    <div class="cd-value"><?php echo e(number_format($ttlOSQtyWO ?? 0, 0, '.', ',')); ?></div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Not yet Input Qty</div>
                    <div class="cd-value"><?php echo e(number_format($ttlNotInQtyWO ?? 0, 0, '.', ',')); ?></div>
                </div>                
            </div>
        </div>        
    </div>

    
    <div class="cd-wrap" x-data="cdCharts(<?php echo \Illuminate\Support\Js::from($charts)->toHtml() ?>)" x-init="init()" @dashboard-updated.window="update($event.detail.charts)">
        <h2 class="text-center text-3xl font-bold mt-0 mb-8">Production Output, NG and Rework Analysis Dashboard</h2>        

        
        <div class="cd-card">
            <div class="cd-filters">
                <div wire:ignore x-data="cdDatePicker(<?php if ((object) ('dateFrom') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('dateFrom'->value()); ?>')<?php echo e('dateFrom'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('dateFrom'); ?>')<?php endif; ?>.live)" x-init="init()">
                    <label>From</label>
                    <input type="text" x-ref="input">
                </div>
                <div wire:ignore x-data="cdDatePicker(<?php if ((object) ('dateTo') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('dateTo'->value()); ?>')<?php echo e('dateTo'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('dateTo'); ?>')<?php endif; ?>.live)" x-init="init()">
                    <label>To</label>
                    <input type="text" x-ref="input">
                </div>
                <div>
                    <label>Part Number</label>
                    <select wire:model.live="itemFilter">
                        <option value="">All Part Number</option>
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

                <div class="cd-btn-row">
                    <button type="button" class="cd-btn" wire:click="setRange(1)">Today</button>
                    <button type="button" class="cd-btn" wire:click="setRange(7)">7 days</button>
                    <button type="button" class="cd-btn" wire:click="setRange(30)">30 days</button>
                    <button type="button" class="cd-btn" wire:click="setRange(90)">90 days</button>
                </div>                

            </div>
        </div>

        
        <div class="cd-grid cd-g5">
            <div class="cd-card">
                <div class="cd-label">Factory Output</div>
                <div class="cd-value"><?php echo e(number_format($kpi['output'])); ?></div>
                <div class="cd-sub">pcs, at last process of each Part Number</div>
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
                <div class="cd-label">Part Number Produced</div>
                <div class="cd-value"><?php echo e($kpi['items']); ?></div>
                <div class="cd-sub">in selected period</div>
            </div>
        </div>

        
        <div class="cd-card">
            <div class="cd-title">Daily Output, NG and Rework Rate</div>
            <div class="cd-chart" wire:ignore><canvas id="cdDaily"></canvas></div>
        </div>

        <div class="cd-card">
            <div class="cd-title">Output By Process</div>
            <div class="cd-chart" wire:ignore><canvas id="cdProcessOutput"></canvas></div>
        </div>        

        <div class="cd-grid cd-g2">
            <div class="cd-card">
                <div class="cd-title">Output by Part Number</div>
                <div class="cd-chart" wire:ignore><canvas id="cdItems"></canvas></div>
            </div>
            <div class="cd-card">
                <div class="cd-title">NG Share by Part Number</div>
                <div class="cd-chart" wire:ignore><canvas id="cdNgItems"></canvas></div>
            </div>
        </div>

        <div class="cd-card">
            <div class="cd-title">NG Ratio By Category</div>
            <div class="cd-chart" wire:ignore><canvas id="cdNgCategory"></canvas></div>
        </div>

        <div class="cd-card">
            <div class="cd-title">NG Ratio By Category Sum</div>
            <div class="cd-chart" wire:ignore><canvas id="cdNgCategorySum"></canvas></div>
        </div>

        <div class="cd-card">
            <div class="cd-title">NG and Rework by PCB Group</div>
            <div class="cd-chart" wire:ignore><canvas id="cdPcbGroup"></canvas></div>
        </div>        

        <div class="cd-card">
            <div class="cd-title">NG and Rework by Process</div>
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
                    <thead><tr><th>Customer</th><th class="num">Part Number</th><th class="num">Output</th><th class="num">NG</th><th class="num">NG %</th></tr></thead>
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
                <div class="cd-title">Part Number Summary</div>
                <table class="cd-table">
                    <thead><tr><th>Part Number</th><th>Last process</th><th class="num">Output</th><th class="num">NG</th><th class="num">NG %</th></tr></thead>
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