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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `wo_balance_report_v2`(
    IN P_WoNo VARCHAR(50),
    IN P_ItemCd VARCHAR(50),
    IN P_AllFlg INT
)
BEGIN

    -- ----------------------------------------------------------
    -- Variables
    -- ----------------------------------------------------------
    DECLARE done INT DEFAULT 0;

    DECLARE V_WoNo VARCHAR(50);
    DECLARE V_ItemCd VARCHAR(50);
    DECLARE V_ItmType VARCHAR(50);

    DECLARE V_SeqNo INT;
    DECLARE V_ProcCd VARCHAR(50);
    DECLARE V_ProcNm VARCHAR(100);

    DECLARE V_IDPrd BIGINT;

    DECLARE V_EndTime DATETIME;

    DECLARE V_WOQty DECIMAL(18,2);

    DECLARE V_Cav INT;

    DECLARE V_InQty DECIMAL(18,2);
    DECLARE V_OutQty DECIMAL(18,2);
    DECLARE V_RWKQty DECIMAL(18,2);
    DECLARE V_NGQty DECIMAL(18,2);

    DECLARE V_MchnCd VARCHAR(50);
    DECLARE V_EmpNm VARCHAR(100);

    DECLARE V_NewInQty DECIMAL(18,2);
    DECLARE V_BalanceQty DECIMAL(18,2);

	-- ----------------------------------------------------------
	-- Cursor Variables
	-- ----------------------------------------------------------
	DECLARE V_PrevWO VARCHAR(50) DEFAULT '';
	DECLARE V_PrevSeq INT DEFAULT 0;

    -- ----------------------------------------------------------
    -- Cursor
    -- ----------------------------------------------------------
    DECLARE cur CURSOR FOR

    SELECT

        wo_no,
        itm_cd,
        itm_type,
        seq_no,
        proc_cd,
        proc_nm,
        id_prd,
        end_time,
        wo_qty,
        cav,
        in_qty,
        out_qty,
        rwk_qty,
        ng_qty,
        mchn_cd,
        emp_nm

    FROM wo_progress_view

    WHERE

        (wo_no=P_WoNo OR IFNULL(P_WoNo,'')='')

    AND

        (itm_cd=P_ItemCd OR IFNULL(P_ItemCd,'')='')

    AND

        (IFNULL(emp_nm,'')<>'' OR P_AllFlg=1)

    ORDER BY

        wo_no,
        seq_no,
        end_time,
        id_prd;

    -- ----------------------------------------------------------
    -- Handler
    -- ----------------------------------------------------------
    DECLARE CONTINUE HANDLER FOR NOT FOUND
    SET done=1;

    -- ----------------------------------------------------------
    -- Temporary Report Table
    -- ----------------------------------------------------------
    DROP TEMPORARY TABLE IF EXISTS TEMP_TBL_WO_BALANCE;

    CREATE TEMPORARY TABLE TEMP_TBL_WO_BALANCE
    (
        row_id BIGINT AUTO_INCREMENT PRIMARY KEY,

        wo_no VARCHAR(50),

        itm_cd VARCHAR(50),

        itm_type VARCHAR(50),

        seq_no INT,

        proc_cd VARCHAR(50),

        proc_nm VARCHAR(100),

        id_prd BIGINT,

        end_time DATETIME,

        wo_qty DECIMAL(18,2),

        cav INT,

        in_qty DECIMAL(18,2),

        out_qty DECIMAL(18,2),

        rwk_qty DECIMAL(18,2),

        ng_qty DECIMAL(18,2),

        bnc_qty DECIMAL(18,2),

        mchn_cd VARCHAR(50),

        emp_nm VARCHAR(100)
    );

    -- ----------------------------------------------------------
    -- Running Total Table
    -- ----------------------------------------------------------
    DROP TEMPORARY TABLE IF EXISTS TEMP_PROCESS_TOTAL;

    CREATE TEMPORARY TABLE TEMP_PROCESS_TOTAL
    (
        wo_no VARCHAR(50),

        seq_no INT,

        total_out DECIMAL(18,2) DEFAULT 0,

        total_ng DECIMAL(18,2) DEFAULT 0,

        total_rwk DECIMAL(18,2) DEFAULT 0,

        PRIMARY KEY
        (
            wo_no,
            seq_no
        )
    );

    -- ----------------------------------------------------------
    -- Initialize Running Total
    -- ----------------------------------------------------------
    INSERT INTO TEMP_PROCESS_TOTAL
    (
        wo_no,
        seq_no,
        total_out,
        total_ng,
        total_rwk
    )
    SELECT
        wo_no,
        seq_no,
        0,
        0,
        0
    FROM wo_progress_view
    WHERE
        (wo_no=P_WoNo OR IFNULL(P_WoNo,'')='')
    AND (itm_cd=P_ItemCd OR IFNULL(P_ItemCd,'')='')
    GROUP BY
        wo_no,
        seq_no;

    /* ============================
       PART 2 STARTS HERE
       ============================ */



	OPEN cur;

	read_loop: LOOP

		FETCH cur INTO

			V_WoNo,
			V_ItemCd,
			V_ItmType,
			V_SeqNo,
			V_ProcCd,
			V_ProcNm,
			V_IDPrd,
			V_EndTime,
			V_WOQty,
			V_Cav,
			V_InQty,
			V_OutQty,
			V_RWKQty,
			V_NGQty,
			V_MchnCd,
			V_EmpNm;

		IF done THEN
			LEAVE read_loop;
		END IF;

		-- --------------------------------------------------
		-- Get Previous Process Total
		-- --------------------------------------------------
		IF V_SeqNo = 1 THEN

			SET V_NewInQty = V_InQty;

		ELSE

			SELECT IFNULL(total_out,0)

			INTO V_NewInQty

			FROM TEMP_PROCESS_TOTAL

			WHERE wo_no = V_WoNo
			  AND seq_no = V_SeqNo-1;

		END IF;

		-- --------------------------------------------------
		-- Running Balance
		-- --------------------------------------------------
		SET V_BalanceQty =
			V_NewInQty
			- V_OutQty
			- V_NGQty;

		-- --------------------------------------------------
		-- Save Detail
		-- --------------------------------------------------
		INSERT INTO TEMP_TBL_WO_BALANCE
		(
			wo_no,
			itm_cd,
			itm_type,
			seq_no,
			proc_cd,
			proc_nm,
			id_prd,
			end_time,
			wo_qty,
			cav,
			in_qty,
			out_qty,
			rwk_qty,
			ng_qty,
			bnc_qty,
			mchn_cd,
			emp_nm
		)
		VALUES
		(
			V_WoNo,
			V_ItemCd,
			V_ItmType,
			V_SeqNo,
			V_ProcCd,
			V_ProcNm,
			V_IDPrd,
			V_EndTime,
			V_WOQty,
			V_Cav,
			V_NewInQty,
			V_OutQty,
			V_RWKQty,
			V_NGQty,
			V_BalanceQty,
			V_MchnCd,
			V_EmpNm
		);

		-- --------------------------------------------------
		-- Update Running Total
		-- --------------------------------------------------
		UPDATE TEMP_PROCESS_TOTAL

		   SET total_out = total_out + V_OutQty,
			   total_ng  = total_ng  + V_NGQty,
			   total_rwk = total_rwk + V_RWKQty

		 WHERE wo_no = V_WoNo
		   AND seq_no = V_SeqNo;

	END LOOP;

	CLOSE cur;

	SELECT *
	FROM TEMP_TBL_WO_BALANCE
	ORDER BY
		wo_no,
		seq_no,
		end_time,
		id_prd;

END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS wo_balance_report_v2");
    }
};
