<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'empl_tbl';
    protected $primaryKey = 'id';
    public $timestamps = true; // Enabled timestamps (created_at / updated_at)
        
    protected $fillable = [
        'emp_id',
        'emp_nm',
        'email',
        'psition',
        'dept_cd',
        'shift_cd',
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
        
    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_cd', 'dept_cd');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_cd', 'shift_cd');
    }
}
