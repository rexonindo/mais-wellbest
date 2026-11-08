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
        DB::statement("CREATE VIEW `wo_progress_view` AS select `a`.`wo_no` AS `wo_no`,`a`.`itm_cd` AS `itm_cd`,`b`.`itm_type` AS `itm_type`,`x`.`itm_grp` AS `itm_grp`,`c`.`seq_no` AS `seq_no`,`c`.`proc_cd` AS `proc_cd`,`e`.`id` AS `id_prd`,`d`.`proc_nm` AS `proc_nm`,`d`.`division` AS `division`,`e`.`rmks` AS `rmks`,`e`.`start_time` AS `start_time`,`e`.`end_time` AS `end_time`,`a`.`plan_qty` AS `wo_qty`,ifnull(`c`.`cav`,0) AS `cav`,(ifnull(`e`.`in_qty`,0) * ifnull(`c`.`cav`,0)) AS `in_qty`,(ifnull(`e`.`rwk_qty`,0) * ifnull(`c`.`cav`,0)) AS `rwk_qty`,ifnull(`e`.`rwk_qty_pcs`,0) AS `rwk_qty_pcs`,(ifnull(`e`.`ng_qty`,0) * ifnull(`c`.`cav`,0)) AS `ng_qty`,ifnull(`e`.`ng_qty_pcs`,0) AS `ng_qty_pcs`,(ifnull(`e`.`out_qty`,0) * ifnull(`c`.`cav`,0)) AS `out_qty`,(((ifnull(`e`.`rwk_qty`,0) * ifnull(`c`.`cav`,0)) + ((ifnull(`e`.`ng_qty`,0) * ifnull(`c`.`cav`,0)) + ifnull(`e`.`ng_qty_pcs`,0))) + (ifnull(`e`.`out_qty`,0) * ifnull(`c`.`cav`,0))) AS `ttl_qty`,`e`.`out_qty` AS `out_qty_shoot`,((ifnull(`e`.`rwk_qty`,0) + ifnull(`e`.`ng_qty`,0)) + ifnull(`e`.`out_qty`,0)) AS `ttl_qty_shoot`,`mais_wellbest`.`h`.`qty` AS `onhand_qty`,`e`.`mchn_cd` AS `mchn_cd`,`g`.`emp_nm` AS `emp_nm` from (((((((`mais_wellbest`.`wo_tbl` `a` left join `mais_wellbest`.`itm_tbl` `b` on((`a`.`itm_cd` = `b`.`itm_cd`))) left join `mais_wellbest`.`itmgrp_tbl` `x` on((`b`.`itm_type` = `x`.`itm_type`))) left join `mais_wellbest`.`wo_proc_tbl` `c` on((`a`.`wo_no` = `c`.`wo_no`))) left join `mais_wellbest`.`proc_tbl` `d` on((`c`.`proc_cd` = `d`.`proc_cd`))) left join `mais_wellbest`.`prdlog_tbl` `e` on(((`a`.`wo_no` = `e`.`wo_no`) and (`a`.`itm_cd` = `e`.`itm_cd`) and (`c`.`proc_cd` = `e`.`proc_cd`)))) left join `mais_wellbest`.`empl_tbl` `g` on((`e`.`emp_id` = `g`.`emp_id`))) left join `mais_wellbest`.`iv_status_wo_view` `h` on(((`a`.`wo_no` = `mais_wellbest`.`h`.`wo_no`) and (`a`.`itm_cd` = `mais_wellbest`.`h`.`itm_cd`) and (`c`.`proc_cd` = `mais_wellbest`.`h`.`proc_cd`)))) order by `a`.`wo_no`,`a`.`itm_cd`,`b`.`itm_type`,`c`.`seq_no`");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `wo_progress_view`");
    }
};
