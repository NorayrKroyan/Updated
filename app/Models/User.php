<?php
  
namespace App\Models;

use App\Http\Controllers\UserController;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
  
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
  
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
    ];
  
    protected $hidden = [
        'password',
        'remember_token',
    ];
  
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function images()
    {
        return $this->hasMany(Images::class,'users_id', 'id');
    }

    
}