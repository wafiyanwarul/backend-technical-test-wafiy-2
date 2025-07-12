<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Admin extends Model
{
    use HasApiTokens, HasUuids, Notifiable;

    protected $table = 'admins';
    protected $fillable = ['name', 'username', 'email', 'phone', 'password'];

    protected $hidden = ['password', 'created_at', 'updated_at'];
}
