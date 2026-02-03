<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditNote extends Model
{
    use HasFactory;
    protected $fillable = [
        'audit_id',
        'auditor_id',
        'note',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function audit()
    {
        return $this->belongsTo(TransactionAudit::class, 'audit_id');
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    // Helper
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }
}
