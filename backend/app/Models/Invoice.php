<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function reading()
    {
        return $this->belongsTo(
            UtilityReading::class,
            'reading_id'
        );
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
