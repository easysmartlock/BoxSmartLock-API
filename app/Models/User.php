<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    const ADMIN = 'ADMIN' ;
    const USER = 'USER' ;

    const ACTIVE = true;
    const INACTIVE = false;

    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Recupère les boxes
     */
    public function boxes()
    {
        return $this->hasMany('App\Models\Box','user_id');
    }

    /**
     * Recupère des serrures
     */
    public function easies()
    {
        return $this->hasMany('App\Models\Easy','user_id');
    }
}
