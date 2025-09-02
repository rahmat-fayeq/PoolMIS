<x-layouts.app :title="__('Edit Service')">

    <form action="{{route('services.update', $service->id)}}" method="POST" class="max-w-sm mx-auto">
        @csrf
        @method('PUT')
        
        <div class="mb-5">
            <x-forms.input type="text" label="Name" name="name" value="{{$service->name}}" required/>
        </div>
        <div class="mb-5">
            <x-forms.input type="number" label="Price" name="price" value="{{$service->price}}" required />
        </div>
        <x-button type="primary">save</x-button>
    </form>


</x-layouts.app>
