<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('new_teacher')->user()->gn_name }}
        </h2>

    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <h1 class="text-3xl font-extrabold text-white">
                        My Profile
                    </h1>

                    <p class="text-violet-300 mt-2">
                        View and update your personal information
                    </p>

                </div>

            </div>


            <div class="bg-white rounded-3xl shadow-lg p-10">

                <h2 class="text-sm font-bold uppercase tracking-wider
                           text-blue-700 mb-6">
                    Personal Information
                </h2>

                <form method="POST" action="{{ route('new_teacher.profile.update') }}">

                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        {{-- LEFT COLUMN --}}
                        <div class="space-y-6">

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Full Name
                                </label>

                                <input
                                    type="text"
                                    value="{{ $guru->gn_name }}"
                                    readonly
                                    class="w-full rounded-xl border-gray-300 bg-gray-100">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    IC Number
                                </label>

                                <input
                                    type="text"
                                    value="{{ $guru->ic_number }}"
                                    readonly
                                    class="w-full rounded-xl border-gray-300 bg-gray-100">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Gender
                                </label>

                                <input
                                    type="text"
                                    value="{{ $guru->gender }}"
                                    readonly
                                    class="w-full rounded-xl border-gray-300 bg-gray-100">
                            </div>

                            <div>
                                <label for="phone_number"
                                    class="block text-gray-700 font-semibold mb-2">
                                    Phone Number
                                </label>

                                <input
                                    type="text"
                                    id="phone_number"
                                    name="phone_number"
                                    value="{{ old('phone_number', $guru->phone_number) }}"
                                    maxlength="20"
                                    required
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-purple-500
                                           focus:ring-purple-500">
                            </div>

                            <div>

                                <label for="marital_status"
                                    class="block text-gray-700 font-semibold mb-2">
                                    Marital Status
                                </label>

                                <select
                                    id="marital_status"
                                    name="marital_status"
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-purple-500
                                           focus:ring-purple-500">

                                    <option value="">
                                        Select Marital Status
                                    </option>

                                    <option value="Single"
                                        {{ old('marital_status', $guru->marital_status) === 'Single' ? 'selected' : '' }}>
                                        Single
                                    </option>

                                    <option value="Married"
                                        {{ old('marital_status', $guru->marital_status) === 'Married' ? 'selected' : '' }}>
                                        Married
                                    </option>

                                    <option value="Divorced"
                                        {{ old('marital_status', $guru->marital_status) === 'Divorced' ? 'selected' : '' }}>
                                        Divorced
                                    </option>

                                </select>

                            </div>

                        </div>

                        {{-- RIGHT COLUMN --}}
                        <div class="space-y-6">

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Current Status
                                </label>

                                <input
                                    type="text"
                                    value="{{ $guru->current_status }}"
                                    readonly
                                    class="w-full rounded-xl border-gray-300 bg-gray-100">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    School
                                </label>

                                <input
                                    type="text"
                                    value="{{ $guru->school?->school_name ?? '-' }}"
                                    readonly
                                    class="w-full rounded-xl border-gray-300 bg-gray-100">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Appointed Date
                                </label>

                                <input
                                    type="date"
                                    value="{{ $guru->appointed_date }}"
                                    readonly
                                    class="w-full rounded-xl border-gray-300 bg-gray-100">
                            </div>

                            <div>
                                <label for="email"
                                    class="block text-gray-700 font-semibold mb-2">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $guru->email) }}"
                                    required
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-purple-500
                                           focus:ring-purple-500">
                            </div>

                            <div>

                                <label for="race"
                                    class="block text-gray-700 font-semibold mb-2">
                                    Race
                                </label>

                                <select
                                    id="race"
                                    name="race"
                                    onchange="toggleOtherRace()"
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-purple-500
                                           focus:ring-purple-500">

                                    <option value="">
                                        Select Race
                                    </option>

                                    <option value="Malay"
                                        {{ old('race', $guru->race) === 'Malay' ? 'selected' : '' }}>
                                        Malay
                                    </option>

                                    <option value="Chinese"
                                        {{ old('race', $guru->race) === 'Chinese' ? 'selected' : '' }}>
                                        Chinese
                                    </option>

                                    <option value="Indian"
                                        {{ old('race', $guru->race) === 'Indian' ? 'selected' : '' }}>
                                        Indian
                                    </option>

                                    <option value="Others"
                                        {{ !in_array(old('race', $guru->race), ['', 'Malay', 'Chinese', 'Indian']) ? 'selected' : '' }}>
                                        Others
                                    </option>
                                </select>

                            </div>

                            <div id="other_race_section"
                                class="hidden">

                                <label for="other_race"
                                    class="block text-gray-700 font-semibold mb-2">
                                    Please Specify Race
                                </label>

                                <input
                                    type="text"
                                    id="other_race"
                                    name="other_race"
                                    value="{{ !in_array(old('race', $guru->race), ['', 'Malay', 'Chinese', 'Indian']) ? old('race', $guru->race) : '' }}"
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-purple-500
                                           focus:ring-purple-500">
                            </div>

                        </div>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

                        <div>

                            <label for="address_line"
                                class="block text-gray-700 font-semibold mb-2">
                                Address
                            </label>

                            <input
                                type="text"
                                id="address_line"
                                name="address_line"
                                value="{{ old('address_line', $addressLine) }}"
                                required
                                class="w-full rounded-xl border-gray-300
                                       focus:border-purple-500
                                       focus:ring-purple-500">
                        </div>


                        <div>

                            <label for="postcode"
                                class="block text-gray-700 font-semibold mb-2">
                                Postcode
                            </label>

                            <input
                                type="text"
                                id="postcode"
                                name="postcode"
                                value="{{ old('postcode', $postcode) }}"
                                maxlength="5"
                                required
                                oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                class="w-full rounded-xl border-gray-300
                                       focus:border-purple-500
                                       focus:ring-purple-500">
                        </div>


                        <div>

                            <label for="city"
                                class="block text-gray-700 font-semibold mb-2">
                                City
                            </label>

                            <input
                                type="text"
                                id="city"
                                name="city"
                                value="{{ old('city', $city) }}"
                                required
                                oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')"
                                class="w-full rounded-xl border-gray-300
                                       focus:border-purple-500
                                       focus:ring-purple-500">
                        </div>

                        <div>
                            <label for="state"
                                class="block text-gray-700 font-semibold mb-2">
                                State
                            </label>

                            <input
                                type="text"
                                id="state"
                                name="state"
                                value="{{ old('state', $state) }}"
                                required
                                oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')"
                                class="w-full rounded-xl border-gray-300
                                       focus:border-purple-500
                                       focus:ring-purple-500">
                        </div>

                    </div>


                    <div class="flex justify-center mt-8">

                        <button
                            type="submit"
                            class="px-6 py-3 bg-purple-700 hover:bg-purple-800
                                   text-white font-semibold rounded-xl shadow transition">
                            Update Profile
                        </button>

                    </div>

                </form>


                <div class="border-t border-gray-200 mt-10 pt-8">

                    <h2 class="text-sm font-bold uppercase tracking-wider
                               text-red-700 mb-6">
                        Change Password
                    </h2>

                    <form method="POST" action="{{ route('new_teacher.profile.password') }}">

                        @csrf
                        @method('PUT')


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                            <div>

                                <label for="new_password"
                                    class="block text-gray-700 font-semibold mb-2">
                                    New Password
                                </label>

                                <div class="relative">

                                    <input
                                        type="password"
                                        id="new_password"
                                        name="password"
                                        required
                                        class="w-full rounded-xl border-gray-300 pr-12
                                               focus:border-purple-500
                                               focus:ring-purple-500">

                                    <button
                                        type="button"
                                        onclick="togglePassword('new_password', this)"
                                        class="absolute inset-y-0 right-0 px-4
                                               flex items-center text-gray-400
                                               hover:text-purple-700">

                                        <svg
                                            class="eye-off h-5 w-5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8
                                                   M9.9 4.2A10.5 10.5 0 0112 4
                                                   c5 0 9 4 10 8
                                                   a11.8 11.8 0 01-2.1 3.5
                                                   M6.6 6.6C4.4 8 2.8 10 2 12
                                                   c1 4 5 8 10 8
                                                   1.4 0 2.7-.3 3.9-.8" />

                                        </svg>

                                        <svg
                                            class="eye-on h-5 w-5 hidden"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0
                                                   3 3 0 016 0z" />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                   c4.477 0 8.268 2.943 9.542 7
                                                   -1.274 4.057-5.065 7-9.542 7
                                                   -4.477 0-8.268-2.943-9.542-7z" />

                                        </svg>

                                    </button>

                                </div>


                                <ul class="mt-3 space-y-1 text-sm">

                                    <li
                                        id="new-pw-length"
                                        class="flex items-center gap-2 text-gray-400">

                                        <span id="new-icon-length">○</span>

                                        <span>
                                            At least 8 characters
                                        </span>

                                    </li>

                                    <li
                                        id="new-pw-upper"
                                        class="flex items-center gap-2 text-gray-400">

                                        <span id="new-icon-upper">○</span>

                                        <span>
                                            At least 1 uppercase letter
                                        </span>

                                    </li>

                                    <li
                                        id="new-pw-symbol"
                                        class="flex items-center gap-2 text-gray-400">

                                        <span id="new-icon-symbol">○</span>

                                        <span>
                                            At least 1 symbol
                                        </span>

                                    </li>

                                </ul>

                            </div>

                            <div>

                                <label for="new_password_confirmation"
                                    class="block text-gray-700 font-semibold mb-2">
                                    Confirm New Password
                                </label>

                                <div class="relative">

                                    <input
                                        type="password"
                                        id="new_password_confirmation"
                                        name="password_confirmation"
                                        required
                                        class="w-full rounded-xl border-gray-300 pr-12
                                               focus:border-purple-500
                                               focus:ring-purple-500">

                                    <button
                                        type="button"
                                        onclick="togglePassword('new_password_confirmation', this)"
                                        class="absolute inset-y-0 right-0 px-4
                                               flex items-center text-gray-400
                                               hover:text-purple-700">

                                        <svg
                                            class="eye-off h-5 w-5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8
                                                   M9.9 4.2A10.5 10.5 0 0112 4
                                                   c5 0 9 4 10 8
                                                   a11.8 11.8 0 01-2.1 3.5
                                                   M6.6 6.6C4.4 8 2.8 10 2 12
                                                   c1 4 5 8 10 8
                                                   1.4 0 2.7-.3 3.9-.8" />

                                        </svg>

                                        <svg
                                            class="eye-on h-5 w-5 hidden"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0
                                                   3 3 0 016 0z" />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                   c4.477 0 8.268 2.943 9.542 7
                                                   -1.274 4.057-5.065 7-9.542 7
                                                   -4.477 0-8.268-2.943-9.542-7z" />

                                        </svg>

                                    </button>

                                </div>


                                <ul class="mt-3 space-y-1 text-sm">

                                    <li
                                        id="new-pw-match"
                                        class="flex items-center gap-2 text-gray-400">

                                        <span id="new-icon-match">○</span>

                                        <span>
                                            Passwords match
                                        </span>

                                    </li>

                                </ul>

                            </div>

                        </div>


                        @error('password')
                        <p class="text-red-500 text-sm mt-4">
                            {{ $message }}
                        </p>
                        @enderror

                        <div class="flex justify-center items-center gap-4 mt-10">

                            <button
                                type="submit"
                                class="px-5 py-2 bg-blue-700 hover:bg-blue-800
                                       text-white font-semibold rounded-xl shadow transition">
                                Update 
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <script>
        // race
        function toggleOtherRace() {

            const race = document.getElementById('race');
            const otherSection = document.getElementById('other_race_section');
            const otherInput = document.getElementById('other_race');

            if (race.value === 'Others') {
                otherSection.classList.remove('hidden');
                otherInput.required = true;
            } else {
                otherSection.classList.add('hidden');
                otherInput.required = false;
                otherInput.value = '';
            }
        }

        document.addEventListener(
            'DOMContentLoaded',
            function() {
                toggleOtherRace();
            }
        );

        // show/hide password
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const eyeOff = button.querySelector('.eye-off');
            const eyeOn = button.querySelector('.eye-on');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOff.classList.add('hidden');
                eyeOn.classList.remove('hidden');

            } else {
                input.type = 'password';
                eyeOn.classList.add('hidden');
                eyeOff.classList.remove('hidden');
            }
        }

        // password live check
        const newPasswordInput = document.getElementById('new_password');

        const newPasswordConfirmationInput =
            document.getElementById(
                'new_password_confirmation'
            );

        const newPwLength = document.getElementById('new-pw-length');
        const newPwUpper = document.getElementById('new-pw-upper');
        const newPwSymbol = document.getElementById('new-pw-symbol');
        const newPwMatch = document.getElementById('new-pw-match');
        const newIconLength = document.getElementById('new-icon-length');
        const newIconUpper = document.getElementById('new-icon-upper');
        const newIconSymbol = document.getElementById('new-icon-symbol');
        const newIconMatch = document.getElementById('new-icon-match');

        function updatePasswordStatus(
            condition,
            element,
            icon
        ) {

            if (condition) {
                element.classList.remove(
                    'text-gray-400'
                );
                element.classList.add(
                    'text-green-600',
                    'font-medium'
                );
                icon.textContent = '✓';
            } else {
                element.classList.remove(
                    'text-green-600',
                    'font-medium'
                );
                element.classList.add(
                    'text-gray-400'
                );
                icon.textContent = '○';
            }
        }

        function checkNewPasswordRequirements() {

            const value = newPasswordInput.value;

            updatePasswordStatus(
                value.length >= 8,
                newPwLength,
                newIconLength
            );

            updatePasswordStatus(
                /[A-Z]/.test(value),
                newPwUpper,
                newIconUpper
            );

            updatePasswordStatus(
                /[^A-Za-z0-9]/.test(value),
                newPwSymbol,
                newIconSymbol
            );

        }

        function checkNewPasswordMatch() {

            const password = newPasswordInput.value;
            const confirmation = newPasswordConfirmationInput.value;

            const isMatch =
                confirmation.length > 0 &&
                password === confirmation;

            updatePasswordStatus(
                isMatch,
                newPwMatch,
                newIconMatch
            );

        }

        newPasswordInput.addEventListener(
            'input',
            function() {
                checkNewPasswordRequirements();
                checkNewPasswordMatch();
            }
        );
        newPasswordConfirmationInput.addEventListener(
            'input',
            checkNewPasswordMatch
        );
    </script>

</x-app-layout>