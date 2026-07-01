<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtilityReading extends Model
{

    protected $fillable = [
        'room_id',
        'month',
        'year',
        'electric_old',
        'electric_new',
        'water_old',
        'water_new'
    ];
    
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
