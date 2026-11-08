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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `wo_balance_report_test`(
	P_WoNo VARCHAR(50),
	P_ItemCd VARCHAR(50),
    P_AllFlg INT
)
BEGIN
	DECLARE V_WoNo VARCHAR(50);
    DECLARE V_EndTime DATE;
    DECLARE V_SeqNo INT;
    DECLARE V_ProcCd VARCHAR(50);
    DECLARE V_InQty FLOAT;
    DECLARE V_OutQty FLOAT;
    DECLARE V_NgQty FLOAT;
    DECLARE V_RwkQty FLOAT;
    
    DECLARE V_TotalINQty FLOAT;
    DECLARE V_TotalOKQty FLOAT;
    
    DECLARE V_First INT;
    
    DECLARE V_WoNo_PREV VARCHAR(50);
    DECLARE V_ProcCd_PREV VARCHAR(50);
    DECLARE V_EndTime_PREV DATE;
    DECLARE V_InQty_PREV FLOAT;
    
    DECLARE V_BNC_Qty FLOAT;
    
    DECLARE done INT DEFAULT 0;
    DECLARE Pcursor CURSOR FOR 
        SELECT wo_no, seq_no, proc_cd, CAST(end_time AS DATE) AS end_time, SUM(in_qty) AS in_qty, SUM(out_qty) AS out_qty, SUM(ng_qty) AS ng_qty, SUM(rwk_qty) AS rwk_qty
        FROM wo_progress_view
		WHERE
			(wo_no = P_WoNo OR IFNULL(P_WoNo, '') = '')
			AND (itm_cd = P_ItemCd OR IFNULL(P_ItemCd, '') = '')
			AND (IFNULL(emp_nm, '') != '' OR IFNULL(P_AllFlg, 1) = 1)
		GROUP BY wo_no, seq_no, proc_cd, CAST(end_time AS DATE)
        ORDER BY wo_no, seq_no, proc_cd, end_time;
        
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
    
    DROP TEMPORARY TABLE IF EXISTS TEMP_TBL_WO_Balance_Report;
	CREATE TEMPORARY TABLE TEMP_TBL_WO_Balance_Report (
        wo_no VARCHAR(50),
        itm_cd VARCHAR(50),
        itm_type VARCHAR(50),
        seq_no INT,
        proc_cd VARCHAR(50),
        proc_nm VARCHAR(100),
        end_time DATE,
        wo_qty FLOAT,
        in_qty FLOAT,
        out_qty FLOAT,
        rwk_qty FLOAT,
        ng_qty FLOAT,        
        bnc_qty FLOAT
    );

    INSERT INTO TEMP_TBL_WO_Balance_Report
    SELECT 
        wo_no,
        itm_cd,
        itm_type,
        seq_no,
        proc_cd,
        proc_nm,
        CAST(end_time AS DATE) AS end_time,
        wo_qty,
        in_qty,
        out_qty,
        rwk_qty,
        ng_qty,        
        0 AS bnc_qty
    FROM wo_progress_view
    WHERE 
		(wo_no = P_WoNo OR IFNULL(P_WoNo, '') = '')
		AND (itm_cd = P_ItemCd OR IFNULL(P_ItemCd, '') = '')
        AND (IFNULL(emp_nm, '') != '' OR IFNULL(P_AllFlg, 1) = 1)
	ORDER BY end_time;
        
    SET V_WoNo_PREV = '';
    SET V_ProcCd_PREV = '';
    SET V_EndTime_PREV = NULL;
    SET V_InQty_PREV = 0;
    
    -- SET V_First = 1;
    OPEN Pcursor;
    read_loop: LOOP
		FETCH Pcursor INTO V_WoNo, V_SeqNo, V_ProcCd, V_EndTime, V_InQty, V_OutQty, V_NgQty, V_RwkQty;                
        IF done THEN
            LEAVE read_loop;
        END IF;
        
		IF (V_WoNo = V_WoNo_PREV) AND (V_ProcCd = V_ProcCd_PREV) AND (V_EndTime = V_EndTime_PREV) THEN
			UPDATE TEMP_TBL_WO_Balance_Report
			SET in_qty = V_TotalINQty
			WHERE wo_no = V_WoNo AND proc_cd = V_ProcCd AND end_time = V_EndTime ;

			SET V_TotalOKQty = V_TotalOKQty + V_OutQty;
			IF (V_BNC_Qty > 0) THEN
				SET V_BNC_Qty = V_BNC_Qty - V_OutQty - V_NgQty;
				UPDATE TEMP_TBL_WO_Balance_Report
				SET bnc_qty = V_BNC_Qty
				WHERE wo_no = V_WoNo AND end_time = V_EndTime AND proc_cd = V_ProcCd;
			END IF;
			
        ELSE -- -> New WO or Process ---------------------------
			IF (V_WoNo != V_WoNo_PREV) THEN
				SET V_TotalINQty = V_InQty;
            ELSE
				SET V_TotalINQty = V_InQty_PREV;
            END IF;
			
            UPDATE TEMP_TBL_WO_Balance_Report
			SET in_qty = V_TotalINQty
			WHERE wo_no = V_WoNo AND end_time = V_EndTime AND proc_cd = V_ProcCd;
            
			SET V_TotalOKQty = V_OutQty;
            
			SET V_BNC_Qty = V_TotalINQty - V_OutQty - V_NgQty;
			UPDATE TEMP_TBL_WO_Balance_Report
			SET bnc_qty = V_BNC_Qty
			WHERE wo_no = V_WoNo AND end_time = V_EndTime AND proc_cd = V_ProcCd;
		END IF;
        
        SET V_InQty_PREV = V_InQty;
        SET V_WoNo_PREV = V_WoNo; 
        SET V_ProcCd_PREV = V_ProcCd;
        SET V_EndTime_PREV = V_EndTime;
        
    END LOOP;
    
	-- SELECT * FROM TBL_Report
    -- ORDER BY wo_no, seq_no, proc_cd, end_time;
    
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS wo_balance_report_test");
    }
};
