<x-layouts.app :title="__('Add User')">

    <form action="{{route('users.store')}}" method="POST" class="max-w-sm mx-auto">
        @csrf
        <div class="mb-5">
            <x-forms.input type="text" label="Full Name" name="name" required/>
        </div>
        <div class="mb-5">
            <x-forms.input type="email" label="Email Address" name="email" required />
        </div>
        <div class="mb-5">
            <x-forms.input type="password" label="Password" name="password" required />
        </div>
        <x-button type="primary">Register</x-button>
    </form>


</x-layouts.app>
