<x-layouts.app :title="__('Dashboard')">
    <h1 class="text-2xl font-bold mb-4">Overview</h1>

    {{-- Quick Filters --}}
    <p class="mb-4">
        <a href="{{ route('overview') }}" class="mr-2 text-blue-600">All</a>
        <a href="?from={{ date('Y-m-d') }}&to={{ date('Y-m-d') }}" class="mr-2 text-blue-600">Today</a>
        <a href="?from={{ date('Y-m-d', strtotime('-7 days')) }}&to={{ date('Y-m-d') }}" class="mr-2 text-blue-600">Last 7
            Days</a>
        <a href="?from={{ date('Y-m-01') }}&to={{ date('Y-m-t') }}" class="text-blue-600">This Month</a>
    </p>

    <h2 class="text-xl font-semibold mb-2">Member Stats</h2>
    <table id="memberStatsTable" class="w-full text-sm text-left text-gray-500">
        <thead>
            <tr class="font-semibold bg-gray-100">
                <th class="border px-4 py-2">Type</th>
                <th class="border px-4 py-2">Count</th>
                <th class="border px-4 py-2">Total Registered</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-4 py-2">Sessional</td>
                <td class="border px-4 py-2">{{ $memberStats['sessional']['count'] }}</td>
                <td class="border px-4 py-2">{{ number_format($memberStats['sessional']['total'], 2) }}</td>
            </tr>
            <tr>
                <td class="border px-4 py-2">Monthly</td>
                <td class="border px-4 py-2">{{ $memberStats['monthly']['count'] }}</td>
                <td class="border px-4 py-2">{{ number_format($memberStats['monthly']['total'], 2) }}</td>
            </tr>
            <tr>
                <td class="border px-4 py-2">Daily</td>
                <td class="border px-4 py-2">{{ $memberStats['daily']['count'] }}</td>
                <td class="border px-4 py-2">{{ number_format($memberStats['daily']['total'], 2) }}</td>
            </tr>
            <tr class="font-bold">
                <td class="border px-4 py-2">Grand Total</td>
                <td class="border px-4 py-2">{{ $grandTotal['count'] }}</td>
                <td class="border px-4 py-2">{{ number_format($grandTotal['total'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <br class="my-5" />

    {{-- Revenue by Service --}}
    <h2 class="text-xl font-semibold mb-2">Revenue by Service</h2>

    <div class="relative overflow-x-auto  sm:rounded-lg">
        <table id="serviceTable" class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3">Service</th>
                    <th scope="col" class="px-6 py-3">Total Count</th>
                    <th scope="col" class="px-6 py-3">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $s)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <a href="{{ route('services.details', $s->id) }}"
                                class="text-blue-600">{{ $s->name }}</a>
                        </td>
                        <td class="px-6 py-4">{{ $s->totalCount }}</td>
                        <td class="px-6 py-4">{{ number_format($s->totalRevenue, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <br class="my-5" />

    {{-- Revenue by Member --}}
    <h2 class="text-xl font-semibold mb-2">Revenue by Member</h2>

    <div class="relative overflow-x-auto  sm:rounded-lg mb-6">
        <table id="memberTable" class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Type</th>
                    <th scope="col" class="px-6 py-3">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $m)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <a href="{{ route('members.details', $m->id) }}"
                                class="text-blue-600">{{ $m->name }}</a>
                        </td>
                        <td class="px-6 py-4">{{ ucfirst($m->type) }}</td>
                        <td class="px-6 py-4">{{ number_format($m->totalRevenue, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-layouts.app>
