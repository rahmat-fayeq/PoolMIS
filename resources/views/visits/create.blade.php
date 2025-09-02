<x-layouts.app :title="__('Add Visit')">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Log a New Visit</h1>

    @if ($errors->any())
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('visits.store') }}" class="max-w-lg mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        @csrf

        <div class="mb-5">
            <label for="member_id" class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Member</label>
            <select name="member_id" id="member_id" class="w-full px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                <option value="">-- Select Member --</option>
                @foreach($members as $m)
                    @if(in_array($m->type, ['sessional','monthly']))
                        <option value="{{ $m->id }}" @selected(old('member_id') == $m->id)>
                            {{ $m->name }} ({{ ucfirst($m->type) }})
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="mb-5">
            <x-forms.input type="datetime-local" label="Visit Date & Time" name="visit_time" required value="{{ old('visit_time') }}" />
        </div>

        <div class="mb-5">
            <x-forms.input type="number" label="Lock Number" name="lock_number" required value="{{ old('lock_number') }}" />
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('visits.index') }}">
                <x-button type="danger">Cancel</x-button>
            </a>
            <x-button type="primary">Save Visit</x-button>
        </div>
    </form>

</x-layouts.app>
