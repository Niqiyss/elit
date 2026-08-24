<x-app-layout>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto px-6">

            <div class="relative bg-gradient-to-br
                        from-slate-900 via-violet-950 to-purple-900
                        rounded-3xl p-8 shadow-xl
                        overflow-hidden mb-8">

                <div class="absolute right-0 top-0
                            translate-x-10 -translate-y-10
                            w-72 h-72
                            bg-purple-500/10
                            rounded-full blur-3xl">
                </div>


                <div class="relative z-10
                            flex flex-col md:flex-row
                            md:items-center
                            md:justify-between gap-6">

                    <div>

                        <p class="text-violet-300
                                  text-sm font-semibold mb-2">
                            Welcome back,
                        </p>

                        <h1 class="text-3xl
                                   font-extrabold text-white">

                            Administrator

                        </h1>


                    </div>


                    <div class="flex flex-wrap gap-3">

                        <a
                            href="{{ route('admin.manage.form') }}"
                            class="px-5 py-3
                                   bg-white
                                   text-violet-900
                                   rounded-xl
                                   font-semibold
                                   shadow
                                   hover:bg-violet-50
                                   transition">

                            Manage Forms

                        </a>

                    </div>

                </div>

            </div>


            <div class="grid grid-cols-1
                        sm:grid-cols-2
                        lg:grid-cols-4
                        gap-6 mb-8">


                {{-- TOTAL FORMS --}}
                <div class="bg-white
                            rounded-3xl
                            shadow-lg
                            p-6
                            border border-gray-100">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-semibold
                                      text-gray-400">

                                Total Forms

                            </p>

                            <p class="text-3xl
                                      font-extrabold
                                      text-gray-800
                                      mt-2">

                                {{ $totalForms }}

                            </p>

                        </div>


                        <div class="w-14 h-14
                                    rounded-2xl
                                    bg-violet-100
                                    flex items-center
                                    justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7 text-violet-700"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6
                                       M9 8h6
                                       M5 4h14a2 2 0 012 2v12
                                       a2 2 0 01-2 2H5
                                       a2 2 0 01-2-2V6
                                       a2 2 0 012-2z" />

                            </svg>

                        </div>

                    </div>

                </div>



                {{-- ACTIVE FORMS --}}
                <div class="bg-white
                            rounded-3xl
                            shadow-lg
                            p-6
                            border border-gray-100">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-semibold
                                      text-gray-400">

                                Active Forms

                            </p>

                            <p class="text-3xl
                                      font-extrabold
                                      text-green-600
                                      mt-2">

                                {{ $activeForms }}

                            </p>

                        </div>


                        <div class="w-14 h-14
                                    rounded-2xl
                                    bg-green-100
                                    flex items-center
                                    justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7 text-green-700"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4
                                       m6 2a9 9 0 11-18 0
                                       9 9 0 0118 0z" />

                            </svg>

                        </div>

                    </div>

                </div>



                {{-- DOCUMENTS --}}
                <div class="bg-white
                            rounded-3xl
                            shadow-lg
                            p-6
                            border border-gray-100">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-semibold
                                      text-gray-400">

                                Evaluation Documents

                            </p>

                            <p class="text-3xl
                                      font-extrabold
                                      text-blue-600
                                      mt-2">

                                {{ $totalDocuments }}

                            </p>

                        </div>


                        <div class="w-14 h-14
                                    rounded-2xl
                                    bg-blue-100
                                    flex items-center
                                    justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7 text-blue-700"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 21h10
                                       a2 2 0 002-2V7
                                       l-5-5H7
                                       a2 2 0 00-2 2v15
                                       a2 2 0 002 2z
                                       M14 2v5h5" />

                            </svg>

                        </div>

                    </div>

                </div>



                {{-- OBSERVATION RECORDS --}}
                <div class="bg-white
                            rounded-3xl
                            shadow-lg
                            p-6
                            border border-gray-100">

                    <div class="flex
                                items-center
                                justify-between">

                        <div>

                            <p class="text-sm
                                      font-semibold
                                      text-gray-400">

                                Observation Records

                            </p>

                            <p class="text-3xl
                                      font-extrabold
                                      text-orange-500
                                      mt-2">

                                {{ $totalAudits }}

                            </p>

                        </div>


                        <div class="w-14 h-14
                                    rounded-2xl
                                    bg-orange-100
                                    flex items-center
                                    justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7 text-orange-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12
                                       a2 2 0 002 2h10
                                       a2 2 0 002-2V7
                                       a2 2 0 00-2-2h-2
                                       M9 5a2 2 0 002 2h2
                                       a2 2 0 002-2
                                       M9 5a2 2 0 012-2h2
                                       a2 2 0 012 2
                                       m-6 8l2 2 4-4" />

                            </svg>

                        </div>

                    </div>

                </div>

            </div>



            {{-- ========================================================= --}}
            {{-- FORM MANAGEMENT --}}
            {{-- ========================================================= --}}

            <div class="bg-white
                        rounded-3xl
                        shadow-lg
                        p-8 mb-8">


                <div class="mb-7">

                    <h2 class="text-xl
                               font-bold
                               text-gray-800">

                        Evaluation Form Management

                    </h2>

                    <p class="text-sm
                              text-gray-400
                              mt-1">

                        Manage observation and evaluation forms

                    </p>

                </div>



                <div class="grid grid-cols-1
                            md:grid-cols-3
                            gap-6">


                    {{-- PRE --}}
                    <a
                        href="{{ route('admin.pre.form') }}"
                        class="group
                               border border-gray-200
                               rounded-2xl
                               p-6
                               hover:border-violet-400
                               hover:bg-violet-50/50
                               transition">

                        <div class="flex
                                    items-center
                                    justify-between
                                    mb-5">

                            <div class="w-12 h-12
                                        rounded-xl
                                        bg-violet-100
                                        flex items-center
                                        justify-center">

                                <span class="font-bold
                                             text-violet-700">
                                    PRE
                                </span>

                            </div>


                            <span class="text-gray-300
                                         group-hover:text-violet-600
                                         text-xl">
                                →
                            </span>

                        </div>


                        <h3 class="font-bold
                                   text-gray-800">

                            Pre-Observation Form

                        </h3>

                        <p class="text-sm
                                  text-gray-400 mt-1">

                            {{ $preFormCount }} form

                        </p>

                    </a>



                    {{-- PDPC --}}
                    <a
                        href="{{ route('admin.pdpc.form') }}"
                        class="group
                               border border-gray-200
                               rounded-2xl
                               p-6
                               hover:border-blue-400
                               hover:bg-blue-50/50
                               transition">

                        <div class="flex
                                    items-center
                                    justify-between
                                    mb-5">

                            <div class="w-12 h-12
                                        rounded-xl
                                        bg-blue-100
                                        flex items-center
                                        justify-center">

                                <span class="font-bold
                                             text-blue-700">
                                    PDPC
                                </span>

                            </div>


                            <span class="text-gray-300
                                         group-hover:text-blue-600
                                         text-xl">
                                →
                            </span>

                        </div>


                        <h3 class="font-bold
                                   text-gray-800">

                            PDPC Evaluation Form

                        </h3>

                        <p class="text-sm
                                  text-gray-400 mt-1">

                            {{ $pdpcFormCount }} form(s)

                        </p>

                    </a>



                    {{-- POST --}}
                    <a
                        href="{{ route('admin.post.form') }}"
                        class="group
                               border border-gray-200
                               rounded-2xl
                               p-6
                               hover:border-green-400
                               hover:bg-green-50/50
                               transition">

                        <div class="flex
                                    items-center
                                    justify-between
                                    mb-5">

                            <div class="w-12 h-12
                                        rounded-xl
                                        bg-green-100
                                        flex items-center
                                        justify-center">

                                <span class="font-bold
                                             text-green-700">
                                    POST
                                </span>

                            </div>


                            <span class="text-gray-300
                                         group-hover:text-green-600
                                         text-xl">
                                →
                            </span>

                        </div>


                        <h3 class="font-bold
                                   text-gray-800">

                            Post-Observation Form

                        </h3>

                        <p class="text-sm
                                  text-gray-400 mt-1">

                            {{ $postFormCount }} form

                        </p>

                    </a>

                </div>

            </div>



            {{-- ========================================================= --}}
            {{-- RECENT OBSERVATION ACTIVITY --}}
            {{-- ========================================================= --}}

            <div class="bg-white
                        rounded-3xl
                        shadow-lg
                        overflow-hidden">


                <div class="px-8 py-6
                            border-b border-gray-100">

                    <h2 class="text-xl
                               font-bold
                               text-gray-800">

                        Recent Observation Activity

                    </h2>

                    <p class="text-sm
                              text-gray-400
                              mt-1">

                        Latest submitted observation records

                    </p>

                </div>



                <div class="overflow-x-auto">

                    <table class="w-full text-sm">


                        <thead class="bg-slate-50
                                      text-gray-500
                                      uppercase text-xs">

                            <tr>

                                <th class="px-6 py-4
                                           text-left
                                           font-semibold">

                                    Observer

                                </th>

                                <th class="px-6 py-4
                                           text-left
                                           font-semibold">

                                    Teacher

                                </th>

                                <th class="px-6 py-4
                                           text-left
                                           font-semibold">

                                    Role

                                </th>

                                <th class="px-6 py-4
                                           text-left
                                           font-semibold">

                                    Stage

                                </th>

                                <th class="px-6 py-4
                                           text-left
                                           font-semibold">

                                    Date

                                </th>

                            </tr>

                        </thead>



                        <tbody class="divide-y
                                     divide-gray-100">

                            @forelse($recentAudits as $audit)

                                <tr class="hover:bg-violet-50/50
                                           transition">


                                    {{-- OBSERVER --}}
                                    <td class="px-6 py-5">

                                        <p class="font-semibold
                                                  text-gray-800">

                                            {{ $audit->teacher?->teacher_name ?? '-' }}

                                        </p>

                                    </td>



                                    {{-- NEW TEACHER --}}
                                    <td class="px-6 py-5">

                                        <p class="font-semibold
                                                  text-gray-800">

                                            {{ $audit->guruNew?->gn_name ?? '-' }}

                                        </p>

                                        @if($audit->guruNew)

                                            <p class="text-xs
                                                      text-gray-400
                                                      mt-1">

                                                {{ $audit->guruNew->gn_id }}

                                            </p>

                                        @endif

                                    </td>



                                    {{-- ROLE --}}
                                    <td class="px-6 py-5">

                                        @if($audit->role === 'External Observer')

                                            <span class="inline-flex
                                                         px-3 py-1
                                                         rounded-full
                                                         text-xs
                                                         font-semibold
                                                         bg-blue-100
                                                         text-blue-700">

                                                External Observer

                                            </span>

                                        @else

                                            <span class="inline-flex
                                                         px-3 py-1
                                                         rounded-full
                                                         text-xs
                                                         font-semibold
                                                         bg-violet-100
                                                         text-violet-700">

                                                {{ $audit->role }}

                                            </span>

                                        @endif

                                    </td>



                                    {{-- STAGE --}}
                                    <td class="px-6 py-5">

                                        <span class="font-semibold
                                                     text-gray-600">

                                            {{ $audit->stage }}

                                        </span>

                                    </td>



                                    {{-- DATE --}}
                                    <td class="px-6 py-5
                                               text-gray-600">

                                        @if($audit->audit_date)

                                            {{ \Carbon\Carbon::parse($audit->audit_date)->format('d M Y') }}

                                        @else

                                            -

                                        @endif

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center
                                               py-12
                                               text-gray-400">

                                        No observation activity found

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


        </div>

    </div>

</x-app-layout>