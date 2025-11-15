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
        DB::statement("CREATE VIEW `current_production_qty_view` AS select `a`.`pyear` AS `pyear`,`a`.`pmonth` AS `pmonth`,sum(ifnull(`a`.`plan_qty`,0)) AS `ttl_plan_qty`,sum(ifnull(`b`.`out_qty`,0)) AS `ttl_out_qty`,sum((ifnull(`a`.`plan_qty`,0) - ifnull(`b`.`out_qty`,0))) AS `ttl_os_qty` from ((select year(`mais_wellbest`.`a`.`req_dt`) AS `pyear`,month(`mais_wellbest`.`a`.`req_dt`) AS `pmonth`,`mais_wellbest`.`a`.`wo_no` AS `wo_no`,sum(`mais_wellbest`.`a`.`plan_qty`) AS `plan_qty` from `mais_wellbest`.`wo_status_view` `a` group by year(`mais_wellbest`.`a`.`req_dt`),month(`mais_wellbest`.`a`.`req_dt`),`mais_wellbest`.`a`.`wo_no`) `a` left join (select year(`mais_wellbest`.`a`.`end_time`) AS `pyear`,month(`mais_wellbest`.`a`.`end_time`) AS `pmonth`,`mais_wellbest`.`a`.`wo_no` AS `wo_no`,sum(`mais_wellbest`.`a`.`out_qty`) AS `out_qty` from (`mais_wellbest`.`wo_status_view` `a` join (select `mais_wellbest`.`prdroute_tbl`.`itm_type` AS `itm_type`,max(`mais_wellbest`.`prdroute_tbl`.`seq_no`) AS `seq_no` from `mais_wellbest`.`prdroute_tbl` group by `mais_wellbest`.`prdroute_tbl`.`itm_type`) `b` on(((`mais_wellbest`.`a`.`itm_type` = `b`.`itm_type`) and (`mais_wellbest`.`a`.`seq_no` = `b`.`seq_no`)))) group by year(`mais_wellbest`.`a`.`end_time`),month(`mais_wellbest`.`a`.`end_time`),`mais_wellbest`.`a`.`wo_no`) `b` on(((`a`.`pyear` = `b`.`pyear`) and (`a`.`pmonth` = `b`.`pmonth`) and (`a`.`wo_no` = `b`.`wo_no`)))) where ((`a`.`pyear` = year(now())) and (`a`.`pmonth` = month(now()))) group by `a`.`pyear`,`a`.`pmonth`");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `current_production_qty_view`");
    }
};
