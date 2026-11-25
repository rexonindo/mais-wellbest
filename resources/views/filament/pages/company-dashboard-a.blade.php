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

        /* ===== Fit-to-one-page overrides ===== */
        .cd-wrap {
            height: calc(100vh - 5rem);      /* 5rem ≈ Filament topbar + page padding; tweak if needed */
            display: flex;
            flex-direction: column;
            padding: 0 8px;
            margin-top: -10px;
            overflow: hidden;
        }
        .cd-dashboard-heading { flex: 0 0 auto; margin: 0 0 4px; }

        .cd-main-title {
            font-size: clamp(16px, 2.6vh, 24px);
            padding: 2px 0 6px;
            margin: 0 0 4px;
        }
        .cd-main-title::after { height: 3px; margin: 5px auto 0; }
        .cd-main-subtitle {
            font-size: clamp(9px, 1.2vh, 11px);
            margin: -2px 0 4px;
        }

        /* 4 sections share the remaining height equally */
        .cd-section {
            flex: 1 1 0;
            min-height: 0;
            display: flex;
            flex-direction: column;
            padding: 6px 10px;
            margin-bottom: 6px;
        }
        .cd-section:last-child { margin-bottom: 0; }
        .cd-section-header { flex: 0 0 auto; margin-bottom: 4px; padding-bottom: 2px; }
        .cd-section-title   { font-size: clamp(11px, 1.7vh, 14px); }
        .cd-section-caption { font-size: clamp(9px, 1.2vh, 11px); margin-top: 0; }

        /* cards fill the section height */
        .cd-section .cd-grid.cd-g5 { flex: 1 1 0; min-height: 0; gap: 8px; }
        .cd-section .cd-card {
            min-height: 0;
            padding: .25rem .75rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .cd-section .cd-label { font-size: clamp(.6rem, 1.3vh, .75rem); margin-bottom: 2px; }
        .cd-section .cd-value { font-size: clamp(1.1rem, 3.4vh, 1.9rem); margin-top: 0; line-height: 1.1; }

        /* Short screens: drop the captions to save space */
        @media (max-height: 800px) {
            .cd-section-caption { display: none; }
        }

        /* Phones / narrow screens: allow normal scrolling instead */
        @media (max-width: 768px) {
            .cd-wrap { height: auto; overflow: visible; }
            .cd-section { flex: none; }
        }

    </style>

    @once
        <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
        <script src="{{ asset('js/flatpickr.min.js') }}"></script>
        <script src="{{ asset('js/chart.umd.min.js') }}"></script>        
    @endonce

    @php
        $isDashboardUser = auth()->check() && auth()->user()->hasRole('dashboard');
    @endphp    

    <script>

        /* =========================================================
        Dashboard rotator: only for users with the "dashboard" role.
        ========================================================= */
        document.addEventListener('DOMContentLoaded', function () {

            const ROTATE_ENABLED = @json($isDashboardUser);
            if (!ROTATE_ENABLED) return;   // other roles stay on the page

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

            const SWITCH_MS = 30 * 1000;

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
                @this.$refresh();
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
                // Describe here if any
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

    {{-- ===== Production Output and Work Order ===== --}}
    <div class="cd-wrap" x-data="cdCharts(@js($charts))" x-init="init()" @dashboard-updated.window="update($event.detail.charts)">
        <div class="cd-dashboard-heading">
            <h2 class="cd-main-title">
                PRODUCTION OUTPUT AND WORK ORDER
            </h2>
            <div class="cd-main-subtitle">
                CURRENT MONTH PRODUCTION MONITORING
            </div>
        </div>


        {{-- Stats Grid SINGLE --}}
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
                    <div class="cd-value">{{ number_format($ttlProductSINGLE ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Running Machine</div>
                    <div class="cd-value">{{ number_format($ttlMachineSINGLE ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Input Qty</div>
                    <div class="cd-value">{{ number_format($ttlInQtySINGLE ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Delivery Qty</div>
                    <div class="cd-value">{{ number_format($ttlOutQtySINGLE ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">WIP Qty</div>
                    <div class="cd-value">{{ number_format($ttlWIPQtySINGLE ?? 0, 0, '.', ',') }}</div>
                </div>
            </div>
        </div>

        {{-- Stats Grid DOUBLE --}}
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
                    <div class="cd-value">{{ number_format($ttlProductDOUBLE ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Running Machine</div>
                    <div class="cd-value">{{ number_format($ttlMachineDOUBLE ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Input Qty</div>
                    <div class="cd-value">{{ number_format($ttlInQtyDOUBLE ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Delivery Qty</div>
                    <div class="cd-value">{{ number_format($ttlOutQtyDOUBLE ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">WIP Qty</div>
                    <div class="cd-value">{{ number_format($ttlWIPQtyDOUBLE ?? 0, 0, '.', ',') }}</div>
                </div>
            </div>
        </div>        

        {{-- Stats Grid OTHER --}}
        <div class="cd-section cd-section-other">
            <div class="cd-section-header">
                <div class="cd-section-title">
                    OTHER TYPE PRODUCTION
                </div>
                <div class="cd-section-caption">
                    Production performance and work order summary for Other Type PCB
                </div>
            </div>
            <div class="cd-grid cd-g5">
                <div class="cd-card">
                    <div class="cd-label">Running Part No</div>
                    <div class="cd-value">{{ number_format($ttlProductOTHER ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Running Machine</div>
                    <div class="cd-value">{{ number_format($ttlMachineOTHER ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Input Qty</div>
                    <div class="cd-value">{{ number_format($ttlInQtyOTHER ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Delivery Qty</div>
                    <div class="cd-value">{{ number_format($ttlOutQtyOTHER ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">WIP Qty</div>
                    <div class="cd-value">{{ number_format($ttlWIPQtyOTHER ?? 0, 0, '.', ',') }}</div>
                </div>
            </div>
        </div>        


        {{-- Stats Grid WO --}}
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
                    <div class="cd-value">{{ number_format($ttlPlanQtyWO ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Input Qty</div>
                    <div class="cd-value">{{ number_format($ttlInQtyWO ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Delivery Qty</div>
                    <div class="cd-value">{{ number_format($ttlOutQtyWO ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Outstanding WO Qty</div>
                    <div class="cd-value">{{ number_format($ttlOSQtyWO ?? 0, 0, '.', ',') }}</div>
                </div>
                <div class="cd-card">
                    <div class="cd-label">Not yet Input Qty</div>
                    <div class="cd-value">{{ number_format($ttlNotInQtyWO ?? 0, 0, '.', ',') }}</div>
                </div>                
            </div>
        </div>        
    </div>

</x-filament::page>