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
        DB::statement("CREATE VIEW `current_production_qty_view` AS select `a`.`pyear` AS `pyear`,`a`.`pmonth` AS `pmonth`,sum(ifnull(`a`.`plan_qty`,0)) AS `ttl_plan_qty`,sum(ifnull(`b`.`out_qty`,0)) AS `ttl_out_qty`,sum((ifnull(`a`.`plan_qty`,0) - ifnull(`b`.`out_qty`,0))) AS `ttl_os_qty` from ((select year(`a`.`req_dt`) AS `pyear`,month(`a`.`req_dt`) AS `pmonth`,`a`.`wo_no` AS `wo_no`,sum(`a`.`plan_qty`) AS `plan_qty` from `mais_wellbest`.`wo_status_view` `a` group by year(`a`.`req_dt`),month(`a`.`req_dt`),`a`.`wo_no`) `a` left join (select year(`a`.`end_time`) AS `pyear`,month(`a`.`end_time`) AS `pmonth`,`a`.`wo_no` AS `wo_no`,sum(`a`.`out_qty`) AS `out_qty` from (`mais_wellbest`.`wo_status_view` `a` join (select `mais_wellbest`.`prdroute_tbl`.`itm_type` AS `itm_type`,max(`mais_wellbest`.`prdroute_tbl`.`seq_no`) AS `seq_no` from `mais_wellbest`.`prdroute_tbl` group by `mais_wellbest`.`prdroute_tbl`.`itm_type`) `b` on(((`a`.`itm_type` = `b`.`itm_type`) and (`a`.`seq_no` = `b`.`seq_no`)))) group by year(`a`.`end_time`),month(`a`.`end_time`),`a`.`wo_no`) `b` on(((`a`.`pyear` = `b`.`pyear`) and (`a`.`pmonth` = `b`.`pmonth`) and (`a`.`wo_no` = `b`.`wo_no`)))) where ((`a`.`pyear` = year(now())) and (`a`.`pmonth` = month(now()))) group by `a`.`pyear`,`a`.`pmonth`");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `current_production_qty_view`");
    }
};
