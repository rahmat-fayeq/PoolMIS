<x-layouts.app :title="__('Expenses')">

    <div class="mb-6 flex justify-between items-center">
        <x-button tag="a" href="{{route('expenses.create')}}" type="primary"><i class="fas fa-plus-circle"></i> Add New</x-button>
        <p class="font-light">Total Amount: {{$expenses->sum('amount')}} Af</p>
        <x-search url="{{ route('expenses.index') }}" placeholder="Search ..." />
    </div>

<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    #
                </th>
                <th scope="col" class="px-6 py-3">
                    Date
                </th>
                <th scope="col" class="px-6 py-3">
                   Title
                </th>
                <th scope="col" class="px-6 py-3">
                    Amount
                </th>
                <th scope="col" class="px-6 py-3">
                    Delete
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $index=>$data)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ ($expenses->currentPage() - 1) * $expenses->perPage() + $index + 1 }}
                </th>
                <td class="px-6 py-4">
                    {{$data->expense_date}}
                </td>
                <td class="px-6 py-4">
                    {{$data->title}}
                </td>
                <td class="px-6 py-4">
                    {{$data->amount}} <small>Af</small>
                </td>
                <td class="px-6 py-4">
                    <x-delete-item id="{{ $data->id }}" url="{{ route('expenses.destroy', $data->id) }}" />
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $expenses->links() }}
</div>


</x-layouts.app>
