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
        DB::statement("CREATE VIEW `iv_status_view` AS select `mais_wellbest`.`ivmov_tbl`.`itm_cd` AS `itm_cd`,`mais_wellbest`.`ivmov_tbl`.`proc_cd` AS `proc_cd`,`mais_wellbest`.`ivmov_tbl`.`wip_cd` AS `wip_cd`,((sum((case when (`mais_wellbest`.`ivmov_tbl`.`mov_type` in ('IN','REWORK_IN')) then `mais_wellbest`.`ivmov_tbl`.`qty` else 0 end)) - sum((case when (`mais_wellbest`.`ivmov_tbl`.`mov_type` in ('OUT','SCRAP','REWORK_OUT')) then `mais_wellbest`.`ivmov_tbl`.`qty` else 0 end))) + sum((case when (`mais_wellbest`.`ivmov_tbl`.`mov_type` = 'SO') then `mais_wellbest`.`ivmov_tbl`.`qty` else 0 end))) AS `qty` from `mais_wellbest`.`ivmov_tbl` group by `mais_wellbest`.`ivmov_tbl`.`itm_cd`,`mais_wellbest`.`ivmov_tbl`.`proc_cd`,`mais_wellbest`.`ivmov_tbl`.`wip_cd`");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `iv_status_view`");
    }
};
