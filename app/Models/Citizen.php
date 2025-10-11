<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
   protected $fillable = [
        'user_id', 'national_id', 'full_name', 'date_of_birth', 
        'address', 'phone_number', 'verification_status', 'verification_notes', 'verified_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
