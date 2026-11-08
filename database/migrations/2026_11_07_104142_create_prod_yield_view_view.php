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
        DB::statement("CREATE VIEW `prod_yield_view` AS select `a`.`id` AS `id`,`a`.`end_time` AS `end_time`,`a`.`itm_cd` AS `itm_cd`,`b`.`itm_nm` AS `itm_nm`,`d`.`cust_nm` AS `cust_nm`,`a`.`proc_cd` AS `proc_cd`,`c`.`proc_nm` AS `proc_nm`,`a`.`mchn_cd` AS `mchn_cd`,`a`.`emp_id` AS `emp_id`,`a`.`cav` AS `cav`,(ifnull(`a`.`in_qty`,0) * `a`.`cav`) AS `in_qty_pcs`,(ifnull(`a`.`out_qty`,0) * `a`.`cav`) AS `out_qty_pcs`,(ifnull(`a`.`ng_qty`,0) * `a`.`cav`) AS `ng_qty_pcs` from (((`mais_wellbest`.`prdlog_tbl` `a` left join `mais_wellbest`.`itm_tbl` `b` on((`a`.`itm_cd` = `b`.`itm_cd`))) left join `mais_wellbest`.`proc_tbl` `c` on((`a`.`proc_cd` = `c`.`proc_cd`))) left join `mais_wellbest`.`cust_tbl` `d` on((`b`.`cust_cd` = `d`.`cust_cd`)))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `prod_yield_view`");
    }
};
