<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;
    protected $table = 'budgets';

    protected $fillable = [
        'tahun',
        'departement_id',
        'user_id',    
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function budget_details()
    {
        return $this->hasMany(Budget_detail::class);
    }    
}
