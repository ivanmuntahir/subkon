<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subkon extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'kode_subkon',
        'total_employee',
    ];

    // Auto-generate 'kode_subkon' on model creation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subkon) {
            $subkon->kode_subkon = self::generateKodeSubkon();
        });
    }

    public static function generateKodeSubkon(): string
    {
        // Retrieve the highest numeric part from all kode_subkon records (e.g., 'SUBKON-0005')
        $maxSubkon = \App\Models\Subkon::selectRaw("MAX(CAST(SUBSTRING(kode_subkon, 8) AS UNSIGNED)) as max_number")
                        ->where('kode_subkon', 'LIKE', 'SUBKON-%')
                        ->first();

        if ($maxSubkon && $maxSubkon->max_number) {
            // Increment the highest number found and pad to 4 digits
            $newNumber = str_pad($maxSubkon->max_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Start from '0001' if no records exist
            $newNumber = '0001';
        }

        return "SUBKON-{$newNumber}";
    }

}
