<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomRegistration extends Model
{

    protected $fillable = [
        'student_id',
        'room_id',
        'proof_file',
        'registration_date',
        'status',
        'approved_by',
        'approved_at'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

        public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}
