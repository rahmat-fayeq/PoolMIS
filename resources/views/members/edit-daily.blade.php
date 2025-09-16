<x-layouts.app :title="__('Edit Daily Member')">

    <form action="{{ route('members.daily.update', $member->id) }}" method="POST"
        class="max-w-lg mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        @csrf
        @method('PUT')
        <div class="mb-5">
            <x-forms.input type="text" label="Lock Number" name="lock_number"
                value="{{ $member->dailyPlan->lock_number }}" required />
            <x-forms.input type="number" min="0" label="Quantity" name="quantity"
                value="{{ $member->dailyPlan->quantity }}" required />
            <x-forms.input type="number" min="0" label="Price" name="price"
                value="{{ $member->dailyPlan->price }}" required />
            <x-forms.input type="number" min="0" label="Total Price" name="total_price"
                value="{{ $member->dailyPlan->total_price }}" required />
        </div>

        <div class="flex justify-center items-center">
            <x-button type="primary" class="w-screen"><i class="fa-solid fa-floppy-disk mr-2"></i> Save</x-button>
        </div>
    </form>

</x-layouts.app>
