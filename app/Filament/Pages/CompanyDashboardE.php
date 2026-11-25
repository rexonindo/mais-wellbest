<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class CompanyDashboardE extends Page
{
    protected static ?string $title = 'Dashboard E';
    protected static bool $shouldShowTitle = false;
    protected static ?string $navigationLabel = 'Dashboard E';
    protected static string $view = 'filament.pages.company-dashboard-e';
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $slug = '/company-dashboard-e';
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
        
        // ----- NG ratio by category (stored procedures) -----
        $pFrom = $from->format('Y-m-d H:i:s');
        $pTo   = $to->format('Y-m-d H:i:s');
        $pItem = (string) ($this->itemFilter ?? '');
        $pCust = (string) ($this->customerFilter ?? '');

        // ----NG------------------------------------------------------------
        $this->ngCatRows = collect(DB::select(
            'CALL ng_detail_ratio_by_category_dash(?, ?, ?, ?)',
            [$pFrom, $pTo, $pItem, $pCust]
        ))
            ->map(fn ($r) => [
                'cat'   => (string) $r->ng_cat,
                'name'  => (string) $r->ng_nm,
                'qty'   => (int) $r->ng_qty,
                'ratio' => round(((float) ($r->ng_ratio ?? 0)) * 100, 2),
                'std'   => round(((float) $r->ng_std) * 100, 2),
            ])->all();

        $this->ngCatSumRows = collect(DB::select(
            'CALL ng_detail_ratio_by_category_sum_dash(?, ?, ?, ?)',
            [$pFrom, $pTo, $pItem, $pCust]
        ))
            ->map(fn ($r) => [
                'cat'   => (string) $r->ng_cat,
                'qty'   => (int) $r->ng_qty,
                'ratio' => round(((float) ($r->ng_ratio ?? 0)) * 100, 2),
                'std'   => round(((float) $r->ng_std) * 100, 2),
            ])->sortByDesc('qty')->values()->all();

        // ----RWK------------------------------------------------------------          
        $this-> rwkCatRows = collect(DB::select(
            'CALL rwk_detail_ratio_by_category_dash(?, ?, ?, ?)',
            [$pFrom, $pTo, $pItem, $pCust]
        ))
            ->map(fn ($r) => [
                'cat'   => (string) $r->rwk_cat,
                'name'  => (string) $r->rwk_nm,
                'qty'   => (int) $r->rwk_qty,
                'ratio' => round(((float) ($r->rwk_ratio ?? 0)) * 100, 2),
                'std'   => round(((float) $r->rwk_std) * 100, 2),
            ])->all();

        $this->rwkCatSumRows = collect(DB::select(
            'CALL rwk_detail_ratio_by_category_sum_dash(?, ?, ?, ?)',
            [$pFrom, $pTo, $pItem, $pCust]
        ))
            ->map(fn ($r) => [
                'cat'   => (string) $r->rwk_cat,
                'qty'   => (int) $r->rwk_qty,
                'ratio' => round(((float) ($r->rwk_ratio ?? 0)) * 100, 2),
                'std'   => round(((float) $r->rwk_std) * 100, 2),
            ])->sortByDesc('qty')->values()->all();
        // ------------------------------------------------------------------
        $this->charts = [
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
            'rwkCategory' => [
                'labels' => collect($this->rwkCatRows)->pluck('name')->all(),
                'cats'   => collect($this->rwkCatRows)->pluck('cat')->all(),
                'qty'    => collect($this->rwkCatRows)->pluck('qty')->all(),
                'ratio'  => collect($this->rwkCatRows)->pluck('ratio')->all(),
                'std'    => collect($this->rwkCatRows)->pluck('std')->all(),
            ],
            'rwkCategorySum' => [
                'labels' => collect($this->rwkCatSumRows)->pluck('cat')->all(),
                'qty'    => collect($this->rwkCatSumRows)->pluck('qty')->all(),
                'ratio'  => collect($this->rwkCatSumRows)->pluck('ratio')->all(),
                'std'    => collect($this->rwkCatSumRows)->pluck('std')->all(),
            ],
        ];
    }
}
