<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget_detail extends Model
{
    use HasFactory;
    protected $table = 'budget_details';

    protected $fillable = [
        'budget_id',
        'account_id',
        'nominal',
        'keterangan', 
    ];
    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
