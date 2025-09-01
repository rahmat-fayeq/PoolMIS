<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'type'];

    public function sessionalPlan()
    {
        return $this->hasOne(SessionalPlan::class);
    }
    public function monthlyPlan()
    {
        return $this->hasOne(MonthlyPlan::class);
    }
    public function dailyPlan()
    {
        return $this->hasOne(DailyPlan::class);
    }

    public function dailyVisit()
    {
        return $this->hasMany(DailyPlan::class);
    }

    public function services()
    {
        return $this->hasMany(MemberService::class);
    }

    public function sessionalVisits()
    {
        return $this->hasMany(SessionalVisit::class);
    }
    public function monthlyVisits()
    {
        return $this->hasMany(MonthlyVisit::class);
    }
    public function expenses()
    {
        return $this->hasMany(MemberService::class);
    }
}
