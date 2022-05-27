<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'role'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
