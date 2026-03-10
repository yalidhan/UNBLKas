<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditNote extends Model
{
    use HasFactory;
    protected $fillable = [
        'audit_id',
        'notetaker_id',
        'note',
        'note_at',
        'read_at',
    ];

    protected $casts = [
        'note_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function audit()
    {
        return $this->belongsTo(TransactionAudit::class, 'audit_id');
    }

    public function notetaker()
    {
        return $this->belongsTo(User::class, 'notetaker_id');
    }

    // Helper
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }
}
