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
        DB::statement("CREATE VIEW `rwk_detail_1st_view` AS select `mais_wellbest`.`a`.`wo_no` AS `wo_no`,`mais_wellbest`.`a`.`itm_cd` AS `itm_cd`,`mais_wellbest`.`a`.`itm_type` AS `itm_type`,`mais_wellbest`.`a`.`itm_grp` AS `itm_grp`,`mais_wellbest`.`a`.`end_time` AS `end_time`,ifnull(`mais_wellbest`.`a`.`in_qty`,0) AS `in_qty`,`b`.`seq_no` AS `seq_no`,`b`.`proc_cd` AS `proc_cd`,`b`.`proc_nm` AS `proc_nm`,`b`.`division` AS `division`,ifnull(`b`.`rwk_nm`,'') AS `rwk_nm`,ifnull(`b`.`rwk_cat`,'') AS `rwk_cat`,ifnull(`b`.`rwk_qty`,0) AS `rwk_qty`,ifnull(`mais_wellbest`.`c`.`out_qty`,0) AS `fgs_qty` from ((`mais_wellbest`.`prodlog_process_1st_view` `a` left join (select `mais_wellbest`.`prdlog_rwk_view`.`wo_no` AS `wo_no`,`mais_wellbest`.`prdlog_rwk_view`.`itm_cd` AS `itm_cd`,`mais_wellbest`.`prdlog_rwk_view`.`seq_no` AS `seq_no`,`mais_wellbest`.`prdlog_rwk_view`.`proc_cd` AS `proc_cd`,`mais_wellbest`.`prdlog_rwk_view`.`proc_nm` AS `proc_nm`,`mais_wellbest`.`prdlog_rwk_view`.`division` AS `division`,`mais_wellbest`.`prdlog_rwk_view`.`rwk_nm` AS `rwk_nm`,`mais_wellbest`.`prdlog_rwk_view`.`rwk_cat` AS `rwk_cat`,`mais_wellbest`.`prdlog_rwk_view`.`rwk_qty` AS `rwk_qty` from `mais_wellbest`.`prdlog_rwk_view`) `b` on(((`mais_wellbest`.`a`.`wo_no` = `b`.`wo_no`) and (`mais_wellbest`.`a`.`itm_cd` = `b`.`itm_cd`) and (`b`.`seq_no` >= `mais_wellbest`.`a`.`seq_no`)))) left join `mais_wellbest`.`prodlog_process_last_view` `c` on(((`mais_wellbest`.`a`.`wo_no` = `mais_wellbest`.`c`.`wo_no`) and (`mais_wellbest`.`a`.`itm_cd` = `mais_wellbest`.`c`.`itm_cd`) and (`mais_wellbest`.`a`.`itm_type` = `mais_wellbest`.`c`.`itm_type`))))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `rwk_detail_1st_view`");
    }
};
