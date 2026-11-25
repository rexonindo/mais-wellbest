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
    }
}
