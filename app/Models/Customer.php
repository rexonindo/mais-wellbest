<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'cust_tbl';
    protected $primaryKey = 'id';
    public $incrementing = false; // since dept_cd is not numeric
    protected $keyType = 'string';    
    public $timestamps = true; // Enabled timestamps (created_at / updated_at)

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'cust_cd',
        'cust_nm',
        'address',
        'telp',
        'created_by',
        'updated_by',
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

    public function getDisplayNameAttribute()
    {
        return "{$this->cust_cd} - {$this->cust_nm}";
    }

    // (Optional) define the key type since it's string
    // protected $keyType = 'string';    
}
