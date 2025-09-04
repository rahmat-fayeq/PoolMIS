<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MonthlyVisit;
use App\Models\SessionalVisit;
use Illuminate\Http\Request;

class MemberLogController extends Controller
{
    // Sessional visit logging
    public function logSessionalVisit(Request $request, $memberId)
    {
        $member = Member::with(['sessionalPlan', 'sessionalVisits' => function ($q) {
            $q->orderBy('visit_time', 'desc');
        }])->findOrFail($memberId);

        if (!$member->sessionalPlan) {
            return back()->with('error', 'No sessional plan assigned.');
        }

        return view('members.sessional-visit', compact('member'));
    }

    public function storeLogSessionalVisit(Request $request, $memberId)
    {
        $member = Member::with('sessionalPlan')->findOrFail($memberId);

        if (!$member->sessionalPlan) {
            return back()->with('error', 'No sessional plan assigned.');
        }

        $request->validate([
            'guest' => 'required|numeric|min:0',
            'lock_number' => 'required|string',
            'visit_time' => 'required|date',
        ]);

        $guest = (int) $request->guest;
        $decrement = $guest === 0 ? 1 : $guest + 1;

        if ($member->sessionalPlan->remaining_sessions < $decrement) {
            return back()->with('error', 'Not enough remaining sessions for this visit.');
        }

        $member->sessionalVisits()->create([
            'lock_number' => $request->lock_number,
            'visit_time' => $request->visit_time,
            'guest' => $request->guest
        ]);

        $member->sessionalPlan->decrement('remaining_sessions', $decrement);

        return back()->with('success', 'Visit logged successfully.');
    }


    public function deleteSessionalVisit(string $id, Member $member)
    {
        $visit = SessionalVisit::query()->findOrFail($id);
        $increment = $visit->guest === 0 ? 1 : $visit->guest + 1;
        $member->sessionalPlan()->increment('remaining_sessions', $increment);
        SessionalVisit::where('id', $id)->delete();
        return back()->with('success', 'Sessional visit deleted successfully.');
    }

    // --- Monthly Visit Logging ---
    public function logMonthlyVisit(Request $request, $memberId)
    {
        $member = Member::with(['monthlyPlan', 'monthlyVisits' => function ($q) {
            $q->orderBy('visit_time', 'desc');
        }])->findOrFail($memberId);

        if (!$member->monthlyPlan) {
            return back()->with('error', 'No monthly plan assigned.');
        }

        return view('members.monthly-visit', compact('member'));
    }

    public function storeLogMonthlyVisit(Request $request, $memberId)
    {
        $member = Member::with('monthlyPlan')->findOrFail($memberId);

        if (!$member->monthlyPlan) {
            return back()->with('error', 'No monthly plan assigned.');
        }

        $request->validate([
            'lock_number' => 'required|string',
            'visit_time' => 'required|date',
        ]);

        $member->monthlyVisits()->create([
            'lock_number' => $request->lock_number,
            'visit_time' => $request->visit_time,
        ]);

        return back()->with('success', 'Monthly visit logged successfully.');
    }

    public function deleteMonthlyVisit(string $id)
    {
        MonthlyVisit::where('id', $id)->delete();
        return back()->with('success', 'Monthly visit deleted successfully.');
    }
}
