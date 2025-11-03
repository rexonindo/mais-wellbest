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
        DB::statement("CREATE VIEW `wo_progress_view` AS select `a`.`wo_no` AS `wo_no`,`a`.`itm_cd` AS `itm_cd`,`b`.`itm_type` AS `itm_type`,`c`.`seq_no` AS `seq_no`,`c`.`proc_cd` AS `proc_cd`,`d`.`proc_nm` AS `proc_nm`,`e`.`end_time` AS `end_time`,`e`.`in_qty` AS `in_qty`,`e`.`ng_qty` AS `ng_qty`,`e`.`out_qty` AS `out_qty`,`e`.`mchn_cd` AS `mchn_cd`,`f`.`emp_nm` AS `emp_nm` from (((((`mais_wellbest`.`wo_tbl` `a` left join `mais_wellbest`.`itm_tbl` `b` on((`a`.`itm_cd` = `b`.`itm_cd`))) left join `mais_wellbest`.`prdroute_tbl` `c` on((`b`.`itm_type` = `c`.`itm_type`))) left join `mais_wellbest`.`proc_tbl` `d` on((`c`.`proc_cd` = `d`.`proc_cd`))) left join `mais_wellbest`.`prdlog_tbl` `e` on(((`a`.`wo_no` = `e`.`wo_no`) and (`a`.`itm_cd` = `e`.`itm_cd`) and (`c`.`proc_cd` = `e`.`proc_cd`)))) left join `mais_wellbest`.`empl_tbl` `f` on((`e`.`emp_id` = `f`.`emp_id`))) order by `a`.`wo_no`,`a`.`itm_cd`,`b`.`itm_type`,`c`.`seq_no`");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `wo_progress_view`");
    }
};
