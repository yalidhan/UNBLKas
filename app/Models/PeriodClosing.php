<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodClosing extends Model
{
    protected $fillable = [
        'year',
        'month',
        'is_closed',
        'closed_by',
        'closed_at',
        'reopened_at',
        'reopen_expires_at'
    ];

    public function closer()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
    
    public function openRequests()
    {
        return $this->hasMany(PeriodOpenRequest::class, 'month', 'month')
            ->where('year', $this->year);
    }
}
