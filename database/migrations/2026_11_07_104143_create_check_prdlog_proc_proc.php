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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `check_prdlog_proc`(
	P_WoNo VARCHAR(50),
	P_ProcCd VARCHAR(50)
)
BEGIN
	DECLARE v_skip_val VARCHAR(3);
	
    DECLARE V_QtyIn FLOAT;
    DECLARE V_QtyInAfter FLOAT;
    
    SELECT IFNULL(SUM(IFNULL(in_qty, 0)),0) AS `in_qty`
    INTO V_QtyIn
	FROM wo_progress_view 
	WHERE wo_no = P_WoNo AND proc_cd = P_ProcCd;    
    
	SELECT IFNULL(SUM(IFNULL(A.in_qty, 0)),0) AS `in_qty`
    INTO V_QtyInAfter
	FROM wo_progress_view A
	INNER JOIN 
	(
		SELECT wo_no, MIN(seq_no) AS `seq_no`
		FROM wo_progress_view 
		WHERE
			(
				IFNULL(in_qty, 0) <> 0 
				AND seq_no > 
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
    
    SELECT skip_validation INTO v_skip_val 
    FROM wo_tbl
    WHERE wo_no = P_WoNo
    LIMIT 1;    
    
    IF (V_QtyIn = 0) AND (V_QtyInAfter > 0) AND (v_skip_val = 'NO') THEN
		SELECT 0 AS valid_flg;
    ELSE
		SELECT 1 AS valid_flg;
    END IF;
    
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS check_prdlog_proc");
    }
};
