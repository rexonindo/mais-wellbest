<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Process extends Model
{
    protected $table = 'proc_tbl';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true; // Enabled timestamps (created_at / updated_at)
    protected $fillable = [
        'proc_cd',
        'proc_nm',
        'dept_cd',
        'wip_sfx',
        'std_time',
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

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_cd', 'dept_cd');
    }

}

