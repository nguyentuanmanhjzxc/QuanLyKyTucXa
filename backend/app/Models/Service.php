<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'service_name',
        'price',
        'status'
    ];

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
    public function studentServices()
    {
        return $this->hasMany(
            StudentService::class,
            'service_id'
        );
    }
}
