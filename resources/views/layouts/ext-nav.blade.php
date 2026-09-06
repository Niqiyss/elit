<nav class="bg-white border-b border-gray-100 shadow-sm">

    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="flex justify-between h-20 items-center">

            <div class="flex items-center">

                <a href="{{ route('external.dashboard') }}"
                    class="text-2xl font-extrabold text-violet-700 tracking-wide">
                    TEMS-ELIT
                </a>

            </div>


            <div class="hidden sm:flex items-center space-x-10">

                <a href="{{ route('external.dashboard') }}"
                    class="text-lg font-semibold text-gray-700 hover:text-purple-700 transition">
                    Dashboard
                </a>

                <a href="{{ route('external.list.evaluate') }}"
                    class="text-lg font-semibold text-gray-700 hover:text-purple-700 transition">
                List Evaluate
                </a>

                <a href="{{ route('external.download.form') }}"
                    class="text-lg font-semibold text-gray-700 hover:text-purple-700 transition">
                    Download
                </a>

                <a href="{{ route('external.profile') }}"
                    class="text-lg font-semibold text-gray-700 hover:text-purple-700 transition">
                    MyProfile
                </a>

                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <button type="submit"
                        class="flex items-center gap-2 bg-red-500 hover:bg-red-600
                               text-white px-4 py-2 rounded-xl font-semibold
                               shadow transition">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />

                        </svg>
                        Logout
                    </button>

                </form>

            </div>

        </div>

    </div>

</nav>