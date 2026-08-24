<x-app-layout>

    <div class="min-h-screen bg-slate-100 py-8 px-6">

        <div class="max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <p class="text-xs uppercase tracking-[0.2em] font-bold text-violet-300">
                        {{ $stage }} Observation
                    </p>

                    <h1 class="text-3xl font-extrabold text-white mt-2">
                        {{ $form->form_name }}
                    </h1>

                    @if($form->instruction)

                    <p class="text-violet-200 mt-2">
                        {{ $form->instruction }}
                    </p>

                    @endif

                </div>

            </div>


            {{-- Errors --}}
            @if($errors->any())

            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4">

                <ul class="list-disc list-inside text-sm">

                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

            @endif


            <form
                method="POST"
                x-data="{ formComplete: false }"
                x-init="$nextTick(() => {
                const checkForm = () => {
                const className = $el.querySelector('[name=class_name]').value.trim();
                const subjectName = $el.querySelector('[name=subject_name]').value.trim();
                const date = $el.querySelector('[name=observation_date]').value;
                const time = $el.querySelector('[name=observation_time]').value;
                const scores = [...$el.querySelectorAll('[data-tums]')];

                formComplete =
                className !== '' &&
                subjectName !== '' &&
                date !== '' &&
                time !== '' &&
                scores.length > 0 &&
                scores.every(input => input.value !== '');
                    };

                    checkForm();
                    $el.addEventListener('input', checkForm);
                    $el.addEventListener('change', checkForm);
                })"
                action="{{ $role === 'observer'
                ? route('observer.pdpc.store', $gn_id)
                : route('external.pdpc.store', $gn_id)
                }}">

                @csrf

                {{-- Observation Information --}}
                <div class="bg-white rounded-3xl shadow-lg px-6 py-5 mb-8">

                    <h2 class="text-lg font-bold text-slate-900 mb-4">
                        Observation Information
                    </h2>


                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pb-5 mb-5 border-b border-slate-200">

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Teacher
                            </p>

                            <p class="font-bold text-slate-800 uppercase mt-1">
                                {{ $guru->gn_name }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                School
                            </p>

                            <p class="font-bold text-slate-800 mt-1">
                                {{ $guru->school?->school_name ?? '-' }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                {{ $role === 'observer' ? 'Observer' : 'External Observer' }}
                            </p>

                            <p class="font-bold text-slate-800 uppercase mt-1">
                                {{ Auth::guard('teacher')->user()->teacher_name }}
                            </p>

                        </div>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Class
                            </label>

                            <input
                                type="text"
                                name="class_name"
                                value="{{ old('class_name') }}"
                                placeholder="Enter class"
                                class="w-full rounded-xl border-slate-300 focus:border-purple-500 focus:ring-purple-500">

                        </div>


                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Subject
                            </label>

                            <input
                                type="text"
                                name="subject_name"
                                value="{{ old('subject_name') }}"
                                placeholder="Enter subject"
                                class="w-full rounded-xl border-slate-300 focus:border-purple-500 focus:ring-purple-500">

                        </div>


                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Date
                            </label>

                            <input
                                type="date"
                                name="observation_date"
                                value="{{ old('observation_date') }}"
                                class="w-full rounded-xl border-slate-300 focus:border-purple-500 focus:ring-purple-500">

                        </div>


                        <div>

                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Time
                            </label>

                            <input
                                type="time"
                                name="observation_time"
                                value="{{ old('observation_time') }}"
                                class="w-full rounded-xl border-slate-300 focus:border-purple-500 focus:ring-purple-500">

                        </div>

                    </div>

                </div>


                {{-- Aspects --}}
                @foreach($form->aspects as $aspect)

                <div class="mb-8">

                    {{-- Aspect Header --}}
                    <div class="bg-blue-900 rounded-t-2xl px-6 py-4 text-white">

                        <p class="text-xs uppercase tracking-wider text-blue-200 font-semibold">
                            Aspect {{ $aspect->aspect_code }}
                        </p>

                        <h2 class="text-lg font-bold mt-1">
                            {{ $aspect->aspect_name }}
                        </h2>

                    </div>


                    {{-- TUMS --}}
                    @foreach($aspect->tums as $tums)

                    @php
                    $allPoints = $tums->tt->flatMap(fn($tt) => $tt->points);
                    $totalPoints = $allPoints->count();
                    @endphp


                    <div
                        id="tums-container-{{ $tums->tumsID }}"
                        data-weight="{{ $tums->wajaran }}"
                        class="bg-white border-x border-b border-slate-200 last:rounded-b-2xl overflow-hidden">


                        {{-- TUMS Header --}}
                        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">

                            <div class="flex items-center justify-between gap-4">

                                <div>

                                    <p class="text-xs font-bold uppercase tracking-wider text-blue-700">
                                        TUMS {{ $tums->tums_code }}
                                    </p>

                                    <p class="font-bold text-black mt-1">
                                        {{ $tums->tums_name }}
                                    </p>

                                </div>


                                <span class="inline-flex items-center justify-center px-4 py-2 rounded-full border border-blue-200 bg-blue-50 text-blue-700 text-sm font-semibold whitespace-nowrap">

                                    Wajaran:
                                    {{ number_format($tums->wajaran, 2) }}

                                </span>

                            </div>

                        </div>


                        {{-- Evaluation Table --}}
                        <div class="overflow-x-auto">

                            <table class="w-full table-fixed text-sm">

                                <thead>

                                    <tr class="bg-slate-100 text-slate-600 uppercase text-xs">

                                        <th class="w-14 px-4 py-3 text-center">
                                            No
                                        </th>

                                        <th class="w-[42%] px-5 py-3 text-left">
                                            Tahap Tindakan (TT)
                                        </th>

                                        <th class="w-28 px-5 py-3 text-center">
                                            Skor
                                        </th>

                                        <th class="px-5 py-3 text-left">
                                            Rubrik Tahap Kualiti (RTK)
                                        </th>

                                    </tr>

                                </thead>


                                <tbody class="divide-y divide-slate-200">

                                    @foreach($allPoints as $point)

                                    <tr class="align-top">

                                        <td class="px-4 py-4 text-center text-black">
                                            {{ $loop->iteration }}.
                                        </td>


                                        <td class="px-5 py-4 border-l border-slate-200 text-black leading-6">

                                            {{ $point->point_text }}

                                        </td>


                                        <td class="px-5 py-4 border-l border-slate-200 text-center">

                                            <input
                                                type="text"
                                                name="scores[{{ $point->pointID }}]"
                                                value="{{ old('scores.' . $point->pointID) }}"
                                                maxlength="1"
                                                inputmode="numeric"
                                                placeholder="-"
                                                data-tums="{{ $tums->tumsID }}"
                                                oninput="this.value=this.value.replace(/[^0-4]/g,'').slice(0,1); calculateTums('{{ $tums->tumsID }}');"
                                                class="w-20 rounded-xl border-slate-300 text-center text-black focus:border-purple-500 focus:ring-purple-500">

                                        </td>


                                        @if($loop->first)

                                        <td
                                            rowspan="{{ $totalPoints }}"
                                            class="px-5 py-4 border-l border-slate-200 align-top">

                                            <div class="space-y-3">

                                                @foreach([4, 3, 2, 1, 0] as $score)

                                                @php($rubric = $tums->rubrics->firstWhere('score', $score))

                                                <div class="flex items-start gap-3">

                                                    <span class="inline-flex items-center justify-center min-w-[60px] px-2 py-2 rounded-lg bg-blue-500 text-white text-xs font-bold whitespace-nowrap">

                                                        RTK {{ $score }}

                                                    </span>


                                                    <div class="flex-1 px-3 py-2 rounded-xl border border-violet-200 bg-white text-xs text-black leading-5">

                                                        {{ $rubric?->description ?? '-' }}

                                                    </div>

                                                </div>

                                                @endforeach

                                            </div>

                                        </td>

                                        @endif

                                    </tr>

                                    @endforeach

                                </tbody>


                                {{-- TUMS Calculation --}}
                                <tfoot class="bg-blue-50/70 text-sm">

                                    <tr class="border-t border-blue-200">

                                        <td></td>

                                        <td class="px-5 py-2.5 text-left text-slate-900 border-l border-blue-200">

                                            Bilangan Tindakan / Jumlah Skor Kualiti

                                        </td>

                                        <td
                                            id="action-count-{{ $tums->tumsID }}"
                                            class="px-3 py-2.5 text-center text-slate-900 border-l border-blue-200">

                                            0

                                        </td>

                                        <td class="p-0 border-l border-blue-200">

                                            <div class="grid grid-cols-2 h-full">

                                                <div
                                                    id="quality-total-{{ $tums->tumsID }}"
                                                    class="px-3 py-2.5 text-center text-slate-900 border-r border-blue-200">

                                                    0

                                                </div>

                                                <div></div>

                                            </div>

                                        </td>

                                    </tr>


                                    <tr class="border-t border-blue-200">

                                        <td></td>

                                        <td class="px-5 py-2.5 text-left text-slate-900 border-l border-blue-200">

                                            Skor Tahap Tindakan / Min Skor Tahap Kualiti

                                        </td>

                                        <td
                                            id="action-score-{{ $tums->tumsID }}"
                                            class="px-3 py-2.5 text-center text-slate-900 border-l border-blue-200">

                                            0

                                        </td>

                                        <td class="p-0 border-l border-blue-200">

                                            <div class="grid grid-cols-2 h-full">

                                                <div
                                                    id="quality-mean-{{ $tums->tumsID }}"
                                                    class="px-3 py-2.5 text-center text-slate-900 border-r border-blue-200">

                                                    0.00

                                                </div>

                                                <div></div>

                                            </div>

                                        </td>

                                    </tr>


                                    <tr class="border-t border-blue-200">

                                        <td></td>

                                        <td class="px-5 py-2.5 text-left text-slate-900 border-l border-blue-200">

                                            Peratus Skor Tahap Tindakan / Peratus Skor Tahap Kualiti

                                        </td>

                                        <td
                                            id="action-percentage-{{ $tums->tumsID }}"
                                            class="px-3 py-2.5 text-center text-slate-900 border-l border-blue-200">

                                            0.00

                                        </td>

                                        <td class="p-0 border-l border-blue-200">

                                            <div class="grid grid-cols-2 h-full">

                                                <div
                                                    id="quality-percentage-{{ $tums->tumsID }}"
                                                    class="px-3 py-2.5 text-center text-slate-900 border-r border-blue-200">

                                                    0.00

                                                </div>

                                                <div></div>

                                            </div>

                                        </td>

                                    </tr>


                                    <tr class="border-t border-blue-200">

                                        <td></td>

                                        <td
                                            colspan="2"
                                            class="px-5 py-2.5 text-left font-semibold text-slate-900 border-l border-blue-200">

                                            Peratus TUMS

                                        </td>

                                        <td class="p-0 border-l border-blue-200">

                                            <div class="grid grid-cols-2 h-full">

                                                <div
                                                    id="tums-percentage-{{ $tums->tumsID }}"
                                                    class="px-3 py-2.5 text-center bg-blue-600 text-white font-bold">

                                                    0.00

                                                </div>

                                                <div></div>

                                            </div>

                                        </td>

                                    </tr>

                                </tfoot>

                            </table>

                        </div>

                    </div>

                    @endforeach

                </div>

                @endforeach


                {{-- Overall Summary --}}
                <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">

                    {{-- Header --}}
                    <div class="px-6 py-5 border-b border-slate-200">

                        <h2 class="text-lg font-bold text-slate-900">
                            Observation Summary
                        </h2>

                        <p class="text-sm text-slate-400 mt-1">
                            Overall result based on TUMS percentage and weight
                        </p>

                    </div>


                    <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,2.1fr)_minmax(330px,1fr)] gap-6 p-5">

                        {{-- Aspect Summary --}}
                        <div class="overflow-hidden rounded-2xl border border-slate-200 self-start">

                            <table class="w-full table-fixed text-sm">

                                <thead>

                                    <tr class="bg-blue-900 text-white uppercase text-xs">

                                        <th class="w-[10%] px-3 py-3 text-center border-r border-blue-700">
                                            Aspect
                                        </th>

                                        <th class="w-[32%] px-3 py-3 text-left border-r border-blue-700">
                                            Aspect Name
                                        </th>

                                        <th class="w-[13%] px-3 py-3 text-center border-r border-blue-700">
                                            TUMS
                                        </th>

                                        <th class="w-[14%] px-3 py-3 text-center border-r border-blue-700">
                                            Wajaran
                                        </th>

                                        <th class="w-[14%] px-3 py-3 text-center border-r border-blue-700">
                                            %
                                        </th>

                                        <th class="w-[17%] px-3 py-3 text-center">
                                            Skor
                                        </th>

                                    </tr>

                                </thead>


                                <tbody class="divide-y divide-slate-200">

                                    @foreach($form->aspects as $aspect)

                                    @foreach($aspect->tums as $tums)

                                    <tr class="hover:bg-slate-50">

                                        <td class="px-3 py-2.5 text-center text-slate-700 border-r border-slate-200">
                                            {{ $aspect->aspect_code }}
                                        </td>

                                        <td class="px-3 py-2.5 text-slate-700 border-r border-slate-200">
                                            {{ $aspect->aspect_name }}
                                        </td>

                                        <td class="px-3 py-2.5 text-center text-slate-700 border-r border-slate-200">
                                            {{ $tums->tums_code }}
                                        </td>

                                        <td class="px-3 py-2.5 text-center text-slate-700 border-r border-slate-200">
                                            {{ number_format($tums->wajaran, 2) }}
                                        </td>

                                        <td
                                            id="summary-percentage-{{ $tums->tumsID }}"
                                            class="px-3 py-2.5 text-center text-slate-700 border-r border-slate-200">

                                            0.00

                                        </td>

                                        <td
                                            id="summary-score-{{ $tums->tumsID }}"
                                            class="px-3 py-2.5 text-center text-slate-900">

                                            0.00

                                        </td>

                                    </tr>

                                    @endforeach

                                    @endforeach

                                </tbody>


                                <tfoot>

                                    <tr class="bg-blue-50 border-t-2 border-blue-200">

                                        <td
                                            colspan="5"
                                            class="px-3 py-3 text-right font-bold text-slate-900 border-r border-blue-200">

                                            JUMLAH

                                        </td>

                                        <td
                                            id="overall-weighted-score"
                                            class="px-3 py-3 text-center bg-blue-600 text-white font-bold">

                                            0.00

                                        </td>

                                    </tr>

                                </tfoot>

                            </table>

                        </div>


                        {{-- Achievement Level --}}
                        <div class="overflow-hidden rounded-2xl border border-slate-200 self-start">

                            <table class="w-full table-fixed text-sm">

                                <thead>

                                    <tr class="bg-blue-900 text-white uppercase text-xs">

                                        <th class="w-[45%] px-3 py-3 text-left border-r border-blue-700">
                                            Taraf
                                        </th>

                                        <th class="w-[38%] px-3 py-3 text-center border-r border-blue-700">
                                            Skor
                                        </th>

                                        <th class="w-[17%] px-3 py-3 text-center">
                                            ✓
                                        </th>

                                    </tr>

                                </thead>


                                <tbody class="divide-y divide-slate-200">

                                    {{-- Cemerlang --}}
                                    <tr>

                                        <td class="px-3 py-3 text-slate-700 border-r border-slate-200">
                                            Cemerlang
                                        </td>

                                        <td class="px-3 py-3 text-center text-slate-700 border-r border-slate-200">
                                            90 - 100
                                        </td>

                                        <td
                                            id="achievement-cemerlang"
                                            class="px-3 py-3 text-center text-green-600 text-lg font-bold">
                                        </td>

                                    </tr>


                                    {{-- Baik --}}
                                    <tr>

                                        <td class="px-3 py-3 text-slate-700 border-r border-slate-200">
                                            Baik
                                        </td>

                                        <td class="px-3 py-3 text-center text-slate-700 border-r border-slate-200">
                                            80 - 89.99
                                        </td>

                                        <td
                                            id="achievement-baik"
                                            class="px-3 py-3 text-center text-blue-600 text-lg font-bold">
                                        </td>

                                    </tr>


                                    {{-- Sederhana --}}
                                    <tr>

                                        <td class="px-3 py-3 text-slate-700 border-r border-slate-200">
                                            Sederhana
                                        </td>

                                        <td class="px-3 py-3 text-center text-slate-700 border-r border-slate-200">
                                            50 - 79.99
                                        </td>

                                        <td
                                            id="achievement-sederhana"
                                            class="px-3 py-3 text-center text-amber-500 text-lg font-bold">
                                        </td>

                                    </tr>


                                    {{-- Lemah --}}
                                    <tr>

                                        <td class="px-3 py-3 text-slate-700 border-r border-slate-200">
                                            Lemah
                                        </td>

                                        <td class="px-3 py-3 text-center text-slate-700 border-r border-slate-200">
                                            20 - 49.99
                                        </td>

                                        <td
                                            id="achievement-lemah"
                                            class="px-3 py-3 text-center text-orange-500 text-lg font-bold">
                                        </td>

                                    </tr>


                                    {{-- Sangat Lemah --}}
                                    <tr>

                                        <td class="px-3 py-3 text-slate-700 border-r border-slate-200">
                                            Sangat Lemah
                                        </td>

                                        <td class="px-3 py-3 text-center text-slate-700 border-r border-slate-200">
                                            0 - 19.99
                                        </td>

                                        <td
                                            id="achievement-sangat-lemah"
                                            class="px-3 py-3 text-center text-red-600 text-lg font-bold">
                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>


                {{-- Sticky Actions --}}
                <div class="sticky bottom-4 z-40 mt-8">

                    <div class="bg-white/95 backdrop-blur-sm border border-slate-200 shadow-xl rounded-2xl px-6 py-4">

                        <div class="flex items-center justify-between gap-4">

                            <a
                                href="{{ route(
                                    $role === 'observer'
                                        ? 'observer.manage'
                                        : 'external.manage',
                                    $gn_id
                                ) }}"
                                class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl">

                                Back

                            </a>


                            <div class="flex items-center gap-3">

                                <button
                                    type="submit"
                                    name="submit_action"
                                    value="Draft"
                                    class="px-5 py-2.5 bg-white border border-green-400 hover:bg-green-50 text-slate-800 text-sm font-semibold rounded-xl">

                                    Save

                                </button>


                                <button
                                    type="submit"
                                    name="submit_action"
                                    value="Submitted"
                                    :disabled="!formComplete"
                                    :class="formComplete
                                    ? 'bg-blue-700 hover:bg-blue-800 text-white cursor-pointer'
                                    : 'bg-slate-300 text-slate-500 cursor-not-allowed'"
                                    class="px-5 py-2.5
                                    text-sm
                                    font-semibold
                                    rounded-xl
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


    {{-- Calculation --}}
    <script>
        function calculateTums(tumsID) {
            const inputs = document.querySelectorAll(`[data-tums="${tumsID}"]`);
            const totalPoints = inputs.length;

            let actionCount = 0;
            let qualityTotal = 0;

            inputs.forEach(input => {
                if (input.value === '') return;

                const score = Number(input.value);

                if (Number.isNaN(score) || score < 0 || score > 4) return;

                qualityTotal += score;

                if (score > 0) actionCount++;
            });

            const actionScore = totalPoints > 0 ? Math.round((actionCount / totalPoints) * 4) : 0;
            const qualityMean = totalPoints > 0 ? qualityTotal / totalPoints : 0;
            const roundedQualityMean = Math.round(qualityMean * 100) / 100;
            const actionPercentage = (actionScore / 4) * 100;
            const qualityPercentage = (roundedQualityMean / 4) * 100;
            const tumsPercentage = (actionPercentage * 0.25) + (qualityPercentage * 0.75);

            document.getElementById(`action-count-${tumsID}`).textContent = actionCount;
            document.getElementById(`quality-total-${tumsID}`).textContent = qualityTotal;
            document.getElementById(`action-score-${tumsID}`).textContent = actionScore;
            document.getElementById(`quality-mean-${tumsID}`).textContent = roundedQualityMean.toFixed(2);
            document.getElementById(`action-percentage-${tumsID}`).textContent = actionPercentage.toFixed(2);
            document.getElementById(`quality-percentage-${tumsID}`).textContent = qualityPercentage.toFixed(2);
            document.getElementById(`tums-percentage-${tumsID}`).textContent = tumsPercentage.toFixed(2);

            const container = document.getElementById(`tums-container-${tumsID}`);
            const weight = container ? Number(container.dataset.weight) : 0;
            const weightedScore = (tumsPercentage * weight) / 100;

            document.getElementById(`summary-percentage-${tumsID}`).textContent = tumsPercentage.toFixed(2);
            document.getElementById(`summary-score-${tumsID}`).textContent = weightedScore.toFixed(2);

            calculateOverallSummary();
        }


        function calculateOverallSummary() {
            let total = 0;

            document.querySelectorAll('[id^="summary-score-"]').forEach(element => {
                total += Number(element.textContent) || 0;
            });

            total = Math.round(total * 100) / 100;

            document.getElementById('overall-weighted-score').textContent = total.toFixed(2);

            document.getElementById('achievement-cemerlang').textContent = '';
            document.getElementById('achievement-baik').textContent = '';
            document.getElementById('achievement-sederhana').textContent = '';
            document.getElementById('achievement-lemah').textContent = '';
            document.getElementById('achievement-sangat-lemah').textContent = '';

            if (total >= 90) {
                document.getElementById('achievement-cemerlang').textContent = '✓';
            } else if (total >= 80) {
                document.getElementById('achievement-baik').textContent = '✓';
            } else if (total >= 50) {
                document.getElementById('achievement-sederhana').textContent = '✓';
            } else if (total >= 20) {
                document.getElementById('achievement-lemah').textContent = '✓';
            } else {
                document.getElementById('achievement-sangat-lemah').textContent = '✓';
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            const tumsIDs = new Set();

            document.querySelectorAll('[data-tums]').forEach(input => {
                tumsIDs.add(input.dataset.tums);
            });

            tumsIDs.forEach(tumsID => calculateTums(tumsID));
        });
    </script>

</x-app-layout>