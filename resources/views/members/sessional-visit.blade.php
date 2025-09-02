<x-layouts.app :title="__('Members')">
    <div class="mb-6 flex flex-col-reverse gap-3 md:flex-row justify-between items-center">

        {{-- Dynamic Add Form --}}
        <form method="POST" action="{{ route('members.sessionalVisit.store', $member->id) }}"
            class="flex justify-between items-center">
            @csrf
            @if (isset($serviceItem))
                @method('PUT')
            @endif
            <div>
                <label>Guest</label>
                <x-forms.input type="number" label="" value="0" min="0" name="guest" required />
            </div>
            <div class="ml-2">
                <label>Lock Number</label>
                <x-forms.input type="number" label="" min="0" name="lock_number" required />
            </div>
            <div class="ml-2">
                <label>Visit Date</label>
                <x-forms.input type="datetime-local" label="" name="visit_time" required />
            </div>


            <x-button class="mt-6" type="primary"><i class="fa fa-plus" aria-hidden="true"></i>
                {{ isset($serviceItem) ? 'Update' : 'Add' }}</x-button>
        </form>

        {{-- Filter Form --}}
        <form method="GET" action="" class="flex justify-between items-center">
            <input type="date" name="visit_time" value="{{ request('visit_time') }}" required
                class="w-full mr-1 px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <x-button type="info"><i class="fa fa-filter" aria-hidden="true"></i> Filter</x-button>
        </form>
    </div>

    <div class="relative overflow-x-auto">
        <div class="flex justify-between items-center">
            <p class="font-semibold">Visits for "{{ $member->name }}"</p>
            <p class="font-semibold">{{ ucfirst($member->type) }}</p>
            <p class="font-semibold">Total Visits: {{ $member->sessionalVisits->count() }}</p>
        </div>

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
                @foreach ($member->sessionalVisits as $index => $m)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th class="px-6 py-4">{{ $index + 1 }}</th>
                        <td class="px-6 py-4">{{ $m->lock_number }}</td>
                        <td class="px-6 py-4">{{ $m->visit_time->format('Y/m/d') }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($m->visit_time)->format('h:i:s A') }}</td>
                        <td class="px-6 py-4">
                            <x-delete-item id="{{ $m->id }}"
                                url="{{ route('members.deleteSessionalVisit', $m->id) }}" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.app>
