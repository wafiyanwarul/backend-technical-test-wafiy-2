<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Employee extends Model
{
    use HasUuids;

    protected $table = 'employees';
    protected $fillable = ['image', 'name', 'phone', 'division_id', 'position'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
