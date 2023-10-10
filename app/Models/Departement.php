<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;
    protected $table = 'departements';

    protected $fillable = [
        'nama',
        'status',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
