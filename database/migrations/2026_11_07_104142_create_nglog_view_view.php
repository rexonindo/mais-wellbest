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
        DB::statement("CREATE VIEW `nglog_view` AS select `a`.`start_time` AS `start_time`,`a`.`itm_cd` AS `itm_cd`,`c`.`itm_type` AS `itm_type`,`d`.`proc_nm` AS `proc_nm`,`b`.`ng_nm` AS `ng_nm`,`b`.`ng_qty` AS `ng_qty`,`a`.`emp_id` AS `emp_id`,`e`.`mchn_nm` AS `mchn_nm` from ((((`mais_wellbest`.`prdlog_tbl` `a` join `mais_wellbest`.`prdng_tbl` `b` on((`a`.`id` = `b`.`id_prd`))) join `mais_wellbest`.`itm_tbl` `c` on((`a`.`itm_cd` = `c`.`itm_cd`))) join `mais_wellbest`.`proc_tbl` `d` on((`a`.`proc_cd` = `d`.`proc_cd`))) left join `mais_wellbest`.`mchn_tbl` `e` on((`a`.`mchn_cd` = `e`.`mchn_cd`)))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `nglog_view`");
    }
};
