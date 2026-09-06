<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::guard('hr')->user()->hrname }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900
                        rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10
                            w-72 h-72 bg-purple-500/10 rounded-full blur-3xl">
                </div>

                <div class="relative z-10">

                    <h1 class="text-3xl font-extrabold text-white">
                        Register New Teacher
                    </h1>

                    <p class="text-violet-300 mt-2">
                        Create a new teacher account
                    </p>

                </div>

            </div>

            <div class="bg-white rounded-3xl shadow-lg p-8">

                <form method="POST"
                    action="{{ route('hr.gurunew.store') }}">

                    @csrf


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- LEFT COLUMN --}}
                        <div class="space-y-6">

                            <div>

                                <label for="ic_number"
                                    class="block text-gray-700 font-semibold mb-2">
                                    IC Number
                                </label>

                                <input
                                    type="text"
                                    id="ic_number"
                                    name="ic_number"
                                    value="{{ old('ic_number') }}"
                                    maxlength="12"
                                    required
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-purple-500
                                           focus:ring-purple-500">

                                @error('ic_number')
                                <p class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </p>
                                @enderror

                            </div>

                            <div>

                                <label for="gender"
                                    class="block text-gray-700 font-semibold mb-2">
                                    Gender
                                </label>

                                <input
                                    type="text"
                                    id="gender"
                                    readonly
                                    placeholder="Auto detected from IC"
                                    class="w-full rounded-xl border-gray-300 bg-gray-100">

                            </div>


                            <div>

                                <label for="gn_name"
                                    class="block text-gray-700 font-semibold mb-2">
                                    Full Name
                                </label>

                                <input
                                    type="text"
                                    id="gn_name"
                                    name="gn_name"
                                    value="{{ old('gn_name') }}"
                                    required
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-purple-500
                                           focus:ring-purple-500">

                                @error('gn_name')
                                <p class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </p>
                                @enderror

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
                                    value="{{ old('email') }}"
                                    required
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-purple-500
                                           focus:ring-purple-500">

                                @error('email')
                                <p class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </p>
                                @enderror

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
                                    value="{{ old('phone_number') }}"
                                    maxlength="20"
                                    required
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-purple-500
                                           focus:ring-purple-500">

                                @error('phone_number')
                                <p class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </p>
                                @enderror

                            </div>

                        </div>

                        {{-- RIGHT COLUMN --}}
                        <div class="space-y-6">

                            <div>

                                <label for="schoolID"
                                    class="block text-gray-700 font-semibold mb-2">
                                    School
                                </label>

                                <select
                                    id="schoolID"
                                    name="schoolID"
                                    required
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-purple-500
                                           focus:ring-purple-500">

                                    <option value="">
                                        Select School
                                    </option>

                                    @foreach($schools as $school)

                                    <option
                                        value="{{ $school->schoolID }}"
                                        {{ old('schoolID') == $school->schoolID ? 'selected' : '' }}>

                                        {{ $school->school_name }}

                                    </option>

                                    @endforeach

                                </select>

                                @error('schoolID')
                                <p class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </p>
                                @enderror

                            </div>

                            <div>

                                <label for="appointed_date"
                                    class="block text-gray-700 font-semibold mb-2">
                                    Appointed Date
                                </label>

                                <input
                                    type="date"
                                    id="appointed_date"
                                    name="appointed_date"
                                    value="{{ old('appointed_date') }}"
                                    required
                                    class="w-full rounded-xl border-gray-300
                                           focus:border-purple-500
                                           focus:ring-purple-500">

                                @error('appointed_date')
                                <p class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </p>
                                @enderror

                            </div>


                            <div>

                                <label for="password"
                                    class="block text-gray-700 font-semibold mb-2">
                                    Temporary Password
                                </label>


                                <div class="relative">

                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        required
                                        class="w-full rounded-xl border-gray-300 pr-12
                                               focus:border-purple-500
                                               focus:ring-purple-500">


                                    <button
                                        type="button"
                                        onclick="togglePassword('password', this)"
                                        class="absolute inset-y-0 right-0 px-4
                                               flex items-center
                                               text-gray-400
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
                                                d="M3 3l18 18
                                                   M10.6 10.6a2 2 0 002.8 2.8
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
                                                d="M2.458 12
                                                   C3.732 7.943 7.523 5 12 5
                                                   c4.477 0 8.268 2.943 9.542 7
                                                   -1.274 4.057-5.065 7-9.542 7
                                                   -4.477 0-8.268-2.943-9.542-7z" />

                                        </svg>

                                    </button>

                                </div>



                            </div>


                            <div>

                                <label for="password_confirmation"
                                    class="block text-gray-700 font-semibold mb-2">
                                    Confirm Password
                                </label>


                                <div class="relative">

                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        required
                                        class="w-full rounded-xl border-gray-300 pr-12
                                               focus:border-purple-500
                                               focus:ring-purple-500">


                                    <button
                                        type="button"
                                        onclick="togglePassword('password_confirmation', this)"
                                        class="absolute inset-y-0 right-0 px-4
                                               flex items-center
                                               text-gray-400
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
                                                d="M3 3l18 18
                                                   M10.6 10.6a2 2 0 002.8 2.8
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
                                                d="M2.458 12
                                                   C3.732 7.943 7.523 5 12 5
                                                   c4.477 0 8.268 2.943 9.542 7
                                                   -1.274 4.057-5.065 7-9.542 7
                                                   -4.477 0-8.268-2.943-9.542-7z" />

                                        </svg>

                                    </button>

                                </div>

                                <ul class="mt-3 space-y-1 text-sm">

                                    <li
                                        id="pw-length"
                                        class="flex items-center gap-2 text-gray-400">

                                        <span id="icon-length">
                                            ○
                                        </span>

                                        <span>
                                            At least 8 characters
                                        </span>

                                    </li>


                                    <li
                                        id="pw-upper"
                                        class="flex items-center gap-2 text-gray-400">

                                        <span id="icon-upper">
                                            ○
                                        </span>

                                        <span>
                                            At least 1 uppercase letter
                                        </span>

                                    </li>


                                    <li
                                        id="pw-symbol"
                                        class="flex items-center gap-2 text-gray-400">

                                        <span id="icon-symbol">
                                            ○
                                        </span>

                                        <span>
                                            At least 1 symbol
                                        </span>

                                    </li>

                                    <li id="pw-match"
                                        class="flex items-center gap-2 text-gray-400">

                                        <span id="icon-match">○</span>

                                        <span>
                                            Passwords match
                                        </span>

                                    </li>

                                </ul>

                                @error('password')
                                <p class="text-red-500 text-sm mt-2">
                                    {{ $message }}
                                </p>
                                @enderror

                            </div>

                        </div>

                    </div>


                    <div class="flex justify-center items-center gap-4 mt-10">

                        <a
                            href="{{ route('hr.gurunew.index') }}"
                            class="px-6 py-3 border border-gray-300 rounded-xl
                                   text-gray-700 font-semibold
                                   hover:bg-gray-100 transition">
                            Cancel
                        </a>


                        <button
                            type="submit"
                            class="px-5 py-2 bg-blue-700
                                   hover:bg-blue-800
                                   text-white font-semibold
                                   rounded-xl shadow transition">
                            Register
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <script>
        // auto detect gender
        const icInput = document.getElementById('ic_number');
        const genderInput = document.getElementById('gender');

        icInput.addEventListener('input', function() {
            const ic = this.value.replace(/\D/g, '');

            this.value = ic;

            if (ic.length === 12) {
                const lastDigit = parseInt(ic.charAt(11));
                genderInput.value =
                    lastDigit % 2 === 0 ?
                    'Female' :
                    'Male';
            } else {
                genderInput.value = '';
            }
        });

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

        // live password check
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const pwLength = document.getElementById('pw-length');
        const pwUpper = document.getElementById('pw-upper');
        const pwSymbol = document.getElementById('pw-symbol');
        const pwMatch = document.getElementById('pw-match');
        const iconLength = document.getElementById('icon-length');
        const iconUpper = document.getElementById('icon-upper');
        const iconSymbol = document.getElementById('icon-symbol');
        const iconMatch = document.getElementById('icon-match');

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

        function checkPasswordRequirements() {
            const value = passwordInput.value;
            // at least 8 characters
            updatePasswordStatus(
                value.length >= 8,
                pwLength,
                iconLength
            );

            // at least 1 uppercase letter
            updatePasswordStatus(
                /[A-Z]/.test(value),
                pwUpper,
                iconUpper
            );

            // at least 1 symbol
            updatePasswordStatus(
                /[^A-Za-z0-9]/.test(value),
                pwSymbol,
                iconSymbol
            );
        }

        function checkPasswordMatch() {

            const password = passwordInput.value;
            const confirmation = passwordConfirmationInput.value;

            const isMatch =
                confirmation.length > 0 &&
                password === confirmation;

            updatePasswordStatus(
                isMatch,
                pwMatch,
                iconMatch
            );
        }

        passwordInput.addEventListener(
            'input',
            function() {
                checkPasswordRequirements();
                checkPasswordMatch();
            }
        );

        passwordConfirmationInput.addEventListener(
            'input',
            checkPasswordMatch
        );
    </script>

</x-app-layout>