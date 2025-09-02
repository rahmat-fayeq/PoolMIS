<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyPlan extends Model
{
    protected $fillable = ['member_id', 'date', 'price', 'lock_number'];
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
