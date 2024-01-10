<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planning_detail extends Model
{
    use HasFactory;
    protected $table = 'planning_details';

    protected $fillable = [
        'planning_id',
        'account_id',
        'nominal',
        'nominal_disetujui', 
        'group_rektorat', 
        'approved_by_wr2',
        'note_wr2', 
        'approved_by_rektor',
        'pj',
        'satuan_ukur_kinerja',
        'target_kinerja',
        'capaian_kinerja',
        'waktu_pelaksanaan',
        'capaian_target_waktu',	

    ];

    public function planning()
    {
        return $this->belongsTo(Planning::class);
    }
    
}
