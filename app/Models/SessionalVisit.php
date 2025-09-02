<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionalVisit extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'visit_time', 'lock_number'];

    protected function casts(): array
    {
        return [
            'visit_time' => 'datetime',
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
