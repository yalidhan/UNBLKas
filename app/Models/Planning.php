<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    use HasFactory;
    protected $table = 'plannings';

    protected $fillable = [
        'for_bulan',
        'departement_id',
        'user_id',    
        'budget_id',
        'is_approved_wr2',
        'is_approved_rektor',
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function planning_details()
    {
        return $this->hasMany(Planning_detail::class);
    }    
}
