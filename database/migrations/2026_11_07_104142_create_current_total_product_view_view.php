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
        DB::statement("CREATE VIEW `current_total_product_view` AS select count(0) AS `ttl_product` from (select `mais_wellbest`.`prdlog_tbl`.`itm_cd` AS `total_product` from `mais_wellbest`.`prdlog_tbl` where ((year(`mais_wellbest`.`prdlog_tbl`.`start_time`) = year(now())) and (month(`mais_wellbest`.`prdlog_tbl`.`start_time`) = month(now()))) group by `mais_wellbest`.`prdlog_tbl`.`itm_cd`) `a`");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `current_total_product_view`");
    }
};
