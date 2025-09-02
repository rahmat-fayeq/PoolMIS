<x-layouts.app :title="__('Add Service')">
    <form action="{{route('services.store')}}" method="POST" class="max-w-sm mx-auto">
        @csrf
        <div class="mb-5">
            <x-forms.input type="text" label="Name" name="name" required/>
        </div>
        <div class="mb-5">
            <x-forms.input type="number" label="Price" name="price" required />
        </div>
        <x-button type="primary">save</x-button>
    </form>


</x-layouts.app>
