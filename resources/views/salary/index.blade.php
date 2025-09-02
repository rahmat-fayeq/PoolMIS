<x-layouts.app :title="__('Salary')">

    <div class="mb-6 flex justify-between items-center">
        <x-button tag="a" href="{{route('salary.create')}}" type="primary"><i class="fas fa-plus-circle"></i> Add New</x-button>
        <p class="font-light">Total Amount: {{$salaries->sum('amount')}} Af</p>
        <x-search url="{{ route('salary.index') }}" placeholder="Search ..." />
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
                    Full Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Father Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Job
                </th>
                <th scope="col" class="px-6 py-3">
                    Phone
                </th>
                <th scope="col" class="px-6 py-3">
                    Amount
                </th>
                <th scope="col" class="px-6 py-3" colspan="2">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salaries as $index=>$data)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ ($salaries->currentPage() - 1) * $salaries->perPage() + $index + 1 }}
                </th>
                <td class="px-6 py-4">
                    {{$data->submit_date}}
                </td>
                <td class="px-6 py-4">
                    {{$data->full_name}}
                </td>
                <td class="px-6 py-4">
                    {{$data->father_name}}
                </td>
                <td class="px-6 py-4">
                    {{$data->job}}
                </td>
                <td class="px-6 py-4">
                    {{$data->phone}}
                </td>
                <td class="px-6 py-4">
                    {{$data->amount}}
                </td>
                <td class="px-6 py-4">
                   <x-button tag="a" href="{{ route('salary.edit', $data->id) }}" 
                        type="warning" 
                        class="px-3 py-1 rounded inline-flex items-center justify-center">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </x-button>

                </td>
                <td class="px-6 py-4">
                    <x-delete-item id="{{ $data->id }}" url="{{ route('salary.destroy', $data->id) }}" />
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $salaries->links() }}
</div>


</x-layouts.app>
