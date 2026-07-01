<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{

    protected $fillable = [
        'student_id',
        'violation_type',
        'description',
        'penalty',
        'violation_date'
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
