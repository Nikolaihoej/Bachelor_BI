<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'user'; // Tabellenavn
    protected $primaryKey = 'email'; // Primær nøgle
    public $incrementing = false; // Primary key is not an incrementing integer
    protected $keyType = 'string'; // Primary key type

    protected $fillable = [
        'email',
        'password',
        'jwt_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
