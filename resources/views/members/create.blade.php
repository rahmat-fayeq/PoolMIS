<x-layouts.app :title="__('Add Member')">

    <form action="{{ route('members.store') }}" method="POST"
        class="max-w-lg mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        @csrf
        <div class="mb-5">
            <label for="member-type"
                class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
            <select name="type" id="member-type"
                class="w-full px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="daily" @if (old('type') == 'daily') selected @endif>Daily</option>
                <option value="monthly" @if (old('type') == 'monthly') selected @endif>Monthly</option>
                <option value="sessional" @if (old('type') == 'sessional') selected @endif>Sessional</option>
            </select>
        </div>

        <!-- Sessional Fields -->
        <div id="sessional-fields" style="display:none;">
            <div class="mb-5">
                <x-forms.input type="text" label="Full Name" name="name" value="{{ old('name') }}" disabled />
                <x-forms.input type="text" label="Phone Number" name="phone" value="{{ old('phone') }}"
                    disabled />
                <x-forms.input type="number" label="Total Sessions" name="total_sessions"
                    value="{{ old('total_sessions') }}" disabled />
                <x-forms.input type="number" step="0.01" label="Price" name="price" value="{{ old('price') }}"
                    disabled />
            </div>
        </div>

        <!-- Monthly Fields -->
        <div id="monthly-fields" style="display:none;">
            <div class="mb-5">
                <x-forms.input type="text" label="Full Name" name="name" value="{{ old('name') }}" disabled />
                <x-forms.input type="text" label="Phone Number" name="phone" value="{{ old('phone') }}"
                    disabled />
                <x-forms.input type="date" label="Start Date" name="start_date" value="{{ old('start_date') }}"
                    disabled />
                <x-forms.input type="date" label="End Date" name="end_date" value="{{ old('end_date') }}" disabled />
                <x-forms.input type="number" min="0" label="Price" name="price" value="{{ old('price') }}"
                    disabled />
            </div>
        </div>

        <!-- Daily Fields -->
        <div id="daily-fields" style="display:none;">
            <div class="mb-5">
                <x-forms.input type="number" label="Lock Number" name="lock_number" value="{{ old('lock_number') }}"
                    disabled />
                <x-forms.input type="number" min="0" label="Quantity" name="quantity"
                    value="{{ old('quantity') }}" disabled />
                <x-forms.input type="number" min="0" label="Price" name="price" value="{{ old('price') }}"
                    disabled />
            </div>
        </div>

        <div class="flex justify-center items-center">
            <x-button type="primary" class="w-screen"><i class="fa-solid fa-floppy-disk mr-2"></i> Save</x-button>
        </div>
    </form>

    <!-- Put the script inline so it actually runs inside a Blade component -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('member-type');
            const sections = {
                sessional: document.getElementById('sessional-fields'),
                monthly: document.getElementById('monthly-fields'),
                daily: document.getElementById('daily-fields'),
            };

            function hideAndDisableAll() {
                Object.values(sections).forEach(sec => {
                    sec.style.display = 'none';
                    sec.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
                });
            }

            function showFields() {
                hideAndDisableAll();
                const v = typeSelect.value;
                if (sections[v]) {
                    sections[v].style.display = 'block';
                    sections[v].querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);
                }
            }

            typeSelect.addEventListener('change', showFields);
            showFields(); // initial render (respects old('type'))
        });
    </script>

</x-layouts.app>
