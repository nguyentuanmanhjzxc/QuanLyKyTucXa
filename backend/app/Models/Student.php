<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'student_code',
        'full_name',
        'gender',
        'dob',
        'phone',
        'email',
        'faculty',
        'class_name',
        'guardian_name',
        'guardian_phone',
        'status'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
        public function services()
    {
        return $this->belongsToMany(
            Service::class,
            'student_services'
        )
        ->withPivot([
            'start_date',
            'end_date',
            'status'
        ])
        ->withTimestamps();
    }
    
    public function roomRegistrations()
    {
        return $this->hasMany(RoomRegistration::class);
    }
    
    public function roomAssignments()
    {
        return $this->hasMany(RoomAssignment::class);
    }

    public function roomTransferRequests()
    {
        return $this->hasMany(RoomTransferRequest::class);
    }

    public function violations()
    {
        return $this->hasMany(Violation::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function repairRequests()
    {
        return $this->hasMany(RepairRequest::class);
    }
}
