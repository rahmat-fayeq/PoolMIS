<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'full_name',
        'father_name',
        'job',
        'phone',
        'amount',
        'submit_date',
    ];
}
