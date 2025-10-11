<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
      protected $fillable = [
        'name', 'email', 'password', 'role'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



    public function citizen()
    {
        return $this->hasOne(Citizen::class);
    }

    public function isSystemAdmin()
    {
        return $this->role === 'system_admin';
    }

    public function isLocalLeader()
    {
        return $this->role === 'local_leader';
    }

    public function isPolicyMaker()
    {
        return $this->role === 'policy_maker';
    }

    public function isCitizen()
    {
        return $this->role === 'citizen';
    }
}
