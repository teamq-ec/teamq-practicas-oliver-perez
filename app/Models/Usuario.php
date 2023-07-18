<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait, HasApiTokens;
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
}
