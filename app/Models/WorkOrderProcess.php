<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WorkOrderProcess extends Model
{
    protected $table = 'wo_proc_tbl';   // your table name
    protected $primaryKey = 'id';  // the new primary key
    public $incrementing = true;   // enable auto-increment
    protected $keyType = 'int';    // key type
    public $timestamps = true;     // if you have created_at and updated_at


    protected $fillable = [
        'wo_no',
        'seq_no',
        'proc_cd',
        'cav',
        'shoot_qty',
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
    }

    // ✅ Relationship to proc_cd
    public function proc_cd()
    {
        return $this->belongsTo(Process::class, 'proc_cd', 'proc_cd');
    }
}
