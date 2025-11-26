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
        DB::statement("CREATE VIEW `wo_status_view` AS select `mais_wellbest`.`a`.`wo_no` AS `wo_no`,`c`.`req_dt` AS `req_dt`,`mais_wellbest`.`a`.`itm_cd` AS `itm_cd`,`mais_wellbest`.`a`.`itm_type` AS `itm_type`,`mais_wellbest`.`a`.`seq_no` AS `seq_no`,`mais_wellbest`.`a`.`proc_cd` AS `proc_cd`,`mais_wellbest`.`a`.`proc_nm` AS `proc_nm`,`mais_wellbest`.`a`.`end_time` AS `end_time`,`c`.`plan_qty` AS `plan_qty`,`mais_wellbest`.`a`.`out_qty` AS `out_qty`,(`c`.`plan_qty` - `mais_wellbest`.`a`.`out_qty`) AS `os_qty`,`mais_wellbest`.`a`.`mchn_cd` AS `mchn_cd`,`mais_wellbest`.`a`.`emp_nm` AS `emp_nm` from ((`mais_wellbest`.`wo_progress_view` `a` join (select `mais_wellbest`.`wo_progress_view`.`wo_no` AS `wo_no`,max(`mais_wellbest`.`wo_progress_view`.`seq_no`) AS `seq_no` from `mais_wellbest`.`wo_progress_view` where (ifnull(`mais_wellbest`.`wo_progress_view`.`out_qty`,0) <> 0) group by `mais_wellbest`.`wo_progress_view`.`wo_no`) `b` on(((`mais_wellbest`.`a`.`wo_no` = `b`.`wo_no`) and (`mais_wellbest`.`a`.`seq_no` = `b`.`seq_no`)))) join `mais_wellbest`.`wo_tbl` `c` on((`mais_wellbest`.`a`.`wo_no` = `c`.`wo_no`)))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `wo_status_view`");
    }
};
