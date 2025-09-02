<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyPlan extends Model
{
    protected $fillable = ['member_id', 'start_date', 'end_date', 'price'];
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
