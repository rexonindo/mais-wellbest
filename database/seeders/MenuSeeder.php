<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menus')->insert([
            [
                'group' => 'Master',
                'name' => 'Department',
                'icon' => 'heroicon-o-building-office',
                'url' => '/departments',
                'order' => 1,
            ],
            [
                'group' => 'Master',
                'name' => 'Employee',
                'icon' => 'heroicon-o-user',
                'url' => '/employees',
                'order' => 2,
            ],
            [
                'group' => 'Production',
                'name' => 'Work Order',
                'icon' => 'heroicon-o-clipboard-document',
                'url' => '/work-orders',
                'order' => 3,
            ],
        ]);
    }
}
