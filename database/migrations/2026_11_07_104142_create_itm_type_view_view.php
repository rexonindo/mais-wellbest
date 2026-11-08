<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE VIEW `itm_type_view` AS select `mais_wellbest`.`itmgrp_tbl`.`itm_type` AS `itm_type` from `mais_wellbest`.`itmgrp_tbl` group by `mais_wellbest`.`itmgrp_tbl`.`itm_type`");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `itm_type_view`");
    }
};
