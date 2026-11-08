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
	DECLARE v_skip_val VARCHAR(3);
    
	DROP TEMPORARY TABLE IF EXISTS TempAvailQty;
	CREATE TEMPORARY TABLE TempAvailQty (
        avail_qty_pcs FLOAT
    );    
    
	IF NOT EXISTS 
	(
        SELECT 1
		FROM wo_progress_view A
		INNER JOIN
		(
			SELECT seq_no FROM wo_progress_view 
			WHERE wo_no = P_WoNo AND proc_cd = P_ProcCd  
		) B
		ON A.wo_no AND A.seq_no < B.seq_no
	) THEN 		
    
        INSERT INTO TempAvailQty(avail_qty_pcs)
		SELECT plan_qty 
		FROM wo_tbl
        WHERE wo_no = P_WoNo;
        
    ELSE

		INSERT INTO TempAvailQty(avail_qty_pcs)
		SELECT IFNULL(SUM(IFNULL(A.out_qty, 0)),0) AS `avail_qty_shoot`
		FROM wo_progress_view A
		INNER JOIN 
		(
			SELECT wo_no, MAX(seq_no) AS `seq_no`
			FROM wo_progress_view 
			WHERE
				(
					IFNULL(out_qty, 0)  <> 0 
					AND seq_no < 
						( 
							SELECT seq_no FROM wo_progress_view 
							WHERE wo_no = P_WoNo AND proc_cd = P_ProcCd 
							ORDER BY seq_no
							LIMIT 1
						)
				)               
			GROUP BY wo_no
		) B
		ON (A.`wo_no` = B.`wo_no` AND A.`seq_no` = B.`seq_no`)
		WHERE A.wo_no = P_WoNo;

    END IF;

    SELECT skip_validation INTO v_skip_val 
    FROM wo_tbl
    WHERE wo_no = P_WoNo
    LIMIT 1;

    IF v_skip_val = 'NO' THEN
		INSERT INTO TempAvailQty(avail_qty_pcs)
		SELECT (SUM(IFNULL(out_qty,0) + IFNULL(ng_qty,0))) * -1 AS `in_qty`
		FROM wo_progress_view 
		WHERE
			(
				IFNULL(in_qty, 0)  <> 0 
				AND seq_no = 
					( 
						SELECT seq_no FROM wo_progress_view 
						WHERE wo_no = P_WoNo AND proc_cd = P_ProcCd 
						ORDER BY seq_no
						LIMIT 1
					)
			)      
		AND wo_no = P_WoNo;
	END IF;
	
    SELECT SUM(avail_qty_pcs) AS avail_qty_pcs FROM TempAvailQty;
    
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
