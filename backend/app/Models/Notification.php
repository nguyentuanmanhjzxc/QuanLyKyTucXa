<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $fillable = [
        'title',
        'content',
        'created_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}
