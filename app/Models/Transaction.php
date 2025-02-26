<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';

    protected $fillable = [
        'no_trf',
        'tanggal',
        'no_spb',
        'keterangan',
        'kepada',
        'ctt_pajak',
        'ctt_bendahara',
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

    public function transaction_details()
    {
        return $this->hasMany(Transaction_detail::class);
    }    
}
