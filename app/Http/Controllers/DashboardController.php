<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Service;
use App\Models\MemberService;
use App\Models\Salary;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('from');
        $to   = $request->input('to');

        if ($from) $from = \Carbon\Carbon::parse($from)->startOfDay();
        if ($to)   $to   = \Carbon\Carbon::parse($to)->endOfDay();

        // ---- Members Counts ----
        $sessionalMembers = Member::where('type', 'sessional')->with('sessionalPlan')->get();
        $monthlyMembers   = Member::where('type', 'monthly')->with('monthlyPlan')->get();
        $dailyMembers     = Member::where('type', 'daily')->with('dailyPlan')->get();

        $sessionalMembersCount = $sessionalMembers->filter(
            fn($m) => $m->sessionalPlan &&
                (!$from || !$to || $m->sessionalPlan->created_at->between($from, $to))
        )->count();

        $monthlyMembersCount = $monthlyMembers->filter(
            fn($m) => $m->monthlyPlan &&
                (!$from || !$to || \Carbon\Carbon::parse($m->monthlyPlan->start_date ?? $m->monthlyPlan->created_at)->between($from, $to))
        )->count();

        $dailyMembersCount = $dailyMembers->filter(
            fn($m) => $m->dailyPlan &&
                (!$from || !$to || \Carbon\Carbon::parse($m->dailyPlan->date ?? $m->dailyPlan->created_at)->between($from, $to))
        )->count();

        // ---- Revenue ----
        $sessionalItemRevenue = MemberService::with('member')
            ->whereHas('member', function($query){
                $query->where('type','sessional');
            })
            ->when($request->has('from') && $request->has('to'), function($query)use($from, $to){
                $query->whereBetween('service_date',[$from,$to]);
            })
            ->sum(
              'total_price'
            );

        $monthlyItemRevenue = MemberService::with('member')
        ->whereHas('member', function($query){
            $query->where('type','monthly');
        })
        ->when($request->has('from') && $request->has('to'), function($query)use($from, $to){
            $query->whereBetween('service_date',[$from,$to]);
        })
        ->sum(
            'total_price'
        );
        
        $dailyItemRevenue = MemberService::with('member')
        ->whereHas('member', function($query){
            $query->where('type','daily');
        })
        ->when($request->has('from') && $request->has('to'), function($query)use($from, $to){
            $query->whereBetween('service_date',[$from,$to]);
        })
        ->sum(
            'total_price'
        );    
            

        $sessionalMembersRevenue = $sessionalMembers->sum(
            fn($m) => ($m->sessionalPlan &&
                (!$from || !$to || $m->sessionalPlan->created_at->between($from, $to)))
                ? $m->sessionalPlan->price : 0
        );

        $monthlyMembersRevenue = $monthlyMembers->sum(
            fn($m) => ($m->monthlyPlan &&
                (!$from || !$to || \Carbon\Carbon::parse($m->monthlyPlan->start_date ?? $m->monthlyPlan->created_at)->between($from, $to)))
                ? $m->monthlyPlan->price : 0
        );

        $dailyMembersRevenue = $dailyMembers->sum(
            fn($m) => ($m->dailyPlan &&
                (!$from || !$to || \Carbon\Carbon::parse($m->dailyPlan->date ?? $m->dailyPlan->created_at)->between($from, $to)))
                ? $m->dailyPlan->price : 0
        );

        // ---- Services ----
        $servicesQuery = Service::query();
        $items = $servicesQuery->count();

        $salaryQuery = Salary::query();
        if ($from && $to) {
            $salaryQuery->whereBetween('submit_date', [$from, $to]);
        }
        $salary = $salaryQuery->sum('amount');

        $expenseQuery = Expense::query();
        if ($from && $to) {
            $expenseQuery->whereBetween('expense_date', [$from, $to]);
        }
        $expense = $expenseQuery->sum('amount');

        return view('dashboard.index', [
            'sessionalMembersCount'   => $sessionalMembersCount,
            'monthlyMembersCount'     => $monthlyMembersCount,
            'dailyMembersCount'       => $dailyMembersCount,
            'totalMembers'            => $sessionalMembersCount + $monthlyMembersCount + $dailyMembersCount,
            'sessionalMembersRevenue' => $sessionalMembersRevenue+$sessionalItemRevenue,
            'monthlyMembersRevenue'   => $monthlyMembersRevenue+$monthlyItemRevenue,
            'dailyMembersRevenue'     => $dailyMembersRevenue+$dailyItemRevenue,
            'totalRevenue'            => $dailyItemRevenue+$monthlyItemRevenue+$sessionalItemRevenue+$sessionalMembersRevenue + $monthlyMembersRevenue + $dailyMembersRevenue,
            'items'                   => $items,
            'salary'                  => $salary,
            'expense'                 => $expense,
            'totalExpense'            => $salary+$expense,  
            'from'                    => $from,
            'to'                      => $to,
        ]);
    }

    public function overview(Request $request)
    {
        $from = $request->input('from');
        $to   = $request->input('to');
        $memberId = $request->input('member_id');
        $serviceId = $request->input('service_id');

        if ($from) $from = Carbon::parse($from)->startOfDay();
        if ($to)   $to   = Carbon::parse($to)->endOfDay();

        // ---- Members Revenue ----
        $members = Member::with(['sessionalPlan', 'monthlyPlan', 'dailyPlan', 'services'])
            ->get()
            ->map(function ($member) use ($from, $to) {
                $planRevenue = 0;

                // Sessional Plan (use created_at)
                if ($member->type === 'sessional' && $member->sessionalPlan) {
                    $planDate = $member->sessionalPlan->created_at;
                    if (!$from || !$to || $planDate->between($from, $to)) {
                        $planRevenue = $member->sessionalPlan->price;
                    }
                }

                // Monthly Plan (prefer start_date, fallback created_at)
                if ($member->type === 'monthly' && $member->monthlyPlan) {
                    $planDate = $member->monthlyPlan->start_date
                        ? Carbon::parse($member->monthlyPlan->start_date)
                        : $member->monthlyPlan->created_at;

                    if (!$from || !$to || $planDate->between($from, $to)) {
                        $planRevenue = $member->monthlyPlan->price;
                    }
                }

                // Daily Plan (prefer date, fallback created_at)
                if ($member->type === 'daily' && $member->dailyPlan) {
                    $planDate = $member->dailyPlan->date
                        ? Carbon::parse($member->dailyPlan->date)
                        : $member->dailyPlan->created_at;

                    if (!$from || !$to || $planDate->between($from, $to)) {
                        $planRevenue = $member->dailyPlan->price;
                    }
                }

                // Services revenue
                $serviceQuery = $member->services();
                if ($from && $to) {
                    $serviceQuery->whereBetween('service_date', [$from->toDateString(), $to->toDateString()]);
                }
                $serviceRevenue = $serviceQuery->sum('total_price');

                $member->totalRevenue = $planRevenue + $serviceRevenue;
                return $member;
            });

        // ---- Service Revenue & Count ----
        $services = Service::all()->map(function ($service) use ($from, $to) {
            $records = MemberService::where('service_id', $service->id);
            if ($from && $to) {
                $records->whereBetween('service_date', [$from->toDateString(), $to->toDateString()]);
            }

            $service->totalRevenue = $records->sum('total_price');
            $service->totalCount   = $records->sum('quantity');
            return $service;
        });

        // ---- Filter by dropdowns ----
        if ($memberId) {
            $members = $members->where('id', $memberId);
        }

        if ($serviceId) {
            $services = $services->where('id', $serviceId);
        }

        // ---- Member Stats ----
        $sessionalMembers = Member::where('type', 'sessional')->with('sessionalPlan')->get();
        $monthlyMembers   = Member::where('type', 'monthly')->with('monthlyPlan')->get();
        $dailyMembers     = Member::where('type', 'daily')->with('dailyPlan')->get();

        $memberStats = [
            'sessional' => [
                'count' => $sessionalMembers->filter(
                    fn($m) => $m->sessionalPlan &&
                        (!$from || !$to || $m->sessionalPlan->created_at->between($from, $to))
                )->count(),
                'total' => $sessionalMembers->sum(
                    fn($m) => ($m->sessionalPlan &&
                        (!$from || !$to || $m->sessionalPlan->created_at->between($from, $to)))
                        ? $m->sessionalPlan->price : 0
                ),
            ],
            'monthly' => [
                'count' => $monthlyMembers->filter(
                    fn($m) => $m->monthlyPlan &&
                        (!$from || !$to ||
                            (Carbon::parse($m->monthlyPlan->start_date ?? $m->monthlyPlan->created_at)->between($from, $to)))
                )->count(),
                'total' => $monthlyMembers->sum(
                    fn($m) => ($m->monthlyPlan &&
                        (!$from || !$to ||
                            (Carbon::parse($m->monthlyPlan->start_date ?? $m->monthlyPlan->created_at)->between($from, $to))))
                        ? $m->monthlyPlan->price : 0
                ),
            ],
            'daily' => [
                'count' => $dailyMembers->filter(
                    fn($m) => $m->dailyPlan &&
                        (!$from || !$to ||
                            (Carbon::parse($m->dailyPlan->date ?? $m->dailyPlan->created_at)->between($from, $to)))
                )->count(),
                'total' => $dailyMembers->sum(
                    fn($m) => ($m->dailyPlan &&
                        (!$from || !$to ||
                            (Carbon::parse($m->dailyPlan->date ?? $m->dailyPlan->created_at)->between($from, $to))))
                        ? $m->dailyPlan->price : 0
                ),
            ],
        ];

        // ---- Grand Total ----
        $grandTotal = [
            'count' => $memberStats['sessional']['count']
                + $memberStats['monthly']['count']
                + $memberStats['daily']['count'],
            'total' => $memberStats['sessional']['total']
                + $memberStats['monthly']['total']
                + $memberStats['daily']['total'],
        ];

        // ---- Dropdowns ----
        $memberNames = Member::orderBy('name')->pluck('name', 'id');
        $serviceNames = Service::orderBy('name')->pluck('name', 'id');

        return view('dashboard.overview', compact(
            'from',
            'to',
            'members',
            'services',
            'memberStats',
            'grandTotal',
            'memberNames',
            'serviceNames'
        ));
    }
}
