<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'type', 'format', 'start_time', 'max_slots', 'is_active'];

    protected $casts = [
        'start_time' => 'datetime',
        'is_active' => 'boolean',
        'registered_count' => 'integer',
        'max_slots' => 'integer'
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function hasAvailableSlots()
    {
        return $this->registered_count < $this->max_slots;
    }

    public function availableSlots()
    {
        return $this->max_slots - $this->registered_count;
    }
}