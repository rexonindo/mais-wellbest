<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $table = 'itm_tbl';
    protected $primaryKey = 'id';
    public $incrementing = false; // Since itm_cd is VARCHAR, not auto-increment
    protected $keyType = 'string';
    public $timestamps = true; // Enabled timestamps (created_at / updated_at)

    // Fillable columns
    protected $fillable = [
        'itm_cd',
        'itm_nm',
        'itm_type',
        'fg_flg',
        'uom',
        'std_rate',
        'cavity',
        'cust_cd',
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

    protected $casts = [
        'fg_flg' => 'boolean',
    ];

    public function getDisplayNameAttribute()
    {
        return "{$this->itm_cd} - {$this->itm_nm}";
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'cust_cd', 'cust_cd');
    }
}
