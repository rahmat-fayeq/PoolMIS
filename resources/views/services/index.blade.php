<x-layouts.app :title="__('Services')">

    <div class="mb-6 flex justify-between items-center">
        <x-button tag="a" href="{{route('services.create')}}" type="primary"><i class="fas fa-plus-circle"></i> Add New</x-button>
        <x-search url="{{ route('services.index') }}" placeholder="Search ..." />
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
                    Price
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
            @foreach ($services as $index=>$data)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $index + 1 }}
                </th>
                <td class="px-6 py-4">
                    {{$data->name}}
                </td>
                <td class="px-6 py-4">
                    {{$data->price}}
                </td>
                <td class="px-6 py-4">
                   <x-button tag="a" href="{{ route('services.edit', $data->id) }}" 
                        type="warning" 
                        class="px-3 py-1 rounded inline-flex items-center justify-center">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </x-button>

                </td>
                <td class="px-6 py-4">
                    <x-delete-item id="{{ $data->id }}" url="{{ route('services.destroy', $data->id) }}" />
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


</x-layouts.app>
