<?php

namespace App\Models;

use App\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Employee extends Model implements Sortable
{
    use HasFactory, SortableTrait;
    protected $fillable = [
        'subkon_id',
        'nik',
        'name',
        'address',
        'phone_number',
        'date_of_birth',
        'speciality',
        'attachment_ktp',
        'status',
    ];

    protected $casts = [
        'status' => EmployeeStatus::class,
    ];

    public function subkon()
    {
        return $this->belongsTo(Subkon::class);
    }

    
}
