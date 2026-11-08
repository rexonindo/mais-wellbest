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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `adjust_stock_opname`(
	p_TransDt DATETIME,
    P_ItmCd VARCHAR(50),
    P_ProcCd VARCHAR(50),
	P_Qty FLOAT,
    P_UserName VARCHAR(50)
)
BEGIN
    DECLARE V_CurrQty FLOAT;
    DECLARE V_AdjQty FLOAT;
    DECLARE V_WIPSfx VARCHAR(50);    
    SET V_CurrQty = 0;
    SET V_AdjQty = 0;
    
    SELECT qty INTO V_CurrQty
    FROM iv_status_view
    WHERE itm_cd = P_ItmCd AND proc_cd = P_ProcCd;
    
    SELECT wip_sfx INTO V_WIPSfx
	FROM proc_tbl
	WHERE proc_cd = P_ProcCd;        
    
    SET V_AdjQty = P_Qty - V_CurrQty;
    
    INSERT INTO ivmov_tbl(itm_cd, proc_cd, wip_cd, trans_dt, mov_type, qty, ref_table, ref_id, created_at) 
    VALUES(P_ItmCd, P_ProcCd, CONCAT(P_ItmCd, '#', V_WIPSfx), p_TransDt, 'SO', V_AdjQty, CONCAT(CONVERT(P_Qty, CHAR), '-', P_UserName), 0, NOW());  
    
	INSERT INTO ivmovwo_tbl(wo_no, itm_cd, proc_cd, wip_cd, trans_dt, mov_type, qty, ref_table, ref_id, created_at) 
    VALUES('WO-SO', P_ItmCd, P_ProcCd, CONCAT(P_ItmCd, '#', V_WIPSfx), p_TransDt, 'SO', V_AdjQty, CONCAT(CONVERT(P_Qty, CHAR), '-', P_UserName), 0, NOW());  
    
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS adjust_stock_opname");
    }
};
