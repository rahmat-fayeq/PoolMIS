<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\Member;

class VisitController extends Controller
{
    /**
     * Display a listing of visits, separated by type.
     */
    public function index()
    {
        $sessionalVisits = Visit::with('member')
            ->whereHas('member', fn($q) => $q->where('type', 'sessional'))
            ->latest()
            ->get();

        $monthlyVisits = Visit::with('member')
            ->whereHas('member', fn($q) => $q->where('type', 'monthly'))
            ->latest()
            ->get();

        return view('visits.index', compact('sessionalVisits', 'monthlyVisits'));
    }

    /**
     * Show the form for creating a new visit.
     */
    public function create()
    {
        $members = Member::whereIn('type', ['sessional', 'monthly'])->get();
        return view('visits.create', compact('members'));
    }

    /**
     * Store a newly created visit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id'   => 'required|exists:members,id',
            'visit_time'  => 'required|date',
            'lock_number' => 'required|integer|min:1',
        ]);

        $member = Member::findOrFail($validated['member_id']);

        // Only allow sessional and monthly members
        if (!in_array($member->type, ['sessional', 'monthly'])) {
            return redirect()->back()->withErrors(['member_id' => 'Only sessional and monthly members can log visits.']);
        }

        // Handle sessional member: reduce remaining sessions
        if ($member->type === 'sessional') {
            $plan = $member->sessionalPlan;

            if (!$plan) {
                return redirect()->back()->with('error', 'No sessional plan assigned to this member.');
            }

            if ($plan->remaining_sessions <= 0) {
                return redirect()->back()->with('error', 'This member has already used all sessions.');
            }

            $plan->remaining_sessions -= 1;
            $plan->save();
        }

        // Handle monthly member: check date range
        if ($member->type === 'monthly') {
            $plan = $member->monthlyPlan;
            if (!$plan) {
                return redirect()->back()->with('error', 'No monthly plan assigned to this member.');
            }

            $visitDate = date('Y-m-d', strtotime($validated['visit_time']));
            if ($visitDate < $plan->start_date || $visitDate > $plan->end_date) {
                return redirect()->back()->with('error', 'Visit date is outside the monthly plan period.');
            }
        }

        // Save the visit
        Visit::create([
            'member_id'   => $validated['member_id'],
            'visit_time'  => $validated['visit_time'],
            'lock_number' => $validated['lock_number'],
        ]);

        return redirect()->route('visits.index')->with('success', 'Visit logged successfully.');
    }

    /**
     * Remove the specified visit.
     */
    public function destroy(Visit $visit)
    {
        // Optional: for sessional, refund the session
        if ($visit->member->type === 'sessional' && $visit->member->sessionalPlan) {
            $plan = $visit->member->sessionalPlan;
            $plan->remaining_sessions += 1;
            $plan->save();
        }

        $visit->delete();

        return redirect()->route('visits.index')->with('success', 'Visit deleted successfully.');
    }
}
