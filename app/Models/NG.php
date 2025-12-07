<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NG extends Model
{
    use HasFactory;

    protected $table = 'ng_tbl';
    protected $primaryKey = 'id';
    public $timestamps = true; // Enabled timestamps (created_at / updated_at)
        
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ng_nm',
        'dsc',
        'location',
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