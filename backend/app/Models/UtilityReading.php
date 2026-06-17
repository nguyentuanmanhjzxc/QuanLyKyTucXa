<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtilityReading extends Model
{
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function invoices()
    {
        return $this->hasMany(
            Invoice::class,
            'reading_id'
        );
    }
}
