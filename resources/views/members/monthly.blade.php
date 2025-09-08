<x-layouts.app :title="__('Members')">

    <div class="mb-6 flex justify-end items-center">
        <x-search url="{{ route('members.monthly') }}" placeholder="Search ..." />
    </div>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        #
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Price
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Event Logs
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Expenses
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Edit
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Delete
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $index => $m)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ ($members->currentPage() - 1) * $members->perPage() + $index + 1 }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $m->name }} <br>
                            {{ $m->phone }}
                        </td>
                        <td class="px-6 py-4">
                            From: {{ $m->monthlyPlan->start_date->format('Y-m-d') }}<br>
                            To: {{ $m->monthlyPlan->end_date->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ number_format($m->monthlyPlan->price, 0) }}
                        </td>
                        <td class="px-6 py-4">
                            <x-button tag="a" href="{{ route('members.monthlyVisit', $m->id) }}" type="primary"
                                class="px-3 py-1 rounded inline-flex items-center justify-center">
                                <i class="fa fa-check-circle" aria-hidden="true"></i>
                            </x-button>
                        </td>
                        <td class="px-6 py-4">
                            <x-button tag="a" href="/members/{{ $m->id }}/expenses" type="info"
                                class="px-3 py-1 rounded inline-flex items-center justify-center">
                                <i class="fa-regular fa-eye"></i>
                            </x-button>
                        </td>
                        <td class="px-6 py-4">
                            <x-button tag="a" href="{{ route('members.edit', $m->id) }}" type="warning"
                                class="px-3 py-1 rounded inline-flex items-center justify-center">
                                <i class="fa-regular fa-edit"></i>
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
