<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id', 'user_type_id', 'reporting_manager', 'role', 'name', 'last_name', 'email', 'password', 'zone', 'state', 'city', 'mobile', 'product', 'segment', 'brand', 'flag', 'dealer_id', 'assign_complaint'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
