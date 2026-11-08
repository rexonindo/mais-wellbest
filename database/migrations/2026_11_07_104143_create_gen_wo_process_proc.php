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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `gen_wo_process`(
	P_WoNo VARCHAR(50),
	P_UserName VARCHAR(50)
)
BEGIN
    DECLARE V_SecNo INT;
    DECLARE V_WoNo VARCHAR(50);
    DECLARE V_PlanQty FLOAT;
    DECLARE V_PlanQtyPnl FLOAT;
    DECLARE V_ProcCd VARCHAR(50);
    DECLARE V_TmpCavItem INT DEFAULT 0;
    DECLARE V_TmpCav INT DEFAULT 0;
    DECLARE V_RealCav INT DEFAULT 0;
    DECLARE V_RealCavPrev INT DEFAULT 0;
    DECLARE V_ShootQty FLOAT;
    DECLARE V_First INT;
	
    DECLARE done INT DEFAULT 0;
    DECLARE Pcursor CURSOR FOR 
        SELECT A.wo_no, A.plan_qty, A.plan_qty_pnl, B.cavity, C.seq_no, C.proc_cd
        FROM wo_tbl A
        INNER JOIN itm_tbl B ON A.itm_cd = B.itm_cd        
        INNER JOIN prdroute_tbl C ON B.itm_type = C.itm_type
        WHERE A.wo_no = P_WoNo
        ORDER BY C.seq_no;
        
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    DELETE FROM wo_proc_tbl WHERE wo_no = P_WoNo;
	
    SET V_First = 1;
    OPEN Pcursor;
    read_loop: LOOP
        FETCH Pcursor INTO V_WoNo, V_PlanQty, V_PlanQtyPnl, V_TmpCavItem, V_SecNo, V_ProcCd;                
        IF done THEN
            LEAVE read_loop;
        END IF;
        
		IF EXISTS 
		(
			SELECT 1
			FROM wo_tbl A
			INNER JOIN toolcav_tbl B
				ON A.itm_cd = B.itm_cd AND A.tool_cd = B.tool_cd
			WHERE A.wo_no = V_WoNo AND B.proc_cd = V_ProcCd
		) THEN        
			SELECT IFNULL(B.cav, 0)
			INTO V_TmpCav
			FROM wo_tbl A
			INNER JOIN toolcav_tbl B
				ON A.itm_cd = B.itm_cd AND A.tool_cd = B.tool_cd
			WHERE A.wo_no = V_WoNo AND B.proc_cd = V_ProcCd
			LIMIT 1;           
	
        END IF;
        
			IF V_First = 1 THEN
				SET V_ShootQty = IFNULL(V_PlanQty, 0) / IFNULL(V_TmpCavItem, 1);
				SET V_TmpCav = CEIL(IFNULL(V_PlanQty, 0) / IFNULL(V_ShootQty, 1));
				SET V_First = 0;
			ELSE        
				IF V_TmpCav = 0 THEN
					SET V_ShootQty = 0;
				ELSE
					SET V_ShootQty = CEIL(IFNULL(V_PlanQty, 0) / IFNULL(V_TmpCav, 1));
				END IF;
			END IF;
			
			INSERT INTO wo_proc_tbl
			(wo_no, seq_no, proc_cd, cav, shoot_qty, created_at, updated_at, created_by, updated_by)
			VALUES
			(V_WoNo, V_SecNo, IFNULL(V_ProcCd, 1), V_TmpCav, V_ShootQty, NOW(), NOW(), P_UserName, P_UserName);
			SET V_RealCavPrev = V_TmpCav;            
        
    END LOOP;
    
	SELECT * FROM wo_proc_tbl;
    CLOSE Pcursor;
    
    UPDATE wo_tbl A
	INNER JOIN
	(
		SELECT wo_no, cav FROM wo_proc_tbl A
		INNER JOIN proc_tbl B ON A.proc_cd = B.proc_cd  
		WHERE wo_no = P_WoNo
		AND proc_nm LIKE '%RAW%'
		LIMIT 1
	) C
	ON A.wo_no = C.wo_no
	SET A.plan_qty_raw = IFNULL(A.plan_qty, 0) / IFNULL(C.cav, 1);
    
    UPDATE wo_tbl A
	INNER JOIN
	(
		SELECT wo_no, cav FROM wo_proc_tbl A
		INNER JOIN proc_tbl B ON A.proc_cd = B.proc_cd  
		WHERE wo_no = P_WoNo
		AND proc_nm LIKE '%CUTTING%'
		LIMIT 1
	) C
	ON A.wo_no = C.wo_no
	SET A.plan_qty_pnl = IFNULL(A.plan_qty, 0) / IFNULL(C.cav, 1);    
    
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS gen_wo_process");
    }
};
