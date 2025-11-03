<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class CompanyDashboard extends Page
{
    protected static ?string $title = 'Dashboard';
    protected static string $view = 'filament.pages.company-dashboard';
    // protected static ?string $icon = 'heroicon-o-home';
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $slug = '/company-dashboard';
    protected static bool $shouldRegisterNavigation = true;

    public function getTitle(): string
    {
        return '';
    }
    
    public $ttlProduct;
    public $ttlMachine;
    public $ttlPlanQty;
    public $ttlOutQty;
    public $ttlOSQty;

    public function mount()
    {
        // Run your query to get the value
        $this->ttlProduct = DB::table('current_total_product_view')->value('ttl_product');
        $this->ttlMachine = DB::table('current_total_machine_view')->value('ttl_machine'); 
        $this->ttlPlanQty = DB::table('current_production_qty_view')->value('ttl_plan_qty'); 
        $this->ttlOutQty = DB::table('current_production_qty_view')->value('ttl_out_qty'); 
        $this->ttlOSQty = DB::table('current_production_qty_view')->value('ttl_os_qty'); 
               
    }
}

