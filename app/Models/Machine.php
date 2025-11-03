<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Machine extends Model
{
    use HasFactory;

    protected $table = 'mchn_tbl';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'mchn_cd',
        'mchn_nm',
        'dept_cd',
        'uom',
        'dsc',
        'stats',
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