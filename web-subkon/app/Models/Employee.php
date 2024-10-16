<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
     protected $fillable = [
        'subkon_id',
        'nik',
        'name',
        'address',
        'phone_number',
        'date_of_birth',
        'speciality',
        'attachment_ktp'
    ];

    public function subkon()
    {
        return $this->belongsTo(Subkon::class);
    }
}
