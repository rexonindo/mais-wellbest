<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductRoute extends Model
{
    protected $table = 'prdroute_tbl';
    protected $primaryKey = 'id'; // composite key, no single PK
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'itm_type',
        'seq_no',
        'proc_cd',
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
        
    public function process()
    {
        return $this->belongsTo(Process::class, 'proc_cd', 'proc_cd');
    }
}
