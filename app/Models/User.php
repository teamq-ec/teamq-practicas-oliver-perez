<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, AuthenticatableTrait, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'birthdate',
        'address',
        'country',
        'zip',
        'telephone',
        'position',
        'department',
        'companyage',
        'state',
        'email',
        'email_verified_at',
        'password',
        'rol_id',
        'ruta_imagen'
    ];

    public function rol()
    {
        return $this->belongsTo(Roles::class);
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
