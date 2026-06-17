<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'building_id',
        'room_code',
        'capacity',
        'current_occupancy',
        'price',
        'status'
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
    public function registrations()
    {
        return $this->hasMany(RoomRegistration::class);
    }
    public function assignments()
    {
        return $this->hasMany(RoomAssignment::class);
    }
    public function utilityReadings()
    {
        return $this->hasMany(UtilityReading::class);
    }
    public function repairRequests()
    {
        return $this->hasMany(RepairRequest::class);
    }
        public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
