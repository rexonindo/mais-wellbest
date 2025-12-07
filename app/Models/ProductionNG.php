<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductionNG extends Model
{
    protected $table = 'prdng_tbl';
    protected $primaryKey = 'id';
    public $timestamps = true; // Enabled timestamps (created_at / updated_at)

    protected $fillable = [
        'id_prd',
        'ng_nm',
        'ng_qty',
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

    public function productionLog()
    {
        return $this->belongsTo(ProductionLog::class, 'id_prd');
    }
}
