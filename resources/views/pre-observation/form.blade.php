<x-app-layout>

    <div class="min-h-screen bg-slate-100 py-8 px-6">

        <div class="max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="relative bg-gradient-to-br
                        from-slate-900 via-violet-950 to-purple-900
                        rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0
                            translate-x-10 -translate-y-10
                            w-72 h-72 bg-purple-500/10
                            rounded-full blur-3xl">
                </div>

                <div class="relative z-10">

                    <h1 class="text-3xl font-extrabold text-white">
                        {{ $form->form_name }}
                    </h1>

                    @if($form->instruction)

                    <p class="text-violet-300 mt-2">
                        {{ $form->instruction }}
                    </p>

                    @endif

                </div>

            </div>


            {{-- Validation errors --}}
            @if($errors->any())

            <div class="mb-6 px-5 py-4
                            bg-red-100 border border-red-200
                            text-red-700 rounded-xl">

                <ul class="list-disc list-inside text-sm">

                    @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

            @endif


            <form
                method="POST"
                action="{{ route('observer.pre.store', $gn_id) }}">

                @csrf


                {{-- Observation information --}}
                <div class="bg-white rounded-3xl shadow-lg px-6 py-5 mb-8">

                    <h2 class="text-lg font-bold text-slate-900 mb-4">
                        Observer Information
                    </h2>


                    {{-- Teacher information --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5
                                pb-4 mb-4 border-b border-slate-200">

                        <div>

                            <p class="text-xs font-semibold uppercase
                                      tracking-wider text-slate-400 mb-1">
                                Teacher Name
                            </p>

                            <p class="font-bold text-slate-800 uppercase">
                                {{ $guru->gn_name }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-semibold uppercase
                                      tracking-wider text-slate-400 mb-1">
                                School
                            </p>

                            <p class="font-bold text-slate-800">
                                {{ $guru->school?->school_name ?? '-' }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-semibold uppercase
                                      tracking-wider text-slate-400 mb-1">
                                Observer Name 
                            </p>

                            <p class="font-bold text-slate-800 uppercase">
                                {{ Auth::guard('teacher')->user()->teacher_name }}
                            </p>

                        </div>

                    </div>


                    {{-- Observation input --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        <div>

                            <label
                                for="class_name"
                                class="block text-sm font-semibold
                                       text-slate-700 mb-1.5">

                                Class

                            </label>

                            <input
                                type="text"
                                id="class_name"
                                name="class_name"
                                value="{{ old('class_name') }}"
                                class="w-full rounded-xl
                                       border-slate-300 py-2
                                       focus:border-purple-500
                                       focus:ring-purple-500">

                            @error('class_name')

                            <p class="text-red-500 text-xs mt-1">
                                {{ $message }}
                            </p>

                            @enderror

                        </div>


                        <div>

                            <label
                                for="subject_name"
                                class="block text-sm font-semibold
                                       text-slate-700 mb-1.5">

                                Subject

                            </label>

                            <input
                                type="text"
                                id="subject_name"
                                name="subject_name"
                                value="{{ old('subject_name') }}"
                                class="w-full rounded-xl
                                       border-slate-300 py-2
                                       focus:border-purple-500
                                       focus:ring-purple-500">

                            @error('subject_name')

                            <p class="text-red-500 text-xs mt-1">
                                {{ $message }}
                            </p>

                            @enderror

                        </div>


                        <div>

                            <label
                                for="observation_date"
                                class="block text-sm font-semibold
                                       text-slate-700 mb-1.5">

                                Date

                            </label>

                            <input
                                type="date"
                                id="observation_date"
                                name="observation_date"
                                value="{{ old('observation_date') }}"
                                class="w-full rounded-xl
                                       border-slate-300 py-2
                                       focus:border-purple-500
                                       focus:ring-purple-500">

                            @error('observation_date')

                            <p class="text-red-500 text-xs mt-1">
                                {{ $message }}
                            </p>

                            @enderror

                        </div>

                    </div>

                </div>



                {{-- Evaluation --}}
                <div class="bg-white rounded-3xl shadow-lg
                            overflow-hidden mb-8">

                    <div class="overflow-x-auto">

                        <table class="w-full">

                            <thead>

                                <tr class="bg-blue-900
                                           text-white
                                           text-xs uppercase
                                           tracking-wider">

                                    <th class="px-5 py-4 text-center w-16">
                                        No.
                                    </th>

                                    <th class="px-5 py-4 text-left">
                                        Section/Criteria
                                    </th>

                                    <th class="px-5 py-4 text-center w-72">
                                        Score
                                    </th>

                                    <th class="px-5 py-4 text-left w-80">
                                        Comment
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-slate-200">

                                @foreach($form->sections as $section)

                                @if($section->criteria->isNotEmpty())

                                {{-- Section --}}
                                <tr class="bg-blue-50">

                                    <td class="px-5 py-4
                                                       text-center text-sm
                                                       font-bold text-slate-700">

                                        {{ $loop->iteration }}

                                    </td>

                                    <td
                                        colspan="3"
                                        class="px-5 py-4
                                                       text-sm font-bold
                                                       text-slate-900">

                                        {{ $section->section_name }}

                                    </td>

                                </tr>


                                @foreach($section->criteria as $criteria)

                                @php
                                $letter = chr(
                                96 + $loop->iteration
                                );
                                @endphp


                                {{-- Criteria --}}
                                <tr class="hover:bg-slate-50 transition">

                                    <td class="px-5 py-5"></td>


                                    <td class="px-5 py-5
                                                           text-sm text-slate-700
                                                           align-middle">

                                        <span class="font-semibold">
                                            {{ $letter }}.
                                        </span>

                                        {{ $criteria->criteria_label }}

                                    </td>


                                    {{-- Score --}}
                                    <td class="px-5 py-5 align-middle">

                                        <div class="flex items-center
                                                                justify-center gap-4">

                                            @for($score = 1; $score <= 5; $score++)

                                                <label class="flex
                                                                          items-center
                                                                          gap-1.5
                                                                          cursor-pointer">

                                                <input
                                                    type="radio"
                                                    name="scores[{{ $criteria->criteriaID }}]"
                                                    value="{{ $score }}"
                                                    {{ old(
                                                                        'scores.' .
                                                                        $criteria->criteriaID
                                                                    ) == $score
                                                                        ? 'checked'
                                                                        : '' }}
                                                    class="pre-score
                                                                           text-blue-600
                                                                           border-slate-300
                                                                           focus:ring-blue-500">

                                                <span class="text-sm
                                                                             font-semibold
                                                                             text-slate-600">

                                                    {{ $score }}

                                                </span>

                                                </label>

                                                @endfor

                                        </div>


                                        @error('scores.' . $criteria->criteriaID)

                                        <p class="text-red-500
                                                                  text-xs mt-2
                                                                  text-center">

                                            {{ $message }}

                                        </p>

                                        @enderror

                                    </td>


                                    {{-- One Ulasan per section --}}
                                    @if($loop->first)

                                    <td
                                        rowspan="{{ $section->criteria->count() }}"
                                        class="px-5 py-5
                                                               align-middle
                                                               border-l border-slate-100">

                                        <textarea
                                            name="section_comments[{{ $section->sectionID }}]"
                                            rows="5"
                                            placeholder="Enter comment.."
                                            class="section-comment
                                                                   w-full rounded-xl
                                                                   border-slate-300
                                                                   focus:border-purple-500
                                                                   focus:ring-purple-500">{{ old(
                                                                'section_comments.' .
                                                                $section->sectionID
                                                            ) }}</textarea>


                                        @error('section_comments.' . $section->sectionID)

                                        <p class="text-red-500
                                                                      text-xs mt-1">

                                            {{ $message }}

                                        </p>

                                        @enderror

                                    </td>

                                    @endif

                                </tr>

                                @endforeach

                                @endif

                                @endforeach

                            </tbody>


                            {{-- Total and percentage --}}
                            <tfoot>

                                <tr class="bg-blue-900 text-white">

                                    <td colspan="2"
                                        class="px-5 py-3 text-right
                   text-sm font-bold uppercase">

                                        Total

                                    </td>

                                    <td class="px-5 py-3 text-center text-sm">

                                        <span id="totalScore"
                                            class="font-bold text-white">
                                            0
                                        </span>

                                        <span class="text-blue-200">
                                            /
                                        </span>

                                        <span id="maximumScore"
                                            class="font-semibold text-blue-100">
                                            0
                                        </span>

                                    </td>

                                    <td class="px-5 py-3 text-center text-sm">

                                        <span class="font-bold text-white uppercase">
                                            Percentage :
                                        </span>

                                        <span id="percentage"
                                            class="ml-2 font-bold text-white">
                                            0%
                                        </span>

                                    </td>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>



                {{-- Other comment --}}
                <div class="bg-white rounded-3xl shadow-lg p-6 mb-8">

                    <h2 class="text-lg font-bold text-slate-900 mb-2">
                        Other Comment
                    </h2>

                    <textarea
                        name="other_comment"
                        rows="4"
                        placeholder="Enter comment..."
                        class="w-full rounded-xl
                               border-slate-300
                               focus:border-purple-500
                               focus:ring-purple-500">{{ old('other_comment') }}</textarea>

                </div>



                {{-- Achievement Level --}}
                <div class="bg-white rounded-3xl shadow-lg px-6 py-5 mb-8">

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                        <h2 class="text-lg font-bold text-slate-900">
                            Achievement Level
                        </h2>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">

                            <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 text-center">
                                <p class="font-semibold text-slate-800">
                                    Weak
                                </p>
                                <p class="text-sm text-slate-500 mt-1">
                                    0 - 39%
                                </p>
                            </div>

                            <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 text-center">
                                <p class="font-semibold text-slate-800">
                                    Satisfactory
                                </p>
                                <p class="text-sm text-slate-500 mt-1">
                                    40 - 59%
                                </p>
                            </div>

                            <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 text-center">
                                <p class="font-semibold text-slate-800">
                                    Good
                                </p>
                                <p class="text-sm text-slate-500 mt-1">
                                    60 - 79%
                                </p>
                            </div>

                            <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 text-center">
                                <p class="font-semibold text-slate-800">
                                    Very Good
                                </p>
                                <p class="text-sm text-slate-500 mt-1">
                                    80 - 89%
                                </p>
                            </div>

                            <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 text-center">
                                <p class="font-semibold text-slate-800">
                                    Excellent
                                </p>
                                <p class="text-sm text-slate-500 mt-1">
                                    90 - 100%
                                </p>
                            </div>

                        </div>

                    </div>

                </div>



                {{-- Sticky action bar --}}
                <div class="sticky bottom-4 z-40 mt-8">

                    <div class="bg-white/95
                                backdrop-blur-sm
                                border border-slate-200
                                shadow-xl
                                rounded-2xl
                                px-6 py-4">

                        <div class="flex flex-col md:flex-row
                                    md:items-center
                                    md:justify-between
                                    gap-4">

                            <div>

                                <p class="text-sm font-semibold text-slate-700">
                                    Save as draft to continue later
                                </p>

                            </div>


                            <div class="flex items-center
                                        gap-3 flex-shrink-0">

                                <a
                                    href="{{ route(
                                        'observer.manage',
                                        $gn_id
                                    ) }}"
                                    class="px-5 py-2
                                           bg-slate-100
                                           hover:bg-slate-200
                                           text-slate-600
                                           font-semibold text-sm
                                           rounded-xl
                                           transition">

                                    Back

                                </a>


                                <button
                                    type="submit"
                                    name="submit_action"
                                    value="Draft"
                                    class="px-5 py-2
                                           bg-white
                                           hover:bg-green-50
                                           border border-green-400
                                           text-slate-900
                                           font-semibold text-sm
                                           rounded-xl
                                           transition">

                                    Save

                                </button>


                                <button
                                    type="submit"
                                    id="submitButton"
                                    name="submit_action"
                                    value="Submitted"
                                    disabled
                                    class="px-5 py-2
                                           bg-slate-200
                                           text-slate-400
                                           font-semibold text-sm
                                           rounded-xl
                                           cursor-not-allowed
                                           transition">

                                    Submit

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Live calculation and submit validation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const classInput = document.getElementById('class_name');
            const subjectInput = document.getElementById('subject_name');
            const dateInput = document.getElementById('observation_date');

            const scoreInputs = document.querySelectorAll('.pre-score');
            const sectionComments = document.querySelectorAll('.section-comment');

            const submitButton = document.getElementById('submitButton');

            const totalScoreElement = document.getElementById('totalScore');
            const maximumScoreElement = document.getElementById('maximumScore');
            const percentageElement = document.getElementById('percentage');


            // Reset achievement level colours
            function resetAchievementLevels() {
                document
                    .querySelectorAll('.achievement-level')
                    .forEach(function(level) {

                        level.classList.remove(
                            'bg-red-100',
                            'border-red-400',
                            'bg-orange-100',
                            'border-orange-400',
                            'bg-yellow-100',
                            'border-yellow-400',
                            'bg-blue-100',
                            'border-blue-400',
                            'bg-emerald-100',
                            'border-emerald-400',
                            'border-2'
                        );

                        level.classList.add(
                            'bg-slate-50',
                            'border',
                            'border-slate-100'
                        );
                    });
            }


            // Highlight achievement level based on percentage
            function highlightAchievementLevel(percentage) {
                let selectedLevel = null;
                let backgroundClass = '';
                let borderClass = '';

                if (percentage < 40) {

                    selectedLevel = document.getElementById('level-weak');
                    backgroundClass = 'bg-red-100';
                    borderClass = 'border-red-400';

                } else if (percentage < 60) {

                    selectedLevel = document.getElementById('level-satisfactory');
                    backgroundClass = 'bg-orange-100';
                    borderClass = 'border-orange-400';

                } else if (percentage < 80) {

                    selectedLevel = document.getElementById('level-good');
                    backgroundClass = 'bg-yellow-100';
                    borderClass = 'border-yellow-400';

                } else if (percentage < 90) {

                    selectedLevel = document.getElementById('level-very-good');
                    backgroundClass = 'bg-blue-100';
                    borderClass = 'border-blue-400';

                } else {

                    selectedLevel = document.getElementById('level-excellent');
                    backgroundClass = 'bg-emerald-100';
                    borderClass = 'border-emerald-400';
                }

                if (selectedLevel) {

                    selectedLevel.classList.remove(
                        'bg-slate-50',
                        'border',
                        'border-slate-100'
                    );

                    selectedLevel.classList.add(
                        backgroundClass,
                        borderClass,
                        'border-2'
                    );
                }
            }


            // Calculate score and check whether the form is complete
            function updateForm() {
                let totalScore = 0;

                const selectedScores =
                    document.querySelectorAll('.pre-score:checked');

                selectedScores.forEach(function(input) {
                    totalScore += parseInt(input.value);
                });


                // Count criteria
                const criteriaNames = new Set();

                scoreInputs.forEach(function(input) {
                    criteriaNames.add(input.name);
                });

                const totalCriteria = criteriaNames.size;
                const maximumScore = totalCriteria * 5;


                // Calculate percentage
                let percentage = 0;

                if (maximumScore > 0) {
                    percentage =
                        (totalScore / maximumScore) * 100;
                }


                // Display live calculation
                totalScoreElement.textContent = totalScore;
                maximumScoreElement.textContent = maximumScore;
                percentageElement.textContent =
                    percentage.toFixed(1) + '%';


                // Check whether every criteria has a score
                const scoresComplete =
                    totalCriteria > 0 &&
                    selectedScores.length === totalCriteria;


                // Update achievement level
                resetAchievementLevels();

                if (scoresComplete) {
                    highlightAchievementLevel(percentage);
                }


                // Check observation information
                const informationComplete =
                    classInput.value.trim() !== '' &&
                    subjectInput.value.trim() !== '' &&
                    dateInput.value !== '';


                // Check all section comments
                let commentsComplete = true;

                sectionComments.forEach(function(comment) {

                    if (comment.value.trim() === '') {
                        commentsComplete = false;
                    }
                });


                // Enable Submit only when all required fields are complete
                const formComplete =
                    informationComplete &&
                    scoresComplete &&
                    commentsComplete;


                if (formComplete) {

                    submitButton.disabled = false;

                    submitButton.className =
                        'px-5 py-2 ' +
                        'bg-blue-700 hover:bg-blue-800 ' +
                        'text-white ' +
                        'font-semibold text-sm ' +
                        'rounded-xl shadow-sm ' +
                        'cursor-pointer transition';

                } else {

                    submitButton.disabled = true;

                    submitButton.className =
                        'px-5 py-2 ' +
                        'bg-slate-200 ' +
                        'text-slate-400 ' +
                        'font-semibold text-sm ' +
                        'rounded-xl ' +
                        'cursor-not-allowed transition';
                }
            }


            // Listen for changes in observation information
            classInput.addEventListener('input', updateForm);
            subjectInput.addEventListener('input', updateForm);
            dateInput.addEventListener('change', updateForm);


            // Listen for score changes
            scoreInputs.forEach(function(input) {
                input.addEventListener('change', updateForm);
            });


            // Listen for section comment changes
            sectionComments.forEach(function(comment) {
                comment.addEventListener('input', updateForm);
            });


            // Load initial calculation
            updateForm();

        });
    </script>

</x-app-layout>