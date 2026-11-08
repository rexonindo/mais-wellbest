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
        DB::statement("CREATE VIEW `prdlog_ng_view` AS select `mais_wellbest`.`a`.`wo_no` AS `wo_no`,`mais_wellbest`.`a`.`itm_cd` AS `itm_cd`,`mais_wellbest`.`a`.`itm_type` AS `itm_type`,`mais_wellbest`.`a`.`itm_grp` AS `itm_grp`,`mais_wellbest`.`a`.`seq_no` AS `seq_no`,`mais_wellbest`.`a`.`proc_cd` AS `proc_cd`,`mais_wellbest`.`a`.`proc_nm` AS `proc_nm`,`mais_wellbest`.`a`.`division` AS `division`,`mais_wellbest`.`a`.`end_time` AS `end_time`,`mais_wellbest`.`a`.`in_qty` AS `in_qty`,`mais_wellbest`.`a`.`out_qty` AS `out_qty`,ifnull(`mais_wellbest`.`a`.`rmks`,'-') AS `rmks`,`b`.`ng_nm` AS `ng_nm`,`c`.`category` AS `ng_cat`,`b`.`ng_qty` AS `ng_qty` from ((`mais_wellbest`.`wo_progress_view` `a` left join `mais_wellbest`.`prdng_tbl` `b` on((`mais_wellbest`.`a`.`id_prd` = `b`.`id_prd`))) left join `mais_wellbest`.`ng_tbl` `c` on((`b`.`ng_nm` = `c`.`ng_nm`)))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `prdlog_ng_view`");
    }
};
