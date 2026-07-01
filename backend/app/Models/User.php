<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'role',
        'status'
    ];
    protected $hidden = [
        'password'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function notifications()
    {
        return $this->hasMany(
            Notification::class,
            'created_by'
        );
    }

    public function approvedRegistrations()
    {
        return $this->hasMany(
            RoomRegistration::class,
            'approved_by'
        );
    }

        public function approvedTransfers()
    {
        return $this->hasMany(
            RoomTransferRequest::class,
            'approved_by'
        );
    }
}
