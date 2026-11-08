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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `wo_progress_report`(
	P_WoNo VARCHAR(50),
	P_ItemCd VARCHAR(50),
    P_AllFlg INT
)
BEGIN
	DECLARE V_WoNo VARCHAR(50);
    DECLARE V_IdPrd INT;
    DECLARE V_SeqNo INT;
    DECLARE V_ProcCd VARCHAR(50);
    DECLARE V_OutQty FLOAT;
    DECLARE V_OnHandQty FLOAT;

    DECLARE V_First INT;
    
    DECLARE V_WoNo_PREV VARCHAR(50);
    DECLARE V_ProcCd_PREV VARCHAR(50);
    DECLARE V_BNC_UsedQty FLOAT;
    DECLARE V_BNC_OHQty FLOAT;
    DECLARE V_CRNT_OutQty FLOAT;
    
    DECLARE done INT DEFAULT 0;
    DECLARE Pcursor CURSOR FOR 
        SELECT wo_no, id_prd, seq_no, proc_cd, out_qty, onhand_qty
        FROM wo_progress_view
		WHERE
			(wo_no = P_WoNo OR IFNULL(P_WoNo, '') = '')
			AND (itm_cd = P_ItemCd OR IFNULL(P_ItemCd, '') = '')
			AND (IFNULL(emp_nm, '') != '' OR IFNULL(P_AllFlg, 1) = 1)
        ORDER BY wo_no, seq_no, proc_cd, end_time;
        
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	
    DROP TEMPORARY TABLE IF EXISTS TBL_Report;
	CREATE TEMPORARY TABLE TBL_Report (
        wo_no VARCHAR(50),
        itm_cd VARCHAR(50),
        itm_type VARCHAR(50),
        seq_no INT,
        proc_cd VARCHAR(50),
        id_prd INT,
        proc_nm VARCHAR(100),
        rmks TEXT,
        start_time DATETIME,
        end_time DATETIME,
        wo_qty FLOAT,
        cav INT,
        in_qty FLOAT,
        rwk_qty FLOAT,
        ng_qty FLOAT,
        out_qty FLOAT,
        ttl_qty FLOAT,
        ttl_qty_shoot FLOAT,
        onhand_qty FLOAT,
        mchn_cd VARCHAR(50),
        emp_nm VARCHAR(100)
    );

    INSERT INTO TBL_Report
    SELECT 
        wo_no,
        itm_cd,
        itm_type,
        seq_no,
        proc_cd,
        id_prd,
        proc_nm,
        rmks,
        start_time,
        end_time,
        wo_qty,
        cav,
        in_qty,
        rwk_qty,
        ng_qty,
        out_qty,
        ttl_qty,
        ttl_qty_shoot,
        onhand_qty,
        mchn_cd,
        emp_nm
    FROM wo_progress_view
    WHERE 
		(wo_no = P_WoNo OR IFNULL(P_WoNo, '') = '')
		AND (itm_cd = P_ItemCd OR IFNULL(P_ItemCd, '') = '')
        AND (IFNULL(emp_nm, '') != '' OR IFNULL(P_AllFlg, 1) = 1);
        
    DROP TEMPORARY TABLE IF EXISTS TBL_UsedQTY;
	CREATE TEMPORARY TABLE TBL_UsedQTY (        
		wo_no VARCHAR(50),
        proc_cd VARCHAR(50),
        used_qty FLOAT,
        onhand_qty FLOAT
    );
    
    INSERT INTO TBL_UsedQTY(wo_no, proc_cd, used_qty, onhand_qty)
	SELECT wo_no, proc_cd, sum(out_qty) - MAX(onhand_qty) AS used_qty, onhand_qty 
	FROM wo_progress_view
    WHERE 
		(wo_no = P_WoNo OR IFNULL(P_WoNo, '') = '')
		AND (itm_cd = P_ItemCd OR IFNULL(P_ItemCd, '') = '')
        AND (IFNULL(emp_nm, '') != '' OR IFNULL(P_AllFlg, 1) = 1) 
	GROUP BY wo_no, itm_cd, proc_cd, onhand_qty;
    
    SET V_WoNo_PREV = '';
    SET V_ProcCd_PREV = '';
    
    SET V_First = 1;
    OPEN Pcursor;
    read_loop: LOOP
        FETCH Pcursor INTO V_WoNo, V_IdPrd, V_SeqNo, V_ProcCd, V_OutQty, V_OnHandQty;                
        IF done THEN
            LEAVE read_loop;
        END IF;
		IF (V_WoNo = V_WoNo_PREV) AND (V_ProcCd = V_ProcCd_PREV) THEN
			IF (V_BNC_UsedQty > 0) THEN
				IF (V_BNC_UsedQty >= V_OutQty) THEN				
					UPDATE TBL_Report
					SET onhand_qty = 0
					WHERE wo_no = V_WoNo AND id_prd = V_IdPrd AND proc_cd = V_ProcCd;
					SET V_BNC_UsedQty = V_BNC_UsedQty - V_OutQty;
				ELSE					
					SET V_CRNT_OutQty = V_OutQty - V_BNC_UsedQty;
                    SET V_BNC_UsedQty = 0;
					IF (V_BNC_OHQty >= V_CRNT_OutQty) THEN
						UPDATE TBL_Report                    
						SET onhand_qty = V_CRNT_OutQty
						WHERE wo_no = V_WoNo AND id_prd = V_IdPrd AND proc_cd = V_ProcCd;
                        SET V_BNC_OHQty = V_BNC_OHQty - V_CRNT_OutQty;
					ELSE
						UPDATE TBL_Report                    
						SET onhand_qty = V_BNC_OHQty
						WHERE wo_no = V_WoNo AND id_prd = V_IdPrd AND proc_cd = V_ProcCd;
                        SET V_BNC_OHQty = 0;						
                    END IF;
				END IF;
			ELSE
				SET V_BNC_UsedQty = 0;
				SET V_CRNT_OutQty = V_OutQty - V_BNC_UsedQty;
				IF (V_BNC_OHQty >= V_CRNT_OutQty) THEN
					UPDATE TBL_Report                    
					SET onhand_qty = V_CRNT_OutQty
					WHERE wo_no = V_WoNo AND id_prd = V_IdPrd AND proc_cd = V_ProcCd;
					SET V_BNC_OHQty = V_BNC_OHQty - V_CRNT_OutQty;
				ELSE
					UPDATE TBL_Report                    
					SET onhand_qty = V_BNC_OHQty
					WHERE wo_no = V_WoNo AND id_prd = V_IdPrd AND proc_cd = V_ProcCd;
					SET V_BNC_OHQty = 0;						
				END IF;
			END IF;
		
        ELSE -- -> New WO or Process ---------------------------			
			SET V_BNC_UsedQty = 0;
            SET V_BNC_OHQty = 0;
            SELECT IFNULL(used_qty, 0), IFNULL(onhand_qty, 0)
            INTO V_BNC_UsedQty, V_BNC_OHQty
            FROM TBL_UsedQTY
            WHERE wo_no = V_WoNo AND proc_cd = V_ProcCd;
            
			IF (V_BNC_UsedQty >= V_OutQty) THEN			
				UPDATE TBL_Report
				SET onhand_qty = 0
				WHERE wo_no = V_WoNo AND id_prd = V_IdPrd AND proc_cd = V_ProcCd;
				SET V_BNC_UsedQty = V_BNC_UsedQty - V_OutQty;
			ELSE				
				SET V_CRNT_OutQty = V_OutQty - V_BNC_UsedQty;
                SET V_BNC_UsedQty = 0;
				IF (V_BNC_OHQty >= V_CRNT_OutQty) THEN
					UPDATE TBL_Report                    
					SET onhand_qty = V_CRNT_OutQty
					WHERE wo_no = V_WoNo AND id_prd = V_IdPrd AND proc_cd = V_ProcCd;
					SET V_BNC_OHQty = V_BNC_OHQty - V_CRNT_OutQty;
				ELSE
					UPDATE TBL_Report                    
					SET onhand_qty = V_BNC_OHQty
					WHERE wo_no = V_WoNo AND id_prd = V_IdPrd AND proc_cd = V_ProcCd;
					SET V_BNC_OHQty = 0;				
				END IF;                    
			END IF;
		END IF;
        SET V_WoNo_PREV = V_WoNo; 
        SET V_ProcCd_PREV = V_ProcCd;
    END LOOP;
    
	SELECT * FROM TBL_Report
    ORDER BY wo_no, seq_no, proc_cd, end_time;
    
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS wo_progress_report");
    }
};
