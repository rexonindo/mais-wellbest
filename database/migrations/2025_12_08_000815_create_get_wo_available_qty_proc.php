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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_wo_available_qty`(
	P_WoNo VARCHAR(50),
	P_ProcCd VARCHAR(50)
)
BEGIN
	DROP TEMPORARY TABLE IF EXISTS TempAvailQty;
	CREATE TEMPORARY TABLE TempAvailQty (
        avail_qty_shoot FLOAT
    );
    INSERT INTO TempAvailQty(avail_qty_shoot)
	SELECT IFNULL(SUM(IFNULL(A.out_qty_shoot, 0)),0) AS `avail_qty_shoot`
	FROM wo_progress_shoot_view A
	INNER JOIN 
	(
		SELECT wo_no, MAX(seq_no) AS `seq_no`
		FROM wo_progress_view 
		WHERE
			(
				IFNULL(out_qty, 0) <> 0 
				AND seq_no < 
					( 
						SELECT seq_no FROM wo_progress_shoot_view 
						WHERE wo_no = P_WoNo AND proc_cd = P_ProcCd 
						ORDER BY seq_no
						LIMIT 1
					)
			)               
		GROUP BY wo_no
	) B
	ON (A.`wo_no` = B.`wo_no` AND A.`seq_no` = B.`seq_no`);
    
    INSERT INTO TempAvailQty(avail_qty_shoot)
	SELECT (out_qty + ng_qty) * -1 AS used_qty FROM wo_progress_shoot_view 
	WHERE wo_no = P_WoNo AND proc_cd = P_ProcCd;
    
    SELECT SUM(avail_qty_shoot) AS avail_qty_shoot FROM TempAvailQty;
    
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS get_wo_available_qty");
    }
};
