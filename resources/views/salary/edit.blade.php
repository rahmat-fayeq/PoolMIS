<x-layouts.app :title="__('Edit Salary')">

    <form action="{{route('salary.update',$salary->id)}}" method="POST" class="max-w-sm mx-auto">
        @csrf
        @method('PATCH')
        <div class="mb-5">
            <x-forms.input type="text" label="Full Name" name="full_name" value="{{$salary->full_name}}" required />
        </div>
        <div class="mb-5">
            <x-forms.input type="text" label="Father Name" name="father_name" value="{{$salary->father_name}}"required />
        </div>
        <div class="mb-5">
            <x-forms.input type="text" label="Job" name="job" value="{{$salary->job}}" required/>
        </div>
        <div class="mb-5">
            <x-forms.input type="number" label="Phone Number" name="phone" value="{{$salary->phone}}" required/>
        </div>
        <div class="mb-5">
            <x-forms.input type="number" label="Amount" name="amount" value="{{$salary->amount}}" required/>
        </div>
        <div class="mb-5">
            <x-forms.input type="date" label="Date" name="submit_date" value="{{$salary->submit_date}}" required/>
        </div>
        <x-button type="primary">save</x-button>
    </form>


</x-layouts.app>
