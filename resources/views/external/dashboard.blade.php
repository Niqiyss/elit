<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $teacher->teacher_name }}
        </h2>
    </x-slot>


    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            {{-- HEADER --}}
            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <h1 class="text-3xl font-extrabold text-white">
                        External Observer Dashboard
                    </h1>

                    <p class="text-violet-300 mt-2">
                        Manage new teacher external observations and evaluations
                    </p>

                </div>

            </div>


            {{-- WELCOME --}}
            <div class="bg-white rounded-3xl shadow-lg p-8">

                <h2 class="text-2xl font-bold text-gray-800">
                    Welcome, {{ $teacher->teacher_name }}
                </h2>

                <p class="text-gray-500 mt-2">
                    View and manage your assigned new teacher evaluations.
                </p>

            </div>

        </div>

    </div>

</x-app-layout>