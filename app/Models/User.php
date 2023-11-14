<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table='users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'departement_id',
        'jabatan',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    const CREATED_AT='created_at';
    const UPDATED_AT='updated_at';
    
    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
