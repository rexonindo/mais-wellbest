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
        DB::statement("CREATE VIEW `itm_proc_view` AS select `a`.`itm_cd` AS `itm_cd`,`b`.`seq_no` AS `seq_no`,`b`.`proc_cd` AS `proc_cd`,`c`.`proc_nm` AS `proc_nm` from ((`mais_wellbest`.`itm_tbl` `a` join `mais_wellbest`.`prdroute_tbl` `b` on((`a`.`itm_type` = `b`.`itm_type`))) join `mais_wellbest`.`proc_tbl` `c` on((`b`.`proc_cd` = `c`.`proc_cd`)))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `itm_proc_view`");
    }
};
