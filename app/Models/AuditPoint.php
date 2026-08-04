<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_process_id',
        'nomor_urut',
        'deskripsi',
    ];

    public function process()
    {
        return $this->belongsTo(AuditProcess::class, 'audit_process_id');
    }
}
