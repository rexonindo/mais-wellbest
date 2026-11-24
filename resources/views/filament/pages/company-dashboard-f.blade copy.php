<x-filament::page>

    <style>
        /* ===== Layout ===== */
        .cd-wrap { padding: 0 8px 5px; margin-top: -10px; }
        .cd-dashboard-heading { margin: 0 0 5px; padding-top: 0; }

        /* ===== Main title ===== */
        .cd-main-title {
            position: relative;
            text-align: center;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            background: linear-gradient(90deg, #1e40af 0%, #2563eb 45%, #0891b2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            padding: 8px 0 12px;
            margin: 0 0 14px;
            filter: drop-shadow(0 2px 2px rgba(37, 99, 235, .15));
        }
        .cd-main-title::after {
            content: "";
            display: block;
            width: 150px;
            height: 4px;
            margin: 9px auto 0;
            border-radius: 10px;
            background: linear-gradient(90deg, #2563eb, #06b6d4);
            box-shadow: 0 2px 8px rgba(37, 99, 235, .25);
        }
        .cd-main-subtitle {
            text-align: center;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2px;
            color: #64748b;
            margin: -8px 0 12px;
        }
        .dark .cd-main-subtitle { color: #94a3b8; }

        /* ===== Section (shared by all themes) ===== */
        .cd-section {
            background: var(--section-bg, #f9fafb);
            border: 1px solid var(--card-border, #d1d5db);
            border-left: 4px solid var(--section-color, #2563eb);
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 10px;
        }
        .cd-section-header {
            margin-bottom: 5px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e5e7eb;
        }
        .cd-section-title {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .04em;
            color: var(--section-color);
        }
        .cd-section-caption { font-size: 11px; color: #6b7280; margin-top: 2px; }

        .cd-section .cd-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 8px;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .cd-section .cd-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, .08);
        }
        .cd-section .cd-value { color: var(--value-color); }
        .dark .cd-section .cd-label { color: #e5e7eb; }

        /* ===== Section themes (variables only) ===== */
        .cd-section-single {            /* BLUE */
            --section-color: #2563eb; --section-bg: #eff6ff;
            --card-bg: #f8fbff;       --card-border: #bfdbfe;
            --value-color: #1d4ed8;
        }
        .cd-section-double {            /* GREEN */
            --section-color: #059669; --section-bg: #ecfdf5;
            --card-bg: #f6fef9;       --card-border: #a7f3d0;
            --value-color: #047857;
        }
        .cd-section-other {             /* VIOLET */
            --section-color: #7c3aed; --section-bg: #f5f3ff;
            --card-bg: #faf9ff;       --card-border: #ddd6fe;
            --value-color: #6d28d9;
        }
        .cd-section-wo {                /* AMBER */
            --section-color: #d97706; --section-bg: #fffbeb;
            --card-bg: #fffdf5;       --card-border: #fde68a;
            --value-color: #b45309;
        }

        /* Dark mode */
        .dark .cd-section-single { --section-bg: #172554; --card-bg: #1e293b; --card-border: #1e40af; }
        .dark .cd-section-double { --section-bg: #022c22; --card-bg: #12352c; --card-border: #047857; }
        .dark .cd-section-other  { --section-bg: #2e1065; --card-bg: #3b1f6e; --card-border: #6d28d9; }
        .dark .cd-section-wo     { --section-bg: #422006; --card-bg: #3b2a12; --card-border: #92400e; }

        /* ===== Grid ===== */
        .cd-grid { display: grid; gap: 1rem; }
        .cd-g2 { grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); }
        .cd-g5 { grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); }
        .cd-grid.cd-g5 { grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 8px; }

        /* ===== Cards ===== */
        .cd-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: .75rem;
            padding: 1rem 1.25rem;
            min-height: 65px;
        }
        .dark .cd-card { background: #18181b; border-color: #27272a; }
        .cd-label { font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; color: #6b7280; margin-bottom: 4px; }
        .cd-value { font-size: 1.75rem; font-weight: 700; margin-top: .25rem; }
        .cd-sub { font-size: .75rem; color: #6b7280; margin-top: .15rem; }
        .cd-title { font-weight: 600; margin-bottom: .75rem; }
        .cd-chart { position: relative; height: 300px; }
        .cd-bad { color: #dc2626; }
        .cd-ok { color: #16a34a; }

        /* ===== Filters ===== */
        .cd-filters { display: flex; flex-wrap: wrap; gap: .75rem; align-items: end; }
        .cd-filters label { display: block; font-size: .75rem; color: #6b7280; margin-bottom: .2rem; }
        .cd-filters input, .cd-filters select {
            border: 1px solid #d1d5db; border-radius: .5rem;
            padding: .35rem .6rem; background: transparent; font-size: .875rem;
        }
        .cd-btn-row { flex-basis: 100%; display: flex; flex-wrap: wrap; gap: .75rem; }
        .cd-btn { border: 1px solid #d1d5db; border-radius: .5rem; padding: .35rem .8rem; font-size: .8rem; cursor: pointer; background: transparent; }
        .cd-btn:hover { background: rgba(59, 130, 246, .1); }

        /* ===== Tables ===== */
        .cd-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
        .cd-table th { text-align: left; color: #6b7280; font-weight: 600; padding: .4rem .5rem; border-bottom: 1px solid #e5e7eb; }
        .cd-table td { padding: .4rem .5rem; border-bottom: 1px solid #f3f4f6; }
        .dark .cd-table td { border-color: #27272a; }
        .cd-table .num { text-align: right; font-variant-numeric: tabular-nums; }
        .cd-row-alert { background: rgba(220, 38, 38, .08); }

        /* ===== Small screens ===== */
        @media (max-width: 768px) {
            .cd-main-title { font-size: 18px; letter-spacing: .5px; }
            .cd-main-title::after { width: 100px; }
            .cd-grid.cd-g5 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        /* =========================================================
           SCROLLING PAGE OVERRIDES
           This page is taller than the screen (auto-scroll rotator),
           so every block must take its natural height. !important
           beats any fixed-height rule coming from theme.css or a
           render hook.
           ========================================================= */
        .cd-wrap {
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
            display: block !important;
        }
        .cd-wrap > .cd-grid.cd-g2 {
            display: grid !important;
            grid-template-rows: none !important;
            align-items: start !important;
        }
        .cd-wrap > .cd-grid.cd-g2 > .cd-card {
            display: block !important;
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
        }
        .cd-wrap .cd-chart {
            height: 300px !important;
            flex: none !important;
        }

        /* space between the first block and the second block's title */
        .cd-wrap + .cd-wrap {
            margin-top: 40px !important;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;   /* optional divider, delete if not wanted */
        }
        .dark .cd-wrap + .cd-wrap { border-top-color: #27272a; }
    </style>

    @once
        <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
        <script src="{{ asset('js/flatpickr.min.js') }}"></script>
        <script src="{{ asset('js/chart.umd.min.js') }}"></script>
    @endonce

    <script>

        /* =========================================================
        Dashboard rotator: auto-scroll down, then switch to next page.
        ========================================================= */
        document.addEventListener('DOMContentLoaded', function () {

            const dashboardPages = [
                '/wellbest/company-dashboard-a',
                '/wellbest/company-dashboard-b',
                '/wellbest/company-dashboard-c',
                '/wellbest/company-dashboard-d',
                '/wellbest/company-dashboard-e',
                '/wellbest/company-dashboard-f'
            ];

            const STEP_PX      = 40;      // pixels per tick
            const TICK_MS      = 1000;    // scroll every 1 second
            const START_DELAY  = 1500;    // wait for charts to render
            const END_PAUSE_MS = 3000;    // pause at the bottom before switching
            const FIT_DWELL_MS = 30000;   // time on a page that fits on screen
            const MAX_PAGE_MS  = 120000;  // safety: always switch after this long

            const currentPath  = window.location.pathname.replace(/\/+$/, '');
            const currentIndex = dashboardPages.indexOf(currentPath);

            const goNext = () => {
                if (currentIndex === -1) return;
                window.location.href = dashboardPages[(currentIndex + 1) % dashboardPages.length];
            };

            // Safety net: never get stuck on one page
            setTimeout(goNext, MAX_PAGE_MS);

            const getScroller = () => {
                const root = document.scrollingElement || document.documentElement;
                if (root.scrollHeight > root.clientHeight + 1) return root;

                const candidates = document.querySelectorAll(
                    '.fi-layout, .fi-main, .fi-main-ctn, .fi-page, main'
                );
                for (const el of candidates) {
                    const oy = getComputedStyle(el).overflowY;
                    if ((oy === 'auto' || oy === 'scroll') && el.scrollHeight > el.clientHeight + 1) {
                        return el;
                    }
                }
                return root;
            };

            const tick = () => {
                const el = getScroller();
                const maxScroll = el.scrollHeight - el.clientHeight;

                // Page fits on screen: just wait, then switch
                if (maxScroll <= 1) {
                    return setTimeout(goNext, FIT_DWELL_MS);
                }

                // Reached the bottom: pause, then go to the next dashboard
                if (el.scrollTop >= maxScroll - 2) {
                    return setTimeout(goNext, END_PAUSE_MS);
                }

                el.scrollBy({ top: STEP_PX, behavior: 'smooth' });
                setTimeout(tick, TICK_MS);
            };

            setTimeout(tick, START_DELAY);
        });

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

    {{-- ===== Block 1: Production Output By Customer ===== --}}
    <div class="cd-wrap" x-data="cdCharts(@js($charts))" x-init="init()" @dashboard-updated.window="update($event.detail.charts)">
        <div class="cd-dashboard-heading">
            <h2 class="cd-main-title">
                PRODUCTION OUTPUT BY CUSTOMER
            </h2>
            <div class="cd-main-subtitle">
                CURRENT MONTH PRODUCTION MONITORING
            </div>
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
                    @forelse ($customerRows as $r)
                        <tr class="{{ $r['ng_rate'] > $kpi['alert_pct'] ? 'cd-row-alert' : '' }}">
                            <td>{{ $r['name'] }}</td>
                            <td class="num">{{ $r['products'] }}</td>
                            <td class="num">{{ number_format($r['output']) }}</td>
                            <td class="num">{{ number_format($r['ng']) }}</td>
                            <td class="num">{{ number_format($r['ng_rate'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No data in this period.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== Block 2: Part Number And Process Summary (tables only, no x-data) ===== --}}
    <div class="cd-wrap">
        <div class="cd-dashboard-heading">
            <h2 class="cd-main-title">
                PART NUMBER AND PROCESS SUMMARY
            </h2>
            <div class="cd-main-subtitle">
                CURRENT MONTH PRODUCTION MONITORING
            </div>
        </div>

        <div class="cd-grid cd-g2">
            <div class="cd-card">
                <div class="cd-title">Part Number Summary</div>
                <table class="cd-table">
                    <thead><tr><th>Part Number</th><th>Last process</th><th class="num">Output</th><th class="num">NG</th><th class="num">NG %</th></tr></thead>
                    <tbody>
                    @forelse ($itemRows as $r)
                        <tr class="{{ $r['ng_rate'] > $kpi['alert_pct'] ? 'cd-row-alert' : '' }}">
                            <td>{{ $r['name'] }}</td>
                            <td>{{ $r['final'] }}</td>
                            <td class="num">{{ number_format($r['output']) }}</td>
                            <td class="num">{{ number_format($r['ng']) }}</td>
                            <td class="num">{{ number_format($r['ng_rate'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No data in this period.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="cd-card">
                <div class="cd-title">Process Summary</div>
                <table class="cd-table">
                    <thead><tr><th>Process</th><th class="num">Input</th><th class="num">Output</th><th class="num">NG</th><th class="num">NG %</th></tr></thead>
                    <tbody>
                    @forelse ($processRows as $r)
                        <tr class="{{ $r['ng_rate'] > $kpi['alert_pct'] ? 'cd-row-alert' : '' }}">
                            <td>{{ $r['code'] }} {{ $r['name'] }}</td>
                            <td class="num">{{ number_format($r['in']) }}</td>
                            <td class="num">{{ number_format($r['out']) }}</td>
                            <td class="num">{{ number_format($r['ng']) }}</td>
                            <td class="num">{{ number_format($r['ng_rate'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No data in this period.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-filament::page>