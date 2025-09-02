<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionalPlan extends Model
{
    protected $fillable = ['member_id', 'total_sessions', 'remaining_sessions', 'price'];
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
