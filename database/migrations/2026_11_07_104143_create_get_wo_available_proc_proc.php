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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_wo_available_proc`(
    P_WoNo VARCHAR(50)
)
BEGIN
    DECLARE v_done    INT DEFAULT 0;
    DECLARE v_proc_cd VARCHAR(50);
    DECLARE v_qty     FLOAT;

    DECLARE cur CURSOR FOR
        SELECT proc_cd
        FROM wo_proc_tbl
        WHERE wo_no = P_WoNo
        ORDER BY seq_no;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = 1;

    DROP TEMPORARY TABLE IF EXISTS TempAvailProc;
    CREATE TEMPORARY TABLE TempAvailProc (
        proc_cd       VARCHAR(50),
        avail_qty_pcs FLOAT
    );

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO v_proc_cd;
        IF v_done = 1 THEN
            LEAVE read_loop;
        END IF;

        SET v_qty = NULL;
        CALL get_wo_available_qty_calc(P_WoNo, v_proc_cd, v_qty);

        IF IFNULL(v_qty, 0) > 0 THEN
            INSERT INTO TempAvailProc(proc_cd, avail_qty_pcs)
            VALUES (v_proc_cd, v_qty);
        END IF;
    END LOOP;

    CLOSE cur;

    SELECT A.proc_cd, B.proc_nm, A.avail_qty_pcs
    FROM TempAvailProc A
    LEFT JOIN proc_tbl B
    ON A.proc_cd = B.proc_cd;

    DROP TEMPORARY TABLE IF EXISTS TempAvailProc;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS get_wo_available_proc");
    }
};
