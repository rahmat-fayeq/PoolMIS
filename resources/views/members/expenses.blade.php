<x-layouts.app :title="__('Expenses')">
    <div class="mb-6 flex flex-col-reverse gap-3 md:flex-row justify-between items-center">

        {{-- Dynamic Add/Edit Form --}}
        <form method="POST"
            action="{{ isset($serviceItem) ? route('members.updateService', [$member->id, $serviceItem->id]) : route('members.addService', $member->id) }}"
            class="flex justify-between items-center">
            @csrf
            @if (isset($serviceItem))
                @method('PUT')
            @endif

            <select name="service_id"
                class="w-full mr-1 px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required>
                <option value="">Select item</option>
                @foreach ($services as $s)
                    <option value="{{ $s->id }}"
                        {{ isset($serviceItem) && $serviceItem->service_id == $s->id ? 'selected' : '' }}>
                        {{ $s->name }} ({{ number_format($s->price, 2) }})
                    </option>
                @endforeach
            </select>

            <x-forms.input type="number" label="" min="1" name="quantity"
                value="{{ isset($serviceItem) ? $serviceItem->quantity : 1 }}" required />

            <x-button type="primary"><i class="fa fa-plus" aria-hidden="true"></i>
                {{ isset($serviceItem) ? 'Update' : 'Add' }}</x-button>
        </form>

        {{-- Filter Form --}}
        <form method="GET" action="" class="flex justify-between items-center">
            <input type="date" name="service_date" value="{{ request('service_date') }}"
                class="w-full mr-1 px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <x-button type="info"><i class="fa fa-filter" aria-hidden="true"></i> Filter</x-button>
        </form>

        {{-- Print Receipt Button --}}
        <x-button tag="a"
            href="{{ route('members.printReceipt', $member->id) }}?service_date={{ request('service_date') }}"
            target="_blank" type="primary">
            <i class="fa fa-print mr-1" aria-hidden="true"></i> Print Receipt
        </x-button>

    </div>

    <div class="relative overflow-x-auto">
        <div class="flex justify-between items-center">
            <p class="font-semibold">Expenses for "{{ $member->name }}"</p>
            <p class="font-semibold">{{ ucfirst($member->type) }}</p>
            <p class="font-semibold">Total: {{ $total }} AF</p>
        </div>

        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">Date</th>
                    <th scope="col" class="px-6 py-3">Service</th>
                    <th scope="col" class="px-6 py-3">Quantity</th>
                    <th scope="col" class="px-6 py-3">Total</th>
                    <th scope="col" class="px-6 py-3">Edit</th>
                    <th scope="col" class="px-6 py-3">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $index => $e)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th class="px-6 py-4">{{ $index + 1 }}</th>
                        <td class="px-6 py-4">{{ $e->service_date }}</td>
                        <td class="px-6 py-4">{{ $e->service->name }}</td>
                        <td class="px-6 py-4">{{ $e->quantity }}</td>
                        <td class="px-6 py-4">{{ $e->total_price }}</td>
                        <td class="px-6 py-4">
                            <x-button tag="a" href="{{ route('members.editService', [$member->id, $e->id]) }}"
                                type="warning" class="px-3 py-1 rounded inline-flex items-center justify-center">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </x-button>
                        </td>
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
