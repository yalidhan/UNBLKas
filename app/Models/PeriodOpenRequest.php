<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodOpenRequest extends Model
{
    protected $fillable = [
        'year',
        'month',
        'requested_by',
        'reason',
        'status',
        'approved_by',
        'approved_at' => 'datetime',
        'open_until' => 'datetime',
        'requested_duration_hours',
        'reject_reason',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    public function period()
    {
        return PeriodClosing::where('year', $this->year)
            ->where('month', $this->month)
            ->first();
    }
}
