<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class CompanyDashboardC extends Page
{
    protected static ?string $title = 'Dashboard C';
    protected static bool $shouldShowTitle = false;
    protected static ?string $navigationLabel = 'Dashboard C';
    protected static string $view = 'filament.pages.company-dashboard-c';
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $slug = '/company-dashboard-c';
    protected static bool $shouldRegisterNavigation = true;
    protected static ?int $navigationSort = 2;    

    /** Processes that are not production output (e.g. incoming material warehouse). */
    private const EXCLUDED_PROCESSES = ['P000'];

    /** NG rate (%) above which a row is highlighted red. */
    private const NG_ALERT_PCT = 1.0;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole(['dashboard', 'admin']) ?? false;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole(['dashboard', 'admin']) ?? false;
    }
    
    public function getTitle(): string
    {
        return '';
    }    

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
        $this->dateTo   = now()->toDateString();
        $this->dateFrom = now()->subDays(29)->toDateString();
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
        $ngItems = collect($this->itemRows)->where('ng', '>', 0)->values();
        $this->kpi = [
            'output'      => $totalOut,
            'avg_daily'   => $activeDays > 0 ? (int) round($totalOut / $activeDays) : 0,
            'ng'          => $totalNg,
            'ng_rate'     => $totalIn > 0 ? round($totalNg / $totalIn * 100, 2) : 0,
            'items'       => count($this->itemRows),
            'alert_pct'   => self::NG_ALERT_PCT,
        ];

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

        $this->charts = [
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
        ];
    }
}
