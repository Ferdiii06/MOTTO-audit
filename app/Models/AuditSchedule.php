<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_user_id',
        'audit_process_id',
        'audit_area_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($schedule) {
            $hasProcess = ! is_null($schedule->audit_process_id);
            $hasArea = ! is_null($schedule->audit_area_id);

            if ($hasProcess === $hasArea) {
                throw new Exception('Jadwal audit harus memilih salah satu antara process audit atau area audit, tidak boleh keduanya terisi atau keduanya kosong.');
            }
        });
    }

    public function scopeOverlapping($query, $tanggalMulai, $tanggalSelesai, $auditUserId, $processId = null, $areaId = null, $excludeId = null)
    {
        $query->where('tanggal_mulai', '<=', $tanggalSelesai)
            ->where('tanggal_selesai', '>=', $tanggalMulai)
            ->where('audit_user_id', $auditUserId);

        if ($processId) {
            $query->where('audit_process_id', $processId);
        } elseif ($areaId) {
            $query->where('audit_area_id', $areaId);
        }

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query;
    }

    public function auditor()
    {
        return $this->belongsTo(AuditUser::class, 'audit_user_id');
    }

    public function process()
    {
        return $this->belongsTo(AuditProcess::class, 'audit_process_id');
    }

    public function area()
    {
        return $this->belongsTo(AuditArea::class, 'audit_area_id');
    }

    public function creator()
    {
        return $this->belongsTo(AuditUser::class, 'created_by');
    }
}
