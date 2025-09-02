<x-layouts.app :title="__('Add Expense')">

    <form action="{{route('expenses.store')}}" method="POST" class="max-w-sm mx-auto">
        @csrf
        <div class="mb-5">
            <x-forms.input type="text" label="Title" name="title" required/>
        </div>
        <div class="mb-5">
            <x-forms.input type="number" label="Amount" name="amount" required />
        </div>
        <div class="mb-5">
            <x-forms.input type="date" label="Date" name="expense_date" required />
        </div>
        <x-button type="primary">save</x-button>
    </form>


</x-layouts.app>
