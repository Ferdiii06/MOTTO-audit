<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditUser extends Model
{
    protected $fillable = ['nik', 'name', 'password', 'role', 'tipe_auditor'];
    protected $hidden = ['password'];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isInstruktur(): bool
    {
        return $this->tipe_auditor === 'instruktur';
    }

    public function schedules()
    {
        return $this->hasMany(AuditSchedule::class, 'audit_user_id');
    }

    public function getAllowedProcessIds(): ?array
    {
        if ($this->isAdmin()) {
            return null;
        }

        $schedules = AuditSchedule::where('audit_user_id', $this->id)
            ->where('tanggal_mulai', '<=', today())
            ->where('tanggal_selesai', '>=', today())
            ->get();

        $processIds = [];

        foreach ($schedules as $schedule) {
            if ($schedule->audit_process_id) {
                $processIds[] = $schedule->audit_process_id;
            } elseif ($schedule->audit_area_id) {
                $areaProcessIds = AuditProcess::where('audit_area_id', $schedule->audit_area_id)
                    ->pluck('id')
                    ->toArray();
                $processIds = array_merge($processIds, $areaProcessIds);
            }
        }

        return array_values(array_unique($processIds));
    }

    public function getAuditedProcessIds(): array
    {
        $schedules = AuditSchedule::where('audit_user_id', $this->id)
            ->where('tanggal_mulai', '<=', today())
            ->where('tanggal_selesai', '>=', today())
            ->get();

        $auditedIds = [];

        foreach ($schedules as $schedule) {
            $processIds = $schedule->audit_process_id
                ? [$schedule->audit_process_id]
                : AuditProcess::where('audit_area_id', $schedule->audit_area_id)->pluck('id')->toArray();

            foreach ($processIds as $pid) {
                $alreadyAudited = AuditRecord::where('audit_process_id', $pid)
                    ->where('audit_user_id', $this->id)
                    ->whereBetween('audit_date', [$schedule->tanggal_mulai, $schedule->tanggal_selesai])
                    ->exists();

                if ($alreadyAudited) {
                    $auditedIds[] = $pid;
                }
            }
        }

        return array_values(array_unique($auditedIds));
    }
}