<x-layouts.app :title="__('Users')">

<div class="mb-6 flex justify-start items-center">
    <x-button tag="a" href="{{route('users.create')}}" type="primary"><i class="fas fa-plus-circle"></i> Add New User</x-button>
</div>

<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    #
                </th>
                <th scope="col" class="px-6 py-3">
                    Full Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Email
                </th>
                <th scope="col" class="px-6 py-3" colspan="2">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index=>$data)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $index + 1 }}
                </th>
                <td class="px-6 py-4">
                    {{$data->name}}
                </td>
                <td class="px-6 py-4">
                    {{$data->email}}
                </td>
                <td class="px-6 py-4">
                    <x-delete-item id="{{ $data->id }}" url="{{ route('users.destroy', $data->id) }}" />
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


</x-layouts.app>
