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
        DB::statement("CREATE VIEW `prdlog_max_seqno_view` AS select `mais_wellbest`.`prdlog_tbl`.`wo_no` AS `wo_no`,max(`mais_wellbest`.`prdlog_tbl`.`seq_no`) AS `seq_no` from `mais_wellbest`.`prdlog_tbl` group by `mais_wellbest`.`prdlog_tbl`.`wo_no`");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `prdlog_max_seqno_view`");
    }
};
