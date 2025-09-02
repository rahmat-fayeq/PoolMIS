<x-layouts.app :title="__('Visits')">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Visit Logs</h1>
        <a href="{{ route('visits.create') }}">
            <x-button type="primary">+ Log New Visit</x-button>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- Sessional Visits --}}
    <h2 class="text-lg font-semibold mt-8 mb-3">Sessional Visits</h2>
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Member</th>
                    <th class="px-4 py-2 text-left">Visit Time</th>
                    <th class="px-4 py-2 text-left">Lock Number</th>
                    <th class="px-4 py-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessionalVisits as $v)
                    <tr class="border-t dark:border-gray-600">
                        <td class="px-4 py-2">{{ $v->member->name }}</td>
                        <td class="px-4 py-2">{{ $v->visit_time }}</td>
                        <td class="px-4 py-2">{{ $v->lock_number }}</td>
                        <td class="px-4 py-2">
                            <form method="POST" action="{{ route('visits.destroy', $v->id) }}">
                                @csrf @method('DELETE')
                                <x-button type="danger" size="sm">Delete</x-button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-3 text-center text-gray-500">No sessional visits yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Monthly Visits --}}
    <h2 class="text-lg font-semibold mt-8 mb-3">Monthly Visits</h2>
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Member</th>
                    <th class="px-4 py-2 text-left">Visit Time</th>
                    <th class="px-4 py-2 text-left">Lock Number</th>
                    <th class="px-4 py-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monthlyVisits as $v)
                    <tr class="border-t dark:border-gray-600">
                        <td class="px-4 py-2">{{ $v->member->name }}</td>
                        <td class="px-4 py-2">{{ $v->visit_time }}</td>
                        <td class="px-4 py-2">{{ $v->lock_number }}</td>
                        <td class="px-4 py-2">
                            <form method="POST" action="{{ route('visits.destroy', $v->id) }}">
                                @csrf @method('DELETE')
                                <x-button type="danger" size="sm">Delete</x-button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-3 text-center text-gray-500">No monthly visits yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.app>
