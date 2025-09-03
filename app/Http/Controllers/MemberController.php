<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::with([
            'sessionalPlan',
            'monthlyPlan',
            'dailyPlan',
            'sessionalVisits',
            'monthlyVisits',
            'services.service',
        ])
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('phone', 'LIKE', '%' . $request->search . '%');
            })
            ->where('type', 'sessional')
            ->latest()
            ->paginate()
            ->withQueryString();

        $services = Service::orderBy('name')->get();

        return view('members.index', compact('members', 'services'));
    }

    public function memberMonthly(Request $request)
    {
        $members = Member::with([
            'sessionalPlan',
            'monthlyPlan',
            'dailyPlan',
            'sessionalVisits',
            'monthlyVisits',
            'services.service',
        ])
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('phone', 'LIKE', '%' . $request->search . '%');
            })
            ->where('type', 'monthly')
            ->latest()
            ->paginate()
            ->withQueryString();

        $services = Service::orderBy('name')->get();

        return view('members.monthly', compact('members', 'services'));
    }

    public function memberDaily(Request $request)
    {
        $members = Member::with([
            'dailyPlan',
            'services.service',
        ])
            ->when($request->has('search'), function ($query) use ($request) {
                $searchTerm = $request->search;
                $query->where(function ($query) use ($searchTerm) {
                    $query->orWhereHas('dailyPlan', function ($q) use ($searchTerm) {
                        $q->where('date', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('lock_number', '=', $searchTerm);
                    });
                });
            })
            ->where('type', 'daily')
            ->latest('updated_at')
            ->paginate()
            ->withQueryString();

        $services = Service::orderBy('name')->get();

        return view('members.daily', compact('members', 'services'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:sessional,monthly,daily',
            'total_sessions' => 'required_if:type,sessional|integer|min:1',
            'price' => 'required_if:type,sessional,monthly,daily|numeric|min:0',
            'lock_number' => 'required_if:type,daily|string',
        ]);

        $validator->sometimes('name', 'required|string|min:3|max:255', function ($input) {
            return $input->type == 'monthly';
        });

        $validator->sometimes(['start_date', 'end_date'], 'required|date', function ($input) {
            return $input->type == 'monthly';
        });
        $validator->sometimes('end_date', 'after_or_equal:start_date', function ($input) {
            return $input->type == 'monthly';
        });

        $validator->validate();

        $member = Member::create($request->only('name', 'phone', 'type'));

        if ($request->type == 'sessional') {
            $member->sessionalPlan()->create([
                'total_sessions' => $request->total_sessions,
                'remaining_sessions' => $request->total_sessions,
                'price' => $request->price,
            ]);
        } elseif ($request->type == 'monthly') {
            $member->monthlyPlan()->create([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'price' => $request->price,
            ]);
        } elseif ($request->type == 'daily') {
            $member->dailyPlan()->create([
                'date' => now(),
                'price' => $request->price,
                'lock_number' => $request->lock_number,
            ]);
        }

        return redirect()->route('members.create')->with('success', 'Member created successfully!');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->back()->with('success', 'Member deleted successfully!');
    }


    // Add service to member
    // public function addService(Request $request, Member $member)
    // {
    //     $request->validate([
    //         'service_id' => 'required|exists:services,id',
    //         'quantity' => 'required|integer|min:1',
    //     ]);

    //     $service = Service::find($request->service_id);
    //     $member->services()->create([
    //         'service_id' => $service->id,
    //         'quantity' => $request->quantity,
    //         'total_price' => $service->price * $request->quantity,
    //         'service_date' => now()->toDateString(),
    //     ]);

    //     return redirect()->back()->with('success', 'Service added successfully!');
    // }
    public function addService(Request $request, Member $member)
    {
        $request->validate([
            'service_id' => 'nullable|exists:services,id|required_without:total_expense',
            'quantity' => 'nullable|integer|min:1|required_with:service_id',
            'total_expense' => 'nullable|numeric|min:0|required_without:service_id',
        ]);

        if ($request->filled('total_expense')) {
            // Add total expense as a generic service item
            $member->services()->create([
                'service_id' => null,
                'quantity' => 1,
                'total_price' => $request->total_expense,
                'service_date' => now()->toDateString(),
            ]);
        } else {
            $service = Service::findOrFail($request->service_id);
            $member->services()->create([
                'service_id' => $service->id,
                'quantity' => $request->quantity,
                'total_price' => $service->price * $request->quantity,
                'service_date' => now()->toDateString(),
            ]);
        }

        return redirect()->back()->with('success', 'Service/Expense added successfully!');
    }


    // Edit service item form
    public function editService(Member $member, $serviceId)
    {
        $serviceItem = $member->services()->findOrFail($serviceId);
        $services = Service::orderBy('name')->get();
        $expenses = $member->services()->with('service')->get();
        $total = $expenses->sum('total_price');

        return view('members.expenses', compact('member', 'serviceItem', 'services', 'expenses', 'total'));
    }

    // Update service item
    public function updateService(Request $request, Member $member, $serviceId)
    {
        $serviceItem = $member->services()->findOrFail($serviceId);

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $service = Service::findOrFail($request->service_id);

        $serviceItem->update([
            'service_id' => $service->id,
            'quantity' => $request->quantity,
            'total_price' => $service->price * $request->quantity,
            'service_date' => now()->toDateString(),
        ]);

        return redirect()->route('members.expenses', $member->id)
            ->with('success', 'Service updated successfully!');
    }

    // Delete service item
    public function deleteService(Member $member, $serviceId)
    {
        $serviceItem = $member->services()->findOrFail($serviceId);
        $serviceItem->delete();

        return redirect()->route('members.expenses', $member->id)
            ->with('success', 'Service deleted successfully!');
    }

    // Show expenses with optional date filter
    public function expenses(Request $request, Member $member)
    {
        $serviceDate = $request->service_date ?? now()->format('Y-m-d');

        $query = $member->services()->with('service')
            ->whereDate('service_date', $serviceDate);

        $expenses = $query->get();
        $total = $expenses->sum('total_price');

        $services = Service::orderBy('name')->get();

        $member->load('dailyPlan');

        return view('members.expenses', compact('member', 'expenses', 'total', 'services', 'serviceDate'));
    }



    // Member details
    public function details($id)
    {
        $member = Member::with([
            'sessionalPlan',
            'monthlyPlan',
            'dailyPlan',
            'sessionalVisits',
            'monthlyVisits',
            'services.service',
            'dailyVisit',
        ])->findOrFail($id);

        return view('members.details', compact('member'));
    }

    public function printReceipt(Member $member, Request $request)
    {
        // Filter expenses by service_date if provided
        $query = $member->expenses()->with('service');

        if ($request->filled('service_date')) {
            $query->whereDate('service_date', '=', $request->service_date);
        }

        $expenses = $query->get();

        // Total amount
        $totalAmount = $expenses->sum('total_price');

        // Generate a unique receipt number
        $receiptNumber = 'RCPT-' . now()->format('YmdHis');

        // Determine the correct date, time, and lock number based on member type
        $date = now();
        $time = now();
        $lockNumber = 'N/A';

        if ($request->filled('service_date')) {
            $serviceDate = $request->service_date;

            switch ($member->type) {
                case 'daily':
                    if ($member->dailyPlan) {
                        $date = $member->dailyPlan->date;
                        $time = $member->dailyPlan->date; // if you have time stored separately, adjust
                        $lockNumber = $member->dailyPlan->lock_number ?? 'N/A';
                    }
                    break;

                case 'monthly':
                    $monthlyVisit = $member->monthlyVisits()
                        ->whereDate('visit_time', $serviceDate)
                        ->first();
                    if ($monthlyVisit) {
                        $date = $monthlyVisit->visit_time;
                        $time = $monthlyVisit->visit_time;
                        $lockNumber = $monthlyVisit->lock_number ?? 'N/A';
                    }
                    break;

                case 'sessional':
                    $sessionalVisit = $member->sessionalVisits()
                        ->whereDate('visit_time', $serviceDate)
                        ->first();
                    if ($sessionalVisit) {
                        $date = $sessionalVisit->visit_time;
                        $time = $sessionalVisit->visit_time;
                        $lockNumber = $sessionalVisit->lock_number ?? 'N/A';
                    }
                    break;
            }
        }

        return view('members.receipt', compact('member', 'expenses', 'totalAmount', 'receiptNumber', 'date', 'time', 'lockNumber'));
    }

    // public function printReceipt(Member $member, Request $request)
    // {
    //     // Filter expenses by date only
    //     $query = $member->expenses()->with('service');

    //     if ($request->filled('service_date')) {
    //         $query->whereDate('service_date', '=', $request->service_date);
    //     }


    //     $expenses = $query->get();

    //     // Total amount
    //     $totalAmount = $expenses->sum('total_price');

    //     // Generate a unique receipt number
    //     $receiptNumber = 'RCPT-' . now()->format('YmdHis');

    //     // Default lock number
    //     $lockNumber = 'N/A';
    //     // Only resolve lock number if service_date filter is applied
    //     if ($request->filled('service_date') && $expenses->isNotEmpty()) {
    //         $serviceDate = $request->service_date;

    //         switch ($member->type) {
    //             case 'daily':
    //                 $lockNumber = $member->dailyPlan?->lock_number ?? 'N/A';
    //                 break;

    //             case 'monthly':
    //                 $monthlyVisit = $member->monthlyVisits()
    //                     ->whereDate('visit_time', $serviceDate)
    //                     ->first();
    //                 $lockNumber = $monthlyVisit?->lock_number ?? 'N/A';
    //                 break;

    //             case 'sessional':
    //                 $sessionalVisit = $member->sessionalVisits()
    //                     ->whereDate('visit_time', $serviceDate)
    //                     ->first();
    //                 $lockNumber = $sessionalVisit?->lock_number ?? 'N/A';
    //                 break;
    //         }
    //     }

    //     return view('members.receipt', compact('member', 'expenses', 'totalAmount', 'receiptNumber', 'lockNumber'));
    // }
}
