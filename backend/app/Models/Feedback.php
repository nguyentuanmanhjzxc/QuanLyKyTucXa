<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
     protected $table = 'feedbacks';

    protected $fillable = [
        'student_id',
        'title',
        'content',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
