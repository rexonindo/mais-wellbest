<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductionLog extends Model
{
    use HasFactory;

    protected $table = 'prdlog_tbl';
    protected $primaryKey = 'id';
    public $timestamps = true; // Enabled timestamps (created_at / updated_at)

    protected $fillable = [
        'wo_no',
        'itm_cd',
        'proc_cd',
        'seq_no',
        'mchn_cd',
        'emp_id',
        'start_time',
        'end_time',
        'cav',        
        'avail_qty',
        'in_qty',
        'out_qty',
        'ng_qty',
        'ng_qty_pcs',
        'rwk_qty',
        'rmks',
        'rmks_rwk',
    ];

    protected $attributes = [
        'avail_qty' => 0,
        'in_qty' => 0,
        'out_qty' => 0,
        'ng_qty' => 0,
        'ng_qty_pcs' => 0,
        'rwk_qty' => 0,

    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::user()->name;
                $model->updated_by = Auth::user()->name;
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::user()->name;
            }
        });
    }    

    public function item() {
        return $this->belongsTo(Item::class, 'itm_cd', 'itm_cd');
    }

    public function process() {
        return $this->belongsTo(Process::class, 'proc_cd', 'proc_cd');
    }

    public function machine() {
        return $this->belongsTo(Machine::class, 'mchn_cd', 'mchn_cd');
    }

    public function employee() {
        return $this->belongsTo(Employee::class, 'emp_id', 'emp_id');
    }

    public function workOrder() {
        return $this->belongsTo(WorkOrder::class, 'wo_no', 'wo_no');
    }

    public function ngDetails()
    {
        return $this->hasMany(\App\Models\ProductionNG::class, 'id_prd');
    }

}
