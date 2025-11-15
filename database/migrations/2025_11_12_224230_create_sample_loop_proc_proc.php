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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sample_loop_proc`()
BEGIN
    DECLARE v_id INT;
    DECLARE v_name VARCHAR(100);
    DECLARE done INT DEFAULT 0;

    -- Declare cursor
    DECLARE cur CURSOR FOR
        SELECT id, name FROM employees ORDER BY id;

    -- Handler for when no more rows are found
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    -- Create a temp table to see output
    CREATE TEMPORARY TABLE IF NOT EXISTS emp_log (
        id INT,
        name VARCHAR(100),
        processed_at DATETIME
    );

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO v_id, v_name;
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Simulate some processing
        INSERT INTO emp_log (id, name, processed_at)
        VALUES (v_id, v_name, NOW());
    END LOOP;

    CLOSE cur;

    SELECT * FROM emp_log;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sample_loop_proc");
    }
};
