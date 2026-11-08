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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `rwk_detail_pivot`(
    IN p_wo_no  VARCHAR(50),
    IN p_itm_cd VARCHAR(50),    
    IN p_itm_type VARCHAR(1000),
    IN p_end_date_1  DATETIME,
    IN p_end_date_2  DATETIME    
)
BEGIN

    DECLARE v_cols  LONGTEXT;
    DECLARE v_sql   LONGTEXT;
    DECLARE v_where LONGTEXT DEFAULT '';

    SET SESSION group_concat_max_len = 100000;

    /* ---------------------------------
       FILTER : WORK ORDER
    --------------------------------- */
    IF p_wo_no IS NOT NULL AND p_wo_no <> '' THEN
        SET v_where = CONCAT(
            v_where,
            ' AND wo_no = ',
            QUOTE(p_wo_no)
        );
    END IF;


    /* ---------------------------------
       FILTER : PART NO
    --------------------------------- */
    IF p_itm_cd IS NOT NULL AND p_itm_cd <> '' THEN
        SET v_where = CONCAT(
            v_where,
            ' AND itm_cd = ',
            QUOTE(p_itm_cd)
        );
    END IF;


    /* ---------------------------------
       FILTER : ITEM TYPE - MULTIPLE
    --------------------------------- */
    IF p_itm_type IS NOT NULL AND p_itm_type <> '' THEN
        SET v_where = CONCAT(
            v_where,
            ' AND FIND_IN_SET(itm_type, ',
            QUOTE(p_itm_type),
            ') > 0'
        );
    END IF;


    /* ---------------------------------
       FILTER : END DATE
    --------------------------------- */
	IF p_end_date_1 IS NOT NULL THEN
		SET v_where = CONCAT(
			v_where,
			' AND end_time >= ',
			QUOTE(p_end_date_1)
		);
	END IF;

	IF p_end_date_2 IS NOT NULL THEN
		SET v_where = CONCAT(
			v_where,
			' AND end_time <= ',
			QUOTE(p_end_date_2)
		);
	END IF;

    /* ---------------------------------
       BUILD DYNAMIC PIVOT COLUMNS
       proc_nm + rwk_nm
    --------------------------------- */
    SET @sql_cols = CONCAT(
        'SELECT GROUP_CONCAT(
            DISTINCT
            CONCAT(
                ''SUM(
                    CASE
                        WHEN proc_nm = '''''',
                        proc_nm,
                        '''''' 
                        AND rwk_nm = '''''',
                        rwk_nm,
                        ''''''
                        THEN rwk_qty
                        ELSE 0
                    END
                ) AS `'',

                REPLACE(
                    CONCAT(proc_nm, ''_'', rwk_nm),
                    ''`'',
                    ''''
                ),

                ''`''
            )
            ORDER BY seq_no
            SEPARATOR '',''
        )
        INTO @v_cols
        FROM rwk_detail_view
        WHERE 1=1 ',
        v_where
    );

    PREPARE stmt_cols FROM @sql_cols;
    EXECUTE stmt_cols;
    DEALLOCATE PREPARE stmt_cols;

    /* ---------------------------------
       MOVE USER VARIABLE
    --------------------------------- */
    SET v_cols = @v_cols;

    IF v_cols IS NULL OR v_cols = '' THEN
        SET v_cols = '0 AS no_data';
    END IF;

    /* ---------------------------------
       FINAL QUERY
    --------------------------------- */
    SET @v_sql = CONCAT(
        'SELECT
            CAST(wo_no AS CHAR)   AS `WO NO`,
            CAST(itm_cd AS CHAR)  AS `PART NO`,
            CAST(itm_type AS CHAR) AS `TYPE`,
            CAST(rmks AS CHAR)    AS `REMARKS`,
            CAST(in_qty AS CHAR)    AS `IN QTY`,
            ',
            v_cols,
        '
        FROM rwk_detail_view
        WHERE 1=1 ',
        v_where,
        '
        GROUP BY
            wo_no,
            itm_cd,
            itm_type,
            rmks,
            in_qty
        ORDER BY
            wo_no'
    );

    /* DEBUG */
    -- SELECT @v_sql;

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
        DB::unprepared("DROP PROCEDURE IF EXISTS rwk_detail_pivot");
    }
};
