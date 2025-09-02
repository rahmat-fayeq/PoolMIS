<x-layouts.app :title="__('Members')">

    <div class="mb-6 flex justify-end items-center">
        <x-search url="{{ route('members.daily') }}" placeholder="Search ..." />
    </div>

<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    #
                </th>
                <th scope="col" class="px-6 py-3">
                    Lock Number
                </th>
                <th scope="col" class="px-6 py-3">
                    Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Phone
                </th>
                <th scope="col" class="px-6 py-3">
                    Plan Info
                </th>
                <th scope="col" class="px-6 py-3">
                    Expenses
                </th>
                <th scope="col" class="px-6 py-3">
                    Edit
                </th>
                <th scope="col" class="px-6 py-3">
                    Delete file
                </th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $index=>$m)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ ($members->currentPage() - 1) * $members->perPage() + $index + 1 }}
                </th>
                <td class="px-6 py-4">
                    {{$m->dailyPlan?->lock_number}}
                </td>
                <td class="px-6 py-4">
                    {{$m->name}}
                </td>
                <td class="px-6 py-4">
                    {{$m->phone}}
                </td>
                <td class="px-6 py-4">
                    @if($m->type === 'sessional' && $m->sessionalPlan)
                        Total Sessions: {{ $m->sessionalPlan->total_sessions }}<br>
                        Remaining: {{ $m->sessionalPlan->remaining_sessions }}<br>
                        Price: {{ number_format($m->sessionalPlan->price, 2) }}
                    @elseif($m->type === 'monthly' && $m->monthlyPlan)
                        From: {{ $m->monthlyPlan->start_date }}<br>
                        To: {{ $m->monthlyPlan->end_date }}<br>
                        Price: {{ number_format($m->monthlyPlan->price, 2) }}
                    @elseif($m->type === 'daily' && $m->dailyPlan)
                        Date: {{ $m->dailyPlan?->date }}<br>
                        Price: {{ number_format($m->dailyPlan?->price, 2) }}
                    @else
                        <em>No plan assigned.</em>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <x-button tag="a" href="/members/{{ $m->id }}/expenses"  
                        type="info"
                        class="px-3 py-1 rounded inline-flex items-center justify-center">
                        <i class="fa-regular fa-eye"></i>
                    </x-button>
                </td>
                <td class="px-6 py-4">
                   <x-button tag="a" href="{{ route('members.edit', $m->id) }}" 
                        type="warning" 
                        class="px-3 py-1 rounded inline-flex items-center justify-center">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </x-button>

                </td>
                <td class="px-6 py-4">
                    <x-delete-item id="{{ $m->id }}" url="{{ route('members.destroy', $m->id) }}" />
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $members->links() }}
</div>


</x-layouts.app>
