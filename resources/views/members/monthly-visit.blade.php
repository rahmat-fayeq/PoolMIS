<x-layouts.app :title="__('Members')">
    <div class="mb-6 flex flex-col-reverse gap-3 md:flex-row justify-between items-center">

        {{-- Dynamic Add Form --}}
        <form method="POST"
            action="{{ $member->type === 'monthly' ? route('members.monthlyVisit.store', $member->id) : route('members.sessionalVisit.store', $member->id) }}"
            class="flex justify-between items-center">
            @csrf

            <x-forms.input type="number" label="Lock Number" name="lock_number" required />
            <x-forms.input type="datetime-local" label="Visit Date" name="visit_time" required />

            <x-button type="primary"><i class="fa fa-plus" aria-hidden="true"></i> Add</x-button>
        </form>

        {{-- Filter Form --}}
        <form method="GET" action="" class="flex justify-between items-center">
            <input type="date" name="visit_date" value="{{ request('visit_date') }}" required
                class="w-full mr-1 px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <x-button type="info"><i class="fa fa-filter" aria-hidden="true"></i> Filter</x-button>
        </form>
    </div>

    <div class="relative overflow-x-auto">
        <div class="flex justify-between items-center mb-2">
            <p class="font-semibold">Visits for "{{ $member->name }}"</p>
            <p class="font-semibold">{{ ucfirst($member->type) }} </p>
            <p class="font-semibold">Total Visits:
                {{ $member->type === 'monthly' ? $member->monthlyVisits->count() : $member->sessionalVisits->count() }}
            </p>
        </div>

        @php
            $visits = $member->type === 'monthly' ? $member->monthlyVisits : $member->sessionalVisits;
            if (request('visit_date')) {
                $visits = $visits->filter(fn($v) => $v->visit_time->toDateString() === request('visit_date'));
            }
        @endphp

        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">Lock Number</th>
                    <th scope="col" class="px-6 py-3">Visit Date</th>
                    <th scope="col" class="px-6 py-3">Visit Time</th>
                    <th scope="col" class="px-6 py-3">Delete</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($visits as $index => $visit)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th class="px-6 py-4">{{ $index + 1 }}</th>
                        <td class="px-6 py-4">{{ $visit->lock_number }}</td>
                        <td class="px-6 py-4">{{ $visit->visit_time->format('Y/m/d') }}</td>
                        <td class="px-6 py-4">{{ $visit->visit_time->format('h:i:s A') }}</td>
                        <td class="px-6 py-4">
                            <x-delete-item id="{{ $visit->id }}"
                                url="{{ route('members.deleteMonthlyVisit', $visit->id) }}" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center">No visits found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
