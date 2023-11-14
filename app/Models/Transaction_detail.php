<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction_detail extends Model
{
    use HasFactory;
    protected $table = 'transaction_details';

    protected $fillable = [
        'transaction_id',
        'account_id',
        'nominal',
        'dk', 
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
