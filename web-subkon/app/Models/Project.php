<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'certificates_skills' => 'array',
        
    ];

    protected $attributes = [
        'certificates_skills' => '[]', // Default to an empty array
    ];

    public function subkon()
    {
        return $this->belongsTo(Subkon::class);
    }
}
