<?php

namespace YS\VueDatatable\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The name of table model is associated with.
     *
     * @var string
     */
     protected $table='users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role_id'
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
