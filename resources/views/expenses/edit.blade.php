<x-layouts.app :title="__('Edit Expense')">

    <form action="{{route('expenses.update',$expense->id)}}" method="POST" class="max-w-sm mx-auto">
        @csrf
        @method('PATCH')
        <div class="mb-5">
            <x-forms.input type="text" label="Title" name="title" value="{{$expense->title}}" required />
        </div>
        <div class="mb-5">
            <x-forms.input type="number" label="Amount" name="amount" value="{{$expense->amount}}" required/>
        </div>
        <div class="mb-5">
            <x-forms.input type="date" label="Date" name="expense_date" value="{{$expense->expense_date}}" required/>
        </div>
        <x-button type="primary">save</x-button>
    </form>


</x-layouts.app>
