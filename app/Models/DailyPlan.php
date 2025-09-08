<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyPlan extends Model
{
    protected $fillable = ['member_id', 'date', 'price', 'quantity', 'lock_number'];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
