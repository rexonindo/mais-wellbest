<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ItemGroup extends Model
{
    use HasFactory;

    protected $table = 'itmgrp_tbl';
    protected $primaryKey = 'id';
    public $incrementing = false; // Since itm_cd is VARCHAR, not auto-increment
    protected $keyType = 'string';
    public $timestamps = true; // Enabled timestamps (created_at / updated_at)

    // Fillable columns
    protected $fillable = [
        'itm_grp',
        'itm_type',
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

}
