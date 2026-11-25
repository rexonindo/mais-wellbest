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
            height: calc(100vh - 1.5rem);   /* topbar is hidden for the dashboard role */
            display: flex;
            flex-direction: column;
            padding: 0 8px;
            margin-top: -10px;
            overflow: hidden;
        }
        .cd-dashboard-heading { flex: 0 0 auto; margin: 0 0 4px; }

        .cd-main-title {
            font-size: clamp(16px, 2.8vh, 26px);
            padding: 2px 0 6px;
            margin: 0 0 4px;
        }
        .cd-main-title::after { height: 3px; margin: 5px auto 0; }
        .cd-main-subtitle {
            font-size: clamp(9px, 1.3vh, 12px);
            margin: -2px 0 6px;
        }

        /* the 2-card grid takes all the remaining height */
        .cd-wrap > .cd-grid.cd-g2 {
            flex: 1 1 0;
            min-height: 0;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            grid-template-rows: minmax(0, 1fr);
            gap: 8px;
        }
        .cd-wrap > .cd-grid.cd-g2 > .cd-card {
            display: flex;
            flex-direction: column;
            min-height: 0;
            padding: .5rem .75rem;
        }
        .cd-wrap > .cd-grid.cd-g2 .cd-title {
            flex: 0 0 auto;
            margin-bottom: .25rem;
            font-size: clamp(.8rem, 1.8vh, 1rem);
        }

        /* each chart fills its card (overrides the old 300px) */
        .cd-wrap > .cd-grid.cd-g2 .cd-chart {
            flex: 1 1 0;
            min-height: 0;
            height: auto;
            position: relative;
        }

        /* phones / narrow screens: normal scrolling */
        @media (max-width: 768px) {
            .cd-wrap { height: auto; overflow: visible; }
            .cd-wrap > .cd-grid.cd-g2 { grid-template-columns: 1fr; grid-template-rows: none; }
            .cd-wrap > .cd-grid.cd-g2 .cd-chart { flex: none; height: 300px; }
        }        

    </style>


    <?php if (! $__env->hasRenderedOnce('0f7bb201-1dd1-41d7-8b02-fc31a991225f')): $__env->markAsRenderedOnce('0f7bb201-1dd1-41d7-8b02-fc31a991225f'); ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/flatpickr.min.css')); ?>">
        <script src="<?php echo e(asset('js/flatpickr.min.js')); ?>"></script>
        <script src="<?php echo e(asset('js/chart.umd.min.js')); ?>"></script>        
    <?php endif; ?>

    <?php
        $isDashboardUser = auth()->check() && auth()->user()->hasRole('dashboard');
    ?>    

    <script>

        /* =========================================================
        Dashboard rotator: only for users with the "dashboard" role.
        ========================================================= */
        document.addEventListener('DOMContentLoaded', function () {

            const ROTATE_ENABLED = <?php echo json_encode($isDashboardUser, 15, 512) ?>;
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

                make('cdNgItems', {
                    type: 'doughnut',
                    data: {
                        labels: d.ngItems.labels,
                        datasets: [{ data: d.ngItems.values, backgroundColor: ['#ef4444', '#f97316', '#eab308', '#8b5cf6', '#06b6d4', '#64748b'] }],
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } },
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
                PRODUCTION OUTPUT AND NG BY PART NUMBER
            </h2>
            <div class="cd-main-subtitle">
                CURRENT MONTH PRODUCTION MONITORING
            </div>
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
<?php /**PATH D:\website\mais-wellbest\resources\views/filament/pages/company-dashboard-d.blade.php ENDPATH**/ ?>