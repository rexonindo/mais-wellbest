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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `ng_status_pivot_by_process`(
    IN p_wo_no  VARCHAR(50),
    IN p_itm_cd VARCHAR(50)
)
BEGIN
    DECLARE v_cols  LONGTEXT;
    DECLARE v_sql   LONGTEXT;
    DECLARE v_where LONGTEXT DEFAULT '';

    SET SESSION group_concat_max_len = 100000;

    /* -------------------------------
       Build WHERE (NULL / '' = ALL)
       ------------------------------- */
    IF p_wo_no IS NOT NULL AND p_wo_no <> '' THEN
        SET v_where = CONCAT(v_where, ' AND wo_no = ', QUOTE(p_wo_no));
    END IF;

    IF p_itm_cd IS NOT NULL AND p_itm_cd <> '' THEN
        SET v_where = CONCAT(v_where, ' AND itm_cd = ', QUOTE(p_itm_cd));
    END IF;

    /* -------------------------------
       Build pivot columns (FILTERED)
       ------------------------------- */
	SET @sql_cols = CONCAT(
		'SELECT GROUP_CONCAT(
			DISTINCT
			CONCAT(
				''GROUP_CONCAT(
					CASE WHEN proc_nm = '''''',
					proc_nm,
					'''''' THEN ng_info
					END SEPARATOR '''', ''''
				) AS `'',

				REPLACE(proc_nm, ''`'', ''''),

				''`''
			)
			ORDER BY seq_no
			SEPARATOR '',''
		)
		INTO @v_cols
		FROM ng_status_view
		WHERE 1=1 ', v_where
	);

    PREPARE stmt_cols FROM @sql_cols;
    EXECUTE stmt_cols;
    DEALLOCATE PREPARE stmt_cols;

    /* move user variable → local variable */
    SET v_cols = @v_cols;

    /* safety: no process */
    IF v_cols IS NULL OR v_cols = '' THEN
        SET v_cols = 'NULL AS no_process';
    END IF;

    /* -------------------------------
       Final pivot query
       ------------------------------- */
    SET @v_sql = CONCAT(
        'SELECT 
            CAST(wo_no AS CHAR)  AS `WO NO`,
            CAST(itm_cd AS CHAR) AS `PART NO`,
            CAST(itm_type AS CHAR) AS `TYPE`,
            CAST(rmks AS CHAR) AS `REMARKS NG`,
            ', v_cols, '
         FROM ng_status_view
         WHERE 1=1 ', v_where, '
         GROUP BY wo_no, itm_cd, itm_type, rmks
         ORDER BY wo_no'
    );

    PREPARE stmt FROM @v_sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS ng_status_pivot_by_process");
    }
};
