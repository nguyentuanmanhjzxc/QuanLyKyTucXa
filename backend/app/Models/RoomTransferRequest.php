<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomTransferRequest extends Model
{   
        protected $fillable = [
        'student_id',
        'old_room_id',
        'new_room_id',
        'reason',
        'status',
        'approved_by',
        'approved_at'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function oldRoom()
    {
        return $this->belongsTo(Room::class,'old_room_id');
    }

    public function newRoom()
    {
        return $this->belongsTo(Room::class,'new_room_id');
    }
}
