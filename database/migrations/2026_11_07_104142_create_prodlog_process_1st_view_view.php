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
        DB::statement("CREATE VIEW `prodlog_process_1st_view` AS select `mais_wellbest`.`wo_progress_view`.`wo_no` AS `wo_no`,`mais_wellbest`.`wo_progress_view`.`itm_cd` AS `itm_cd`,`mais_wellbest`.`wo_progress_view`.`itm_type` AS `itm_type`,`mais_wellbest`.`wo_progress_view`.`itm_grp` AS `itm_grp`,`mais_wellbest`.`wo_progress_view`.`seq_no` AS `seq_no`,`mais_wellbest`.`wo_progress_view`.`proc_cd` AS `proc_cd`,`mais_wellbest`.`wo_progress_view`.`proc_nm` AS `proc_nm`,min(`mais_wellbest`.`wo_progress_view`.`end_time`) AS `end_time`,sum(`mais_wellbest`.`wo_progress_view`.`in_qty`) AS `in_qty`,sum(`mais_wellbest`.`wo_progress_view`.`out_qty`) AS `out_qty` from `mais_wellbest`.`wo_progress_view` where (`mais_wellbest`.`wo_progress_view`.`proc_cd` in ('P005','P010')) group by `mais_wellbest`.`wo_progress_view`.`wo_no`,`mais_wellbest`.`wo_progress_view`.`itm_cd`,`mais_wellbest`.`wo_progress_view`.`itm_type`,`mais_wellbest`.`wo_progress_view`.`itm_grp`,`mais_wellbest`.`wo_progress_view`.`seq_no`,`mais_wellbest`.`wo_progress_view`.`proc_cd`,`mais_wellbest`.`wo_progress_view`.`proc_nm` having ((sum(`mais_wellbest`.`wo_progress_view`.`in_qty`) > 0) and (sum(`mais_wellbest`.`wo_progress_view`.`out_qty`) > 0))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `prodlog_process_1st_view`");
    }
};
