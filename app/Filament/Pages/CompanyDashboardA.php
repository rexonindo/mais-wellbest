<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class CompanyDashboardA extends Page
{
    protected static ?string $title = 'Dashboard A';
    protected static bool $shouldShowTitle = false;
    protected static ?string $navigationLabel = 'Dashboard A';
    protected static string $view = 'filament.pages.company-dashboard-a';
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $slug = '/company-dashboard-a';
    protected static bool $shouldRegisterNavigation = true;
    protected static ?int $navigationSort = 2;    

    /** Processes that are not production output (e.g. incoming material warehouse). */
    private const EXCLUDED_PROCESSES = ['P000'];

    /** NG rate (%) above which a row is highlighted red. */
    private const NG_ALERT_PCT = 1.0;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('dashboard') ?? false;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('dashboard') ?? false;
    }

    public function getTitle(): string
    {
        return '';
    }    

    // Existing cards
    public $ttlProductSINGLE;
    public $ttlMachineSINGLE;
    public $ttlPlanQtySINGLE;
    public $ttlInQtySINGLE;
    public $ttlOutQtySINGLE;
    public $ttlWIPQtySINGLE;
    public $ttlOSQtySINGLE;

    public $ttlProductDOUBLE;
    public $ttlMachineDOUBLE;
    public $ttlPlanQtyDOUBLE;
    public $ttlInQtyDOUBLE;
    public $ttlOutQtyDOUBLE;
    public $ttlWIPQtyDOUBLE;
    public $ttlOSQtyDOUBLE;

    public $ttlProductOTHER;
    public $ttlMachineOTHER;
    public $ttlPlanQtyOTHER;
    public $ttlInQtyOTHER;
    public $ttlOutQtyOTHER;
    public $ttlWIPQtyOTHER;
    public $ttlOSQtyOTHER;


    public $ttlPlanQtyWO;
    public $ttlInQtyWO;
    public $ttlOutQtyWO; 
    public $ttlOSQtyWO;
    public $ttlNotInQtyWO;

    // Filters
    public $dateFrom;
    public $dateTo;
    public $itemFilter = '';
    public $customerFilter = '';

    // Data for the view
    public array $kpi = [];
    public array $charts = [];
    public array $itemRows = [];
    public array $processRows = [];
    public array $customerRows = [];
    public array $itemOptions = [];
    public array $customerOptions = [];
    public array $groupRows = [];
    public array $ngCatRows = [];
    public array $ngCatSumRows = [];    

    public function mount()
    {
        // --- Production Performance Single --------------------------------------------
        $productCount = DB::table('current_total_product_grp_view')
            ->where('itm_grp_surface', 'SINGLE')
            ->first();
        $this->ttlProductSINGLE = $productCount->ttl_product ?? 0;
        $machineCount = DB::table('current_total_machine_grp_view')
            ->where('itm_grp_surface', 'SINGLE')
            ->first();
        $this->ttlMachineSINGLE = $machineCount->ttl_machine ?? 0;
        $productionQty = DB::table('current_total_production_qty_grp_view')
            ->where('itm_grp_surface', 'SINGLE')
            ->first();
        $this->ttlPlanQtySINGLE = $productionQty->ttl_plan_qty ?? 0;
        $this->ttlInQtySINGLE   = $productionQty->ttl_in_qty ?? 0;
        $this->ttlOutQtySINGLE  = $productionQty->ttl_out_qty ?? 0;
        $this->ttlWIPQtySINGLE  = $productionQty->ttl_wip_qty ?? 0; 
        $this->ttlOSQtySINGLE   = $productionQty->ttl_os_qty ?? 0;

        // --- Production Profarmance Double --------------------------------------------
        $productCount = DB::table('current_total_product_grp_view')
            ->where('itm_grp_surface', 'DOUBLE')
            ->first();
        $this->ttlProductDOUBLE = $productCount->ttl_product ?? 0;
        $machineCount = DB::table('current_total_machine_grp_view')
            ->where('itm_grp_surface', 'DOUBLE')
            ->first();
        $this->ttlMachineDOUBLE = $machineCount->ttl_machine ?? 0;
        $productionQty = DB::table('current_total_production_qty_grp_view')
            ->where('itm_grp_surface', 'DOUBLE')
            ->first();
        $this->ttlPlanQtyDOUBLE = $productionQty->ttl_plan_qty ?? 0;
        $this->ttlInQtyDOUBLE   = $productionQty->ttl_in_qty ?? 0;
        $this->ttlOutQtyDOUBLE  = $productionQty->ttl_out_qty ?? 0;
        $this->ttlWIPQtyDOUBLE  = $productionQty->ttl_wip_qty ?? 0; 
        $this->ttlOSQtyDOUBLE   = $productionQty->ttl_os_qty ?? 0;

        // --- Production Profarmance Other --------------------------------------------
        $productCount = DB::table('current_total_product_grp_view')
            ->where('itm_grp_surface', 'OTHER')
            ->first();
        $this->ttlProductOTHER = $productCount->ttl_product ?? 0;
        $machineCount = DB::table('current_total_machine_grp_view')
            ->where('itm_grp_surface', 'OTHER')
            ->first();
        $this->ttlMachineOTHER = $machineCount->ttl_machine ?? 0;
        $productionQty = DB::table('current_total_production_qty_grp_view')
            ->where('itm_grp_surface', 'OTHER')
            ->first();
        $this->ttlPlanQtyOTHER = $productionQty->ttl_plan_qty ?? 0;
        $this->ttlInQtyOTHER   = $productionQty->ttl_in_qty ?? 0;
        $this->ttlOutQtyOTHER  = $productionQty->ttl_out_qty ?? 0;
        $this->ttlWIPQtyOTHER  = $productionQty->ttl_wip_qty ?? 0; 
        $this->ttlOSQtyOTHER   = $productionQty->ttl_os_qty ?? 0;        

        // --- WO Order Progress --------------------------------------
        $WOProgress = DB::table('current_total_wo_qty_view')            
            ->first();
        $this->ttlPlanQtyWO = $WOProgress->ttl_plan_qty ?? 0;
        $this->ttlInQtyWO   = $WOProgress->ttl_in_qty ?? 0;
        $this->ttlOutQtyWO  = $WOProgress->ttl_out_qty ?? 0; 
        $this->ttlOSQtyWO   = $WOProgress->ttl_os_qty ?? 0;
        $this->ttlNotInQtyWO = $WOProgress->ttl_notin_qty ?? 0;
        
        // ========================================================

        $this->dateTo   = now()->toDateString();
        $this->dateFrom = now()->subDays(29)->toDateString();

        $this->itemOptions = DB::table('prod_yield_view')
            ->select('itm_cd', DB::raw('MAX(itm_cd) as itm_cd'))
            ->groupBy('itm_cd')
            ->orderBy('itm_cd')
            ->get()
            ->map(fn ($r) => ['code' => $r->itm_cd, 'name' => $r->itm_cd])
            ->all();

        $this->customerOptions = DB::table('prod_yield_view')
            ->selectRaw("DISTINCT COALESCE(NULLIF(TRIM(cust_nm), ''), 'Unassigned') as cust")
            ->orderBy('cust')
            ->pluck('cust')
            ->all();

        $this->loadData();
    }

    public function setRange(int $days): void
    {
        $this->dateTo   = now()->toDateString();
        $this->dateFrom = now()->subDays($days - 1)->toDateString();
        $this->refreshDashboard();
    }

    public function updated($property): void
    {
        if (in_array($property, ['dateFrom', 'dateTo', 'itemFilter', 'customerFilter'], true)) {
            $this->refreshDashboard();
        }
    }

    private function refreshDashboard(): void
    {
        $this->loadData();
        $this->dispatch('dashboard-updated', charts: $this->charts);
    }

    private function loadData(): void
    {
        $from = Carbon::parse($this->dateFrom)->startOfDay();
        $to   = Carbon::parse($this->dateTo)->endOfDay();
        if ($to->lt($from)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        $query = DB::table('prod_yield_view')
            ->select(
                DB::raw('DATE(end_time) as d'),
                'itm_cd',
                DB::raw('MAX(itm_cd) as itm_cd'),
                DB::raw("COALESCE(NULLIF(TRIM(itm_grp), ''), 'Unassigned') as grp"),
                DB::raw("COALESCE(NULLIF(TRIM(cust_nm), ''), 'Unassigned') as cust"),
                'proc_cd',
                DB::raw('MAX(TRIM(proc_nm)) as proc_nm'),
                DB::raw('SUM(in_qty_pcs) as in_qty'),
                DB::raw('SUM(out_qty_pcs) as out_qty'),
                DB::raw('SUM(ng_qty_pcs) as ng_qty'),
                DB::raw('SUM(rwk_qty_pcs) as rwk_qty')
            )
            ->whereBetween('end_time', [$from, $to])
            ->whereNotIn('proc_cd', self::EXCLUDED_PROCESSES)
            ->groupBy(DB::raw('DATE(end_time)'), 'itm_cd', 'cust', 'grp', 'proc_cd');

        if ($this->itemFilter !== '' && $this->itemFilter !== null) {
            $query->where('itm_cd', $this->itemFilter);
        }

        if ($this->customerFilter !== '' && $this->customerFilter !== null) {
            $query->whereRaw("COALESCE(NULLIF(TRIM(cust_nm), ''), 'Unassigned') = ?", [$this->customerFilter]);
        }

        $rows = $query->get();

        // Factory output = out qty of the LAST process each product reached
        // (summing every process would count the same pieces many times).
        $finalProc = $rows->groupBy('itm_cd')->map(fn ($g) => $g->max('proc_cd'));
        $finalRows = $rows->filter(fn ($r) => $r->proc_cd === $finalProc[$r->itm_cd]);

        $totalOut = (int) $finalRows->sum('out_qty');
        $totalNg  = (int) $rows->sum('ng_qty');
        $totalIn  = (int) $rows->sum('in_qty');

        // ----- Daily trend -----
        $labels = [];
        $outByDay = [];
        $ngByDay = [];
        $ngRateByDay = [];
        $rwkRateByDay = [];
        $finalByDay = $finalRows->groupBy('d');
        $allByDay = $rows->groupBy('d');
        $cursor = $from->copy();
        $activeDays = 0;
        while ($cursor->lte($to)) {
            $key = $cursor->toDateString();
            $out = (int) ($finalByDay[$key] ?? collect())->sum('out_qty');
            $ng  = (int) ($allByDay[$key] ?? collect())->sum('ng_qty');
            $in  = (int) ($allByDay[$key] ?? collect())->sum('in_qty');
            $rwk = (int) ($allByDay[$key] ?? collect())->sum('rwk_qty');            
            if ($out > 0) {
                $activeDays++;
            }
            $labels[]      = $cursor->format('d M');
            $outByDay[]    = $out;
            $ngByDay[]     = $ng;
            $ngRateByDay[] = $in > 0 ? round($ng / $in * 100, 2) : 0;
            $rwkRateByDay[] = $in > 0 ? round($rwk / $in * 100, 2) : 0;
            $cursor->addDay();
        }

        // ----- Per item -----
        $this->itemRows = $rows->groupBy('itm_cd')->map(function ($g, $code) use ($finalProc) {
            $final = $g->firstWhere('proc_cd', $finalProc[$code]);
            $in  = (int) $g->sum('in_qty');
            $ng  = (int) $g->sum('ng_qty');
            return [
                'code'     => $code,
                'name'     => $g->first()->itm_cd,
                'final'    => $final->proc_nm ?? '-',
                'output'   => (int) $g->where('proc_cd', $finalProc[$code])->sum('out_qty'),
                'ng'       => $ng,
                'ng_rate'  => $in > 0 ? round($ng / $in * 100, 2) : 0,
            ];
        })->sortByDesc('output')->values()->all();

        // ----- Per process -----
        $this->processRows = $rows->groupBy('proc_cd')->map(function ($g, $code) {
            $in  = (int) $g->sum('in_qty');
            $out = (int) $g->sum('out_qty');
            $ng  = (int) $g->sum('ng_qty');
            $rwk = (int) $g->sum('rwk_qty');
            return [
                'code'     => $code,
                'name'     => $g->first()->proc_nm,
                'in'       => $in,
                'out'      => $out,
                'ng'       => $ng,
                'rwk'      => $rwk,
                'ng_rate'  => $in > 0 ? round($ng / $in * 100, 2) : 0,
                'rwk_rate' => $in > 0 ? round($rwk / $in * 100, 2) : 0,
            ];
        })->sortKeys()->values()->all();

        // ----- Per customer -----
        $this->customerRows = $rows->groupBy('cust')->map(function ($g, $name) use ($finalProc) {
            $in  = (int) $g->sum('in_qty');
            $ng  = (int) $g->sum('ng_qty');
            $out = (int) $g->filter(fn ($r) => $r->proc_cd === $finalProc[$r->itm_cd])->sum('out_qty');
            return [
                'name'     => $name,
                'products' => $g->pluck('itm_cd')->unique()->count(),
                'output'   => $out,
                'ng'       => $ng,
                'ng_rate'  => $in > 0 ? round($ng / $in * 100, 2) : 0,
            ];
        })->sortByDesc('output')->values()->all();

        // ----- Per PCB group -----
        $this->groupRows = $rows->groupBy('grp')->map(function ($g, $name) {
            $in = (int) $g->sum('in_qty');
            $ng = (int) $g->sum('ng_qty');
            $rwk = (int) $g->sum('rwk_qty');
            return [
                'name'    => $name,
                'ng'      => $ng,
                'ng_rate' => $in > 0 ? round($ng / $in * 100, 2) : 0,
                'rwk_rate' => $in > 0 ? round($rwk / $in * 100, 2) : 0,
            ];
        })->sortByDesc('ng')->values()->all();
        
        // ----- NG ratio by category (stored procedures) -----
        $pFrom = $from->format('Y-m-d H:i:s');
        $pTo   = $to->format('Y-m-d H:i:s');

        $this->ngCatRows = collect(DB::select('CALL ng_detail_ratio_by_category(?, ?)', [$pFrom, $pTo]))
            ->map(fn ($r) => [
                'cat'   => (string) $r->ng_cat,
                'name'  => (string) $r->ng_nm,
                'qty'   => (int) $r->ng_qty,
                'ratio' => round(((float) ($r->ng_ratio ?? 0)) * 100, 2),
                'std'   => round(((float) $r->ng_std) * 100, 2),
            ])->all();

        // Same as the Excel: largest category first
        $this->ngCatSumRows = collect(DB::select('CALL ng_detail_ratio_by_category_sum(?, ?)', [$pFrom, $pTo]))
            ->map(fn ($r) => [
                'cat'   => (string) $r->ng_cat,
                'qty'   => (int) $r->ng_qty,
                'ratio' => round(((float) ($r->ng_ratio ?? 0)) * 100, 2),
                'std'   => round(((float) $r->ng_std) * 100, 2),
            ])->sortByDesc('qty')->values()->all();        

        $ngItems = collect($this->itemRows)->where('ng', '>', 0)->values();

        $this->kpi = [
            'output'      => $totalOut,
            'avg_daily'   => $activeDays > 0 ? (int) round($totalOut / $activeDays) : 0,
            'ng'          => $totalNg,
            'ng_rate'     => $totalIn > 0 ? round($totalNg / $totalIn * 100, 2) : 0,
            'items'       => count($this->itemRows),
            'alert_pct'   => self::NG_ALERT_PCT,
        ];

        $this->charts = [
            'daily' => [
                'labels' => $labels,
                'output' => $outByDay,
                'ng'     => $ngByDay,
                'rate'   => $ngRateByDay,
                'rwk_rate' => $rwkRateByDay,
            ],
            'items' => [
                'labels' => collect($this->itemRows)->pluck('name')->all(),
                'output' => collect($this->itemRows)->pluck('output')->all(),
            ],
            'process' => [
                'labels' => collect($this->processRows)->map(fn ($p) => $p['code'] . ' ' . $p['name'])->all(),
                'ng'     => collect($this->processRows)->pluck('ng')->all(),
                'rate'   => collect($this->processRows)->pluck('ng_rate')->all(),
                'rwk_rate' => collect($this->processRows)->pluck('rwk_rate')->all(),
            ],
            'processOutput' => [
                'labels'   => collect($this->processRows)->map(fn ($p) => $p['code'] . ' ' . $p['name'])->all(),
                'in'       => collect($this->processRows)->pluck('in')->all(),
                'out'      => collect($this->processRows)->pluck('out')->all(),
                'ng_rate'  => collect($this->processRows)->pluck('ng_rate')->all(),
                'rwk_rate' => collect($this->processRows)->pluck('rwk_rate')->all(),
            ],            
            'ngCategory' => [
                'labels' => collect($this->ngCatRows)->pluck('name')->all(),
                'cats'   => collect($this->ngCatRows)->pluck('cat')->all(),
                'qty'    => collect($this->ngCatRows)->pluck('qty')->all(),
                'ratio'  => collect($this->ngCatRows)->pluck('ratio')->all(),
                'std'    => collect($this->ngCatRows)->pluck('std')->all(),
            ],
            'ngCategorySum' => [
                'labels' => collect($this->ngCatSumRows)->pluck('cat')->all(),
                'qty'    => collect($this->ngCatSumRows)->pluck('qty')->all(),
                'ratio'  => collect($this->ngCatSumRows)->pluck('ratio')->all(),
                'std'    => collect($this->ngCatSumRows)->pluck('std')->all(),
            ],            
            'pcbGroup' => [
                'labels' => collect($this->groupRows)->pluck('name')->all(),
                'ng'     => collect($this->groupRows)->pluck('ng')->all(),
                'rate'   => collect($this->groupRows)->pluck('ng_rate')->all(),
                'rwk_rate' => collect($this->groupRows)->pluck('rwk_rate')->all(),
            ],            
            'ngItems' => [
                'labels' => $ngItems->pluck('name')->all(),
                'values' => $ngItems->pluck('ng')->all(),
            ],
            'customer' => [
                'labels' => collect($this->customerRows)->pluck('name')->all(),
                'output' => collect($this->customerRows)->pluck('output')->all(),
                'rate'   => collect($this->customerRows)->pluck('ng_rate')->all(),
            ],
        ];
    }
}
