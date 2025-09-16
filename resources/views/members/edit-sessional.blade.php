<x-layouts.app :title="__('Edit Sessional Member')">

    <form action="{{ route('members.sessional.update', $member->id) }}" method="POST"
        class="max-w-lg mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        @csrf
        @method('PUT')
        <div class="mb-5">
            <x-forms.input type="text" label="Full Name" name="name" value="{{ $member->name }}"  />
            <x-forms.input type="text" label="Phone Number" name="phone" value="{{ $member->phone }}"/>
            <x-forms.input type="number" label="Total Sessions" name="total_sessions"
                value="{{ $member->sessionalPlan->total_sessions }}"  />
            <x-forms.input type="number" step="0.01" label="Price" name="price" value="{{ $member->sessionalPlan->price }}" />
        </div>

        <div class="flex justify-center items-center">
            <x-button type="primary" class="w-screen"><i class="fa-solid fa-floppy-disk mr-2"></i> Save</x-button>
        </div>
    </form>

</x-layouts.app>
