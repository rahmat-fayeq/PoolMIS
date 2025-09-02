<x-layouts.app :title="__('Add Member')">

    <div class="flex justify-between items-center mb-3 p-3">
        <div>
            <p class="font-bold">Full Name: {{ ucfirst($member->name) }}</p>
            <p class="font-bold">Phone: {{ $member->phone }}</p>
            <p class="font-bold">Email: {{ $member->email }}</p>
            <p class="font-bold">Type: {{ $member->type }}</p>
        </div>
        <div>
            <p class="font-bold">Plan</p>
            <p class="font-semibold">
                @if($member->type == 'sessional' && $member->sessionalPlan)
                Total Sessions: {{ $member->sessionalPlan->total_sessions }} <br>
                Remaining Sessions: {{ $member->sessionalPlan->remaining_sessions }} <br>
                Price: {{ $member->sessionalPlan->price }} <small>Af</small>
                @elseif($member->type == 'monthly' && $member->monthlyPlan)
                    Start: {{ $member->monthlyPlan->start_date }} <br>
                    End: {{ $member->monthlyPlan->end_date }} <br>
                    Price: {{ $member->monthlyPlan->price }} <small>Af</small>
                @elseif($member->type == 'daily' && $member->dailyPlan)
                    Date: {{ $member->dailyPlan->date }} <br>
                    Price: {{ $member->dailyPlan->price }} <small>Af</small>
                @endif
            </p>
        </div>
    </div>

    <div>
        <p class="font-semibold italic text-lg">Visits</p>
        <table class="border-collapse border border-gray-300 w-full">
            <tr class=" font-semibold">
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Lock Number</th>
                <th class="border px-4 py-2">Date</th>
                <th class="border px-4 py-2">Time</th>
            </tr>

            @if($member->type == 'sessional')
                @forelse($member->sessionalVisits as $index=>$visit)
                <tr>
                    <td class="border px-4 py-2 text-center">{{ $index+1 }}</td>
                    <td class="border px-4 py-2 text-center">{{ $visit->lock_number }}</td>
                    <td class="border px-4 py-2 text-center">{{ $visit->visit_time }}</td>
                    <td class="border px-4 py-2 text-center">{{ $visit->visit_time }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">No visit</td>
                </tr>    
                @endforelse
            @elseif($member->type == 'monthly')
                @forelse($member->monthlyVisits as $visit)
                <tr>
                    <td class="border px-4 py-2 text-center">{{ $index+1 }}</td>
                    <td class="border px-4 py-2 text-center">{{ $visit->lock_number }}</td>
                    <td class="border px-4 py-2 text-center">{{ $visit->visit_time }}</td>
                    <td class="border px-4 py-2 text-center">{{ $visit->visit_time }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">No visit</td>
                </tr>
                @endforelse
            @endif
        </table>
    </div>
    <br>
    <div>
        <p class="font-semibold italic text-lg">Services</p>
        <table class="border-collapse border border-gray-300 w-full">
            <tr class=" font-semibold">
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Date</th>
                <th class="border px-4 py-2">Item Name</th>
                <th class="border px-4 py-2">Quantity</th>
                <th class="border px-4 py-2">Price</th>
                <th class="border px-4 py-2">Total</th>
            </tr>
            @forelse($member->services as $index=>$ms)
            <tr>
                <td class="border px-4 py-2 text-center">{{ $index+1 }}</td>
                <td class="border px-4 py-2 text-center">{{ $ms->service_date }}</td>
                <td class="border px-4 py-2 text-center">{{ $ms->service->name }}</td>
                <td class="border px-4 py-2 text-center">{{ $ms->quantity }}</td>
                <td class="border px-4 py-2 text-center">{{ $ms->service->price }} <small>Af</small></td>
                <td class="border px-4 py-2 text-center">{{ $ms->total_price }} <small>Af</small></td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No service has been taken</td>
            </tr>
            @endforelse
        </table>
    </div>
</x-layouts.app>
