<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolingCavity extends Model
{
    use HasFactory;

    protected $table = 'toolcav_tbl';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true; // Enabled timestamps (created_at / updated_at)    

    protected $fillable = [
        'itm_cd',
        'tool_cd',
        'proc_cd',
        'cav',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'itm_cd', 'itm_cd');
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'proc_cd', 'proc_cd');
    }
}
