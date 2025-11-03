<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus'; // name of your table
    public $timestamps = true; // Enabled timestamps (created_at / updated_at)
    protected $fillable = [
        'group',
        'name',
        'icon',
        'url',
        'order',
    ];
}

