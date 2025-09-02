<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberService extends Model
{
    protected $fillable = ['member_id', 'service_id', 'quantity', 'total_price', 'service_date'];
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
