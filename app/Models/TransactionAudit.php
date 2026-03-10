<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionAudit extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'status',
        'auditor_id',
        'audited_at',
    ];

    public const STATUS_VERIFIED = 'verified';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_REVIEW = 'under_review';
    public const STATUS_PENDING = 'pending_review';

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function notes()
    {
        return $this->hasMany(AuditNote::class, 'audit_id');
    }
    
    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }
}
