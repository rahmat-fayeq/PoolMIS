<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'member_id',
        'visit_time',
        'lock_number',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
