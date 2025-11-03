<?php

namespace App\Models;

/*
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
*/

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role as SpatieRole;

class ModelHasRole extends Model
{

    protected $table = 'model_has_roles';
    public $timestamps = false; // Table doesn’t have timestamps

    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
    ];
/*
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }    
*/
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function user()
    {
        // Only if model_type = App\Models\User
        return $this->belongsTo(User::class, 'model_id');
    }
}
