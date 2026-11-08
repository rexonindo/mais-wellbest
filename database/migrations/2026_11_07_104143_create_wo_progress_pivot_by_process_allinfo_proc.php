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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `wo_progress_pivot_by_process_allinfo`(
	IN p_wo_no VARCHAR(50),
    IN p_itm_cd VARCHAR(50)
)
proc_end: BEGIN
    DECLARE v_row_count INT DEFAULT 0;

    -- ---------- Result-A: raw rows ----------
    DROP TEMPORARY TABLE IF EXISTS tmp_a;
    CREATE TEMPORARY TABLE tmp_a (
        rn        INT AUTO_INCREMENT PRIMARY KEY,
        wo_no		VARCHAR(50),
        end_time	DATE,
        seq_no		INT,
        proc_nm		VARCHAR(100),
        itm_cd		VARCHAR(50),
        itm_type	VARCHAR(50),
        wo_qty		FLOAT,
        cav			INT,
        id_prd    	INT,
        in_qty    	FLOAT,
        out_qty   	FLOAT,
        ng_qty    	FLOAT,
        rwk_qty   	FLOAT
    );

    INSERT INTO tmp_a (wo_no, end_time, seq_no, proc_nm, itm_cd, itm_type, wo_qty, cav, id_prd, in_qty, out_qty, ng_qty, rwk_qty)
    SELECT wo_no, CAST(end_time AS DATE), seq_no, proc_nm, itm_cd, itm_type, wo_qty, cav, id_prd, in_qty, out_qty, ng_qty, rwk_qty
    FROM wo_progress_view
    WHERE IFNULL(emp_nm, '') <> ''
      AND (IFNULL(p_wo_no,'') = '' OR wo_no = p_wo_no)
      AND (IFNULL(p_itm_cd,'') = '' OR itm_cd = p_itm_cd)
    ORDER BY wo_no, CAST(end_time AS DATE), seq_no, id_prd;

	SELECT COUNT(*) INTO v_row_count FROM tmp_a;

	IF v_row_count = 0 THEN
		SELECT
			CAST(NULL AS CHAR(50)) AS `WO NO`,
			CAST(NULL AS CHAR(50)) AS `PART NO`,
			CAST(NULL AS CHAR(50)) AS `TYPE`,
			CAST(NULL AS DATETIME) AS `END DATE`,
			CAST(NULL AS DECIMAL(10,2)) AS `WO QTY`
		WHERE 1 = 0;
		LEAVE proc_end;
	END IF;

    -- lowest seq_no per wo_no = the first process (entry point, e.g. WAREHOUSE)
    DROP TEMPORARY TABLE IF EXISTS tmp_min_seq;
    CREATE TEMPORARY TABLE tmp_min_seq (
        wo_no   VARCHAR(50) PRIMARY KEY,
        min_seq INT
    );
    INSERT INTO tmp_min_seq (wo_no, min_seq)
    SELECT wo_no, MIN(seq_no) FROM tmp_a GROUP BY wo_no;

    -- ---------- tmp_station_seq: master list of every (wo_no, seq_no) station that
    --            legitimately exists, PLUS the true previous/next seq_no in that WO's
    --            routing order (via LAG/LEAD) — this is what makes the logic below
    --            gap-tolerant, since seq_no is NOT guaranteed to be contiguous. ----------
    DROP TEMPORARY TABLE IF EXISTS tmp_station_seq;
    CREATE TEMPORARY TABLE tmp_station_seq (
        wo_no     VARCHAR(50),
        seq_no    INT,
        proc_nm   VARCHAR(100),
        itm_cd    VARCHAR(50),
        itm_type  VARCHAR(50),
        wo_qty    FLOAT,
        prev_seq  INT,
        next_seq  INT,
        PRIMARY KEY (wo_no, seq_no)
    );
    INSERT INTO tmp_station_seq (wo_no, seq_no, proc_nm, itm_cd, itm_type, wo_qty, prev_seq, next_seq)
    SELECT
        wo_no, seq_no, proc_nm, itm_cd, itm_type, wo_qty,
        LAG(seq_no)  OVER (PARTITION BY wo_no ORDER BY seq_no) AS prev_seq,
        LEAD(seq_no) OVER (PARTITION BY wo_no ORDER BY seq_no) AS next_seq
    FROM (
        SELECT
            wo_no, seq_no,
            MIN(proc_nm)  AS proc_nm,
            MIN(itm_cd)   AS itm_cd,
            MIN(itm_type) AS itm_type,
            MIN(wo_qty)   AS wo_qty
        FROM tmp_a
        GROUP BY wo_no, seq_no
    ) s;

    -- ---------- tmp_agg: one row per (wo_no, end_time, seq_no) where the station
    --            itself actually logged production (real activity only) ----------
    DROP TEMPORARY TABLE IF EXISTS tmp_agg;
    CREATE TEMPORARY TABLE tmp_agg (
        wo_no     VARCHAR(50),
        end_time  DATE,
        seq_no    INT,
        proc_nm   VARCHAR(100),
        itm_cd    VARCHAR(50),
        itm_type  VARCHAR(50),
        wo_qty    FLOAT,
        out_qty   FLOAT,
        ng_qty    FLOAT,
        rwk_qty   FLOAT,
        PRIMARY KEY (wo_no, seq_no, end_time)
    );

    INSERT INTO tmp_agg (wo_no, end_time, seq_no, proc_nm, itm_cd, itm_type, wo_qty, out_qty, ng_qty, rwk_qty)
    SELECT
        wo_no, end_time, seq_no,
        MIN(proc_nm), MIN(itm_cd), MIN(itm_type), MIN(wo_qty),
        SUM(out_qty), SUM(ng_qty), SUM(rwk_qty)
    FROM tmp_a
    GROUP BY wo_no, end_time, seq_no;

    -- copies to avoid referencing the same temp table twice in one query (error 1137)
    DROP TEMPORARY TABLE IF EXISTS tmp_agg_check;
    CREATE TEMPORARY TABLE tmp_agg_check AS
    SELECT wo_no, seq_no, end_time FROM tmp_agg;

    DROP TEMPORARY TABLE IF EXISTS tmp_station_seq_lookup;
    CREATE TEMPORARY TABLE tmp_station_seq_lookup AS
    SELECT wo_no, seq_no, next_seq FROM tmp_station_seq;

    -- ---------- tmp_phantom: pass-through rows. Whenever a station produced OUT on a
    --            date, but its true next station (per tmp_station_seq, not a literal
    --            seq_no+1) has no row of its own for that same date, create an empty
    --            placeholder row so the next station can display the incoming
    --            material (IN) and running balance (SAL). ----------
    DROP TEMPORARY TABLE IF EXISTS tmp_phantom;
    CREATE TEMPORARY TABLE tmp_phantom AS
    SELECT
        up.wo_no, up.end_time, ds.seq_no, ds.proc_nm, ds.itm_cd, ds.itm_type, ds.wo_qty,
        CAST(NULL AS FLOAT) AS out_qty,
        CAST(NULL AS FLOAT) AS ng_qty,
        CAST(NULL AS FLOAT) AS rwk_qty
    FROM tmp_agg up
    JOIN tmp_station_seq_lookup nx
      ON nx.wo_no = up.wo_no AND nx.seq_no = up.seq_no
    JOIN tmp_station_seq ds
      ON ds.wo_no = up.wo_no AND ds.seq_no = nx.next_seq
    LEFT JOIN tmp_agg_check ex
      ON ex.wo_no = up.wo_no AND ex.seq_no = ds.seq_no AND ex.end_time = up.end_time
    WHERE up.out_qty IS NOT NULL
      AND nx.next_seq IS NOT NULL
      AND ex.wo_no IS NULL;

    DROP TEMPORARY TABLE tmp_agg_check;
    DROP TEMPORARY TABLE tmp_station_seq_lookup;

    -- ---------- tmp_agg_full: real rows + phantom rows, with running cumulative
    --            out/ng per (wo_no, seq_no) computed via window function ----------
    DROP TEMPORARY TABLE IF EXISTS tmp_agg_full;
    CREATE TEMPORARY TABLE tmp_agg_full (
        wo_no     VARCHAR(50),
        end_time  DATE,
        seq_no    INT,
        proc_nm   VARCHAR(100),
        itm_cd    VARCHAR(50),
        itm_type  VARCHAR(50),
        wo_qty    FLOAT,
        out_qty   FLOAT,
        ng_qty    FLOAT,
        rwk_qty   FLOAT,
        cum_out   FLOAT,
        cum_ng    FLOAT,
        PRIMARY KEY (wo_no, seq_no, end_time)
    );

    INSERT INTO tmp_agg_full (wo_no, end_time, seq_no, proc_nm, itm_cd, itm_type, wo_qty, out_qty, ng_qty, rwk_qty, cum_out, cum_ng)
    SELECT
        wo_no, end_time, seq_no, proc_nm, itm_cd, itm_type, wo_qty, out_qty, ng_qty, rwk_qty,
        SUM(out_qty) OVER (PARTITION BY wo_no, seq_no ORDER BY end_time
                            ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS cum_out,
        SUM(ng_qty)  OVER (PARTITION BY wo_no, seq_no ORDER BY end_time
                            ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS cum_ng
    FROM (
        SELECT wo_no, end_time, seq_no, proc_nm, itm_cd, itm_type, wo_qty, out_qty, ng_qty, rwk_qty FROM tmp_agg
        UNION ALL
        SELECT wo_no, end_time, seq_no, proc_nm, itm_cd, itm_type, wo_qty, out_qty, ng_qty, rwk_qty FROM tmp_phantom
    ) combined;

    DROP TEMPORARY TABLE tmp_agg;
    DROP TEMPORARY TABLE tmp_phantom;

    -- dedicated one-off copies for each lookup (avoids error 1137)
    DROP TEMPORARY TABLE IF EXISTS tmp_agg_same;
    CREATE TEMPORARY TABLE tmp_agg_same AS
    SELECT wo_no, seq_no, end_time, out_qty FROM tmp_agg_full;

    DROP TEMPORARY TABLE IF EXISTS tmp_agg_cum;
    CREATE TEMPORARY TABLE tmp_agg_cum AS
    SELECT wo_no, seq_no, end_time, cum_out FROM tmp_agg_full;

    DROP TEMPORARY TABLE IF EXISTS tmp_agg_cum2;
    CREATE TEMPORARY TABLE tmp_agg_cum2 AS
    SELECT wo_no, seq_no, end_time, cum_out FROM tmp_agg_full;

    DROP TEMPORARY TABLE IF EXISTS tmp_station_seq_b;
    CREATE TEMPORARY TABLE tmp_station_seq_b AS
    SELECT wo_no, seq_no, prev_seq FROM tmp_station_seq;

    -- ---------- Result-B: IN (same-date pass-through) + SAL (cumulative balance) ----------
    DROP TEMPORARY TABLE IF EXISTS tmp_b;
    CREATE TEMPORARY TABLE tmp_b AS
    SELECT
        t.wo_no, t.end_time, t.seq_no, t.proc_nm, t.itm_cd, t.itm_type, t.wo_qty,
        -- IN:
        --   Entry process: IN = OUT + NG for that row.
        --   Every other process: IN = same-date OUT of its TRUE previous station.
        CASE
            WHEN t.seq_no = m.min_seq THEN t.out_qty + IFNULL(t.ng_qty, 0)
            ELSE up.out_qty
        END AS in_qty,
        t.out_qty,
        t.ng_qty,
        t.rwk_qty,
        -- SAL:
        --   Entry process: always 0.
        --   The station immediately after the entry point (per true routing order,
        --   not seq_no+1): no balance shown until it has its own first output.
        --   Every other process: cumulative upstream OUT (as of this date) minus this
        --   seq's cumulative own OUT+NG (as of this date). RWK excluded.
        CASE
            WHEN t.seq_no = m.min_seq THEN 0
            WHEN sq.prev_seq = m.min_seq AND IFNULL(t.cum_out, 0) = 0 THEN NULL
            ELSE IFNULL(uc.cum_out, 0) - IFNULL(t.cum_out, 0) - IFNULL(t.cum_ng, 0)
        END AS bnc_qty
    FROM tmp_agg_full t
    JOIN tmp_min_seq m
      ON m.wo_no = t.wo_no
    JOIN tmp_station_seq_b sq
      ON sq.wo_no = t.wo_no AND sq.seq_no = t.seq_no
    LEFT JOIN tmp_agg_same up
      ON up.wo_no = t.wo_no AND up.seq_no = sq.prev_seq AND up.end_time = t.end_time
    LEFT JOIN tmp_agg_cum uc
      ON uc.wo_no = t.wo_no AND uc.seq_no = sq.prev_seq
     AND uc.end_time = (
            SELECT MAX(x.end_time)
            FROM tmp_agg_cum2 x
            WHERE x.wo_no = t.wo_no AND x.seq_no = sq.prev_seq AND x.end_time <= t.end_time
         );

    DROP TEMPORARY TABLE tmp_min_seq;
    DROP TEMPORARY TABLE tmp_station_seq;
    DROP TEMPORARY TABLE tmp_station_seq_b;
    DROP TEMPORARY TABLE tmp_agg_full;
    DROP TEMPORARY TABLE tmp_agg_same;
    DROP TEMPORARY TABLE tmp_agg_cum;
    DROP TEMPORARY TABLE tmp_agg_cum2;

    -- ---------- Result-D: dynamic pivot by proc_nm (ordered by seq_no) ----------
	SET SESSION group_concat_max_len = 1000000;
	SET @cols = NULL;
	SELECT GROUP_CONCAT(
			 CONCAT(
			   'SUM(CASE WHEN seq_no=', seq_no,
			   ' THEN in_qty END) AS `',
			   REPLACE(proc_nm,'`',''),'_IN_QTY`,',

			   'SUM(CASE WHEN seq_no=', seq_no,
			   ' THEN out_qty END) AS `',
			   REPLACE(proc_nm,'`',''),'_OUT_QTY`,',

			   'SUM(CASE WHEN seq_no=', seq_no,
			   ' THEN ng_qty END) AS `',
			   REPLACE(proc_nm,'`',''),'_NG_QTY`,',

			   'SUM(CASE WHEN seq_no=', seq_no,
			   ' THEN rwk_qty END) AS `',
			   REPLACE(proc_nm,'`',''),'_RWK_QTY`,',

			   'SUM(CASE WHEN seq_no=', seq_no,
			   ' THEN bnc_qty END) AS `',
			   REPLACE(proc_nm,'`',''),'_SAL_QTY`',

			   -- Delivery Qty: right after the finish good process, taken from its out_qty
			   IF(UPPER(REPLACE(proc_nm,'_',' ')) LIKE 'FINISH%GOOD%',
				  CONCAT(',SUM(CASE WHEN seq_no=', seq_no,
						 ' THEN out_qty END) AS `DELIVERY_QTY`'),
				  '')
			 )
			 ORDER BY seq_no
			 SEPARATOR ','
		   )
	INTO @cols
	FROM
	(
		SELECT seq_no, MIN(proc_nm) AS proc_nm
		FROM tmp_b
		GROUP BY seq_no
	) p;

    SET @sql = CONCAT(
        'SELECT
				CAST(wo_no AS CHAR) AS `WO NO`,
                CAST(itm_cd AS CHAR) AS `PART NO`,
				CAST(itm_type AS CHAR) AS `TYPE`,
				CAST(end_time AS DATETIME) AS `END DATE`,
				CAST(wo_qty AS DECIMAL(10,2)) AS `WO QTY`,
        ',
        @cols, '
         FROM tmp_b
         GROUP BY wo_no, itm_cd, itm_type, end_time, wo_qty
         ORDER BY wo_no, itm_cd, end_time'
    );

    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS wo_progress_pivot_by_process_allinfo");
    }
};
