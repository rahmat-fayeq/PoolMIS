<x-layouts.app :title="__('Edit Monthly Member')">

    <form action="{{ route('members.update', $member->id) }}" method="POST"
        class="max-w-lg mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        @csrf
        @method('PUT')
        <div class="mb-5">
            <x-forms.input type="text" label="Full Name" name="name" value="{{ $member->name }}" required />
            <x-forms.input type="text" label="Phone Number" name="phone" value="{{ $member->phone }}" required />
            <x-forms.input type="text" label="Start Date" name="start_date"
                value="{{ $member->monthlyPlan->start_date->format('Y-m-d') }}" required />
            <x-forms.input type="text" label="End Date" name="end_date"
                value="{{ $member->monthlyPlan->end_date->format('Y-m-d') }}" required />
            <x-forms.input type="number" min="0" label="Price" name="price"
                value="{{ $member->monthlyPlan->price }}" required />
        </div>

        <div class="flex justify-center items-center">
            <x-button type="primary" class="w-screen"><i class="fa-solid fa-floppy-disk mr-2"></i> Save</x-button>
        </div>
    </form>

</x-layouts.app>
