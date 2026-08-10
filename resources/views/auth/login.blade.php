<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login TEMS</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>


<body class="min-h-screen bg-gray-100 flex items-center justify-center p-6">

    <div class="w-full max-w-6xl bg-white rounded-[40px] shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

        {{-- LEFT SIDE --}}
        <div class="bg-gradient-to-br from-violet-700 to-purple-800 text-white p-16 flex flex-col justify-center">

            <h1 class="text-5xl font-extrabold mb-10">
                AL AMIN
                <br>
                EDU OASIS
            </h1>

            <h2 class="text-3xl font-semibold leading-relaxed">
                EduOasis Learning &
                <br>
                Insight Tracking System
                <br>
                (ELIT)
            </h2>

        </div>

        {{-- RIGHT SIDE --}}
        <div class="p-14 flex flex-col justify-center">

            <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">
                LOGIN
            </h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-6">
                    <label for="email"
                        class="block text-gray-700 font-semibold mb-3">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        class="w-full rounded-2xl border border-gray-300 px-5 py-3
                               focus:outline-none focus:ring-2 focus:ring-purple-300
                               focus:border-purple-400">
                </div>

                <div class="mb-5">
                    <label for="password"
                        class="block text-gray-700 font-semibold mb-3">
                        Password
                    </label>

                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full rounded-2xl border border-gray-300
                                   px-5 py-3 pr-12
                                   focus:outline-none focus:ring-2
                                   focus:ring-purple-300
                                   focus:border-purple-400">

                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-4 flex items-center
                                   text-gray-400 hover:text-violet-700">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                id="eyeIcon"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

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
                </div>

               
                <div class="flex items-center mb-8">

                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        {{ old('remember') ? 'checked' : '' }}
                        class="rounded border-gray-300 text-purple-600
                               focus:ring-purple-500">

                    <label for="remember"
                        class="ml-2 text-gray-600 cursor-pointer">
                        Remember Me
                    </label>

                </div>


                <button
                    type="submit"
                    class="w-full bg-purple-700 hover:bg-purple-800
                           text-white font-bold py-4 rounded-2xl
                           transition duration-300 shadow-lg">
                    LOGIN
                </button>


                {{--
                    REGISTER LINK WILL BE ADDED LATER
                    WHEN APPLICANT REGISTRATION IS IMPLEMENTED
                --}}

            </form>

        </div>

    </div>


    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            if (password.type === 'password') {
                password.type = 'text';
            } else {

                password.type = 'password';
            }
        }
    </script>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    @endif

    {{-- ERROR MESSAGE --}}
    @if($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            html: `
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                `,
            confirmButtonColor: '#7c3aed'
        });
    </script>
    @endif

</body>
</html>