<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Department extends Model
{
    use HasFactory;

    // Define the actual table name
    protected $table = 'dept_tbl';
    // If your table doesn’t have `id` or timestamps, disable them
    protected $primaryKey = 'id';
    public $incrementing = false; // since dept_cd is not numeric
    public $timestamps = true; // Enabled timestamps (created_at / updated_at)
    // Define the fillable fields for mass assignment
    protected $fillable = [
        'dept_cd',
        'dept_nm',
        'descrp',
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

    // (Optional) define the key type since it's string
    protected $keyType = 'string';


}
