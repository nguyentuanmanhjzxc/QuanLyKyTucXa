<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'student_services'
        )
        ->withPivot([
            'start_date',
            'end_date',
            'status'
        ])
        ->withTimestamps();
    }
}
