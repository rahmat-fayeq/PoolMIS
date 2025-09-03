<x-layouts.app :title="__('Expenses')">
    <div class="mb-6 flex flex-col-reverse gap-3 md:flex-row justify-between items-center">

        {{-- Dynamic Add/Edit Form --}}
        <form method="POST"
            action="{{ isset($serviceItem) ? route('members.updateService', [$member->id, $serviceItem->id]) : route('members.addService', $member->id) }}"
            class="flex flex-wrap gap-2 items-end">
            @csrf
            @if (isset($serviceItem))
                @method('PUT')
            @endif

            {{-- Select existing service --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Select Item</label>
                <select name="service_id"
                    class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select item</option>
                    @foreach ($services as $s)
                        <option value="{{ $s->id }}"
                            {{ isset($serviceItem) && $serviceItem->service_id == $s->id ? 'selected' : '' }}>
                            {{ $s->name }} ({{ number_format($s->price, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Quantity --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Quantity</label>
                <x-forms.input type="number" label="" min="1" name="quantity"
                    value="{{ isset($serviceItem) ? $serviceItem->quantity : 1 }}" required />
            </div>

            {{-- Total Expense Direct Input --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Total Food</label>
                <x-forms.input type="number" label="" min="0" name="total_expense"
                    value="{{ isset($serviceItem) && !$serviceItem->service ? $serviceItem->total_price : '' }}" />
            </div>

            <x-button type="primary" class="mt-6 flex items-center gap-2">
                <i class="fa fa-plus" aria-hidden="true"></i>
                {{ isset($serviceItem) ? 'Update' : 'Add' }}
            </x-button>
        </form>

        {{-- Filter Form --}}
        <form method="GET" action="" class="flex gap-2 items-end mt-6">
            <input type="date" name="service_date" value="{{ request('service_date') ?? now()->format('Y-m-d') }}"
                class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <x-button type="info" class="flex items-center gap-2">
                <i class="fa fa-filter" aria-hidden="true"></i> Filter
            </x-button>
        </form>

        {{-- Print Receipt Button --}}
        <x-button tag="a"
            href="{{ route('members.printReceipt', $member->id) }}?service_date={{ request('service_date') ?? now()->format('Y-m-d') }}"
            target="_blank" type="primary" class="mt-6 flex items-center gap-2">
            <i class="fa fa-print" aria-hidden="true"></i> Print Receipt
        </x-button>

    </div>

    <div class="relative overflow-x-auto mt-4">
        <div class="flex justify-between items-center mb-2">
            <p class="font-semibold">Expenses for "{{ $member->name?? $member->dailyPlan->lock_number }}"</p>
            <p class="font-semibold">{{ ucfirst($member->type) }}</p>
            <p class="font-semibold">Total: {{ $total }} AF</p>
        </div>

        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">Date</th>
                    <th scope="col" class="px-6 py-3">Service</th>
                    <th scope="col" class="px-6 py-3">Quantity</th>
                    <th scope="col" class="px-6 py-3">Total</th>
                    <th scope="col" class="px-6 py-3">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $index => $e)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th class="px-6 py-4">{{ $index + 1 }}</th>
                        <td class="px-6 py-4">{{ $e->service_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">
                            @if ($e->service)
                                {{ $e->service->name }}
                            @else
                                Total Food
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $e->quantity ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $e->total_price }}</td>
                        <td class="px-6 py-4">
                            <x-delete-item id="{{ $e->id }}"
                                url="{{ route('members.deleteService', [$member->id, $e->id]) }}" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.app>
