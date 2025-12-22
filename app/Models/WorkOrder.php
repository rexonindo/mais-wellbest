<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WorkOrder extends Model
{
    protected $table = 'wo_tbl';   // your table name
    protected $primaryKey = 'id';  // the new primary key
    public $incrementing = true;   // enable auto-increment
    protected $keyType = 'int';    // key type
    public $timestamps = true;     // if you have created_at and updated_at


    protected $fillable = [
        'wo_no',
        'itm_cd',
        'po_no',
        'req_dt',
        'plan_qty',
        'plan_qty_raw',
        'plan_qty_pnl',
        'start_dt',
        'end_dt',
        'stats',
        'tool_cd',
    ];

    protected $attributes = [
        'plan_qty' => 0,
        'plan_qty_raw' => 0,
        'plan_qty_pnl' => 0,
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

    protected static function booted()
    {
        static::saved(function ($model) {
            $model->refresh();
        });

        static::deleting(function ($workOrder) {
            $workOrder->processes()->delete();
        });        
    }

    // ✅ Relationship to item
    public function item()
    {
        return $this->belongsTo(Item::class, 'itm_cd', 'itm_cd');
    }

    public function processes()
    {
        return $this->hasMany(WorkOrderProcess::class, 'wo_no', 'wo_no');
    }    
}
