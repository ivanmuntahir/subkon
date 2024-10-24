<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'subkon_id',
        'name',
        'pic_name',
        'certificates_skills',
        'comment',
        'total_needed',
        'attachment_bast',
        'attachment_photo',
        'regency_id',
        'province_id',
    ];

    protected $casts = [
        'certificates_skills' => 'array',
    ];

    public function getFormattedCertificatesSkillsAttribute()
    {
        if (is_array($this->certificates_skills)) {
            return implode(', ', array_map(fn($item) => $item['skill'] ?? 'N/A', $this->certificates_skills));
        }
        return 'No skills available';
    }

    public function subkon()
    {
        return $this->belongsTo(Subkon::class);
    }

   public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    public function assignments()
    {
        return $this->hasMany(ProjectAssignment::class, 'project_id');
    }
}
