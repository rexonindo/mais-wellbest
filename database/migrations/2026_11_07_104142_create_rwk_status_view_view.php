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
        DB::statement("CREATE VIEW `rwk_status_view` AS select `mais_wellbest`.`b`.`wo_no` AS `wo_no`,`mais_wellbest`.`b`.`itm_cd` AS `itm_cd`,`mais_wellbest`.`b`.`itm_type` AS `itm_type`,`mais_wellbest`.`b`.`seq_no` AS `seq_no`,ifnull(`mais_wellbest`.`b`.`rmks`,'-') AS `rmks`,concat(`a`.`rwk_nm`,' : ',`a`.`rwk_qty`) AS `rwk_info`,`mais_wellbest`.`b`.`proc_nm` AS `proc_nm` from (`mais_wellbest`.`prdrwk_tbl` `a` join `mais_wellbest`.`wo_progress_view` `b` on((`a`.`id_prd` = `mais_wellbest`.`b`.`id_prd`)))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `rwk_status_view`");
    }
};
