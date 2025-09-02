<x-layouts.app :title="__('Add Salary')">

    <form action="{{route('salary.store')}}" method="POST" class="max-w-sm mx-auto">
        @csrf
        <div class="mb-5">
            <x-forms.input type="text" label="Full Name" name="full_name" required/>
        </div>
        <div class="mb-5">
            <x-forms.input type="text" label="Father Name" name="father_name" required />
        </div>
        <div class="mb-5">
            <x-forms.input type="text" label="Job" name="job" required />
        </div>
        <div class="mb-5">
            <x-forms.input type="number" label="Phone Number" name="phone" required />
        </div>
        <div class="mb-5">
            <x-forms.input type="number" label="Amount" name="amount" required />
        </div>
        <div class="mb-5">
            <x-forms.input type="date" label="Date" name="submit_date" required />
        </div>
        <x-button type="primary">save</x-button>
    </form>


</x-layouts.app>
