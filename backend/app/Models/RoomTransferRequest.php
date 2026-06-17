<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomTransferRequest extends Model
{
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
