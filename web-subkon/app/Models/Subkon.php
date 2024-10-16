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
        // Get the last kode_subkon (e.g., 'SUB-005')
        $lastSubkon = self::orderBy('id', 'desc')->first();

        if ($lastSubkon) {
            $lastNumber = (int) substr($lastSubkon->kode_subkon, 4);  // Extract '005'
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);  // Increment and pad to 3 digits
        } else {
            $newNumber = '001';  // Start with '001' if no record exists
        }

        return "SUB-{$newNumber}";
    }
}
