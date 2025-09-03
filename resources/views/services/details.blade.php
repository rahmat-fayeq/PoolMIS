<x-layouts.app :title="__('Service Details')">
    <p class="font-bold text-2xl">Item Name: {{ $service->name }}</p>
    <p class="font-bold text-2xl">Item Price: {{ $service->price }} <small>Af</small></p>

    <hr class="my-2"/>

    <div>
        <p class="font-semibold italic text-lg">Members who used this service</p>
        <table class="border-collapse border border-gray-300 w-full">
            <tr class=" font-semibold">
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Date</th>
                <th class="border px-4 py-2">Member Name</th>
                <th class="border px-4 py-2">Quantity</th>
                <th class="border px-4 py-2">Total</th>
            </tr>
            @forelse($serviceRecords as $index=>$sr)
            <tr>
                <td class="border px-4 py-2 text-center">{{ $index+1 }}</td>
                <td class="border px-4 py-2 text-center">{{ $sr->service_date->format('Y/m/d')}}</td>
                <td class="border px-4 py-2 text-center">{{ $sr->member->name ?? $sr->member->dailyPlan->lock_number}}</td>
                <td class="border px-4 py-2 text-center">{{ $sr->quantity}}</td>
                <td class="border px-4 py-2 text-center">{{ $sr->total_price }} <small>Af</small></td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No service has been taken</td>
            </tr>
            @endforelse
        </table>
    </div>
</x-layouts.app>
