<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentService extends Model
{
    protected $fillable = [
        'student_id',
        'service_id',
        'start_date',
        'end_date',
        'status'
    ];
    
        public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
