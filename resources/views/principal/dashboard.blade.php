<x-app-layout>

    <div class="min-h-screen bg-slate-100 py-8 px-6">

        <div class="max-w-7xl mx-auto">

            <div class="relative bg-gradient-to-br from-slate-900 via-violet-950 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden mb-8">

                <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <p class="text-xs uppercase tracking-[0.2em] font-bold text-violet-300">
                        Principal Dashboard
                    </p>

                    <h1 class="text-3xl font-extrabold text-white">
                        {{ $principal->principal_name }}
                    </h1>

                    <p class="text-base text-white mt-1">
                        {{ $school->school_name ?? '-' }}
                    </p>

                </div>

            </div>


            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">

                    <p class="text-sm font-semibold text-gray-900">
                        Total Teachers
                    </p>

                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $summary['total'] }}
                    </p>

                </div>


                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">

                    <p class="text-sm font-semibold text-gray-900">
                        Completed
                    </p>

                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $summary['completed'] }}
                    </p>

                </div>


                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">

                    <p class="text-sm font-semibold text-gray-900">
                        In Progress
                    </p>

                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $summary['in_progress'] }}
                    </p>

                </div>


                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-5 py-4">

                    <p class="text-sm font-semibold text-gray-900">
                        Repeat Required
                    </p>

                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $summary['repeat_required'] }}
                    </p>

                </div>

            </div>


            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-8">

                <div class="mb-5">

                    <h2 class="text-lg font-bold text-black">
                        Performance Filter
                    </h2>

                    <p class="text-sm text-black mt-1">
                        Filter PRE and PDPC performance by teacher, month, year or status
                    </p>

                </div>


                <form method="GET" action="{{ route('principal.dashboard') }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4 items-end">


                        <div class="lg:col-span-4">

                            <label class="block text-xs font-bold uppercase tracking-wider text-black mb-2">
                                Teacher
                            </label>

                            <div class="relative">

                                <svg
                                    class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z">
                                    </path>

                                </svg>


                                <input
                                    type="text"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Search teacher..."
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl text-sm text-black focus:border-blue-500 focus:ring-blue-500">

                            </div>

                        </div>


                        <div class="lg:col-span-2">

                            <label class="block text-xs font-bold uppercase tracking-wider text-black mb-2">
                                Month
                            </label>

                            <select
                                name="month"
                                class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-black focus:border-blue-500 focus:ring-blue-500">

                                <option value="all">
                                    All Months
                                </option>

                                @foreach([
                                1 => 'January',
                                2 => 'February',
                                3 => 'March',
                                4 => 'April',
                                5 => 'May',
                                6 => 'June',
                                7 => 'July',
                                8 => 'August',
                                9 => 'September',
                                10 => 'October',
                                11 => 'November',
                                12 => 'December'
                                ] as $monthNumber => $monthName)

                                <option
                                    value="{{ $monthNumber }}"
                                    {{ (string) $month === (string) $monthNumber ? 'selected' : '' }}>

                                    {{ $monthName }}

                                </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="lg:col-span-2">

                            <label class="block text-xs font-bold uppercase tracking-wider text-black mb-2">
                                Year
                            </label>

                            <select
                                name="year"
                                class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-black focus:border-blue-500 focus:ring-blue-500">

                                <option value="all">
                                    All Years
                                </option>

                                @foreach($years as $availableYear)

                                <option
                                    value="{{ $availableYear }}"
                                    {{ (string) $year === (string) $availableYear ? 'selected' : '' }}>

                                    {{ $availableYear }}

                                </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="lg:col-span-2">

                            <label class="block text-xs font-bold uppercase tracking-wider text-black mb-2">
                                Status
                            </label>

                            <select
                                name="status"
                                class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm text-black focus:border-blue-500 focus:ring-blue-500">

                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>
                                    All Status
                                </option>

                                <option value="Completed" {{ $status === 'Completed' ? 'selected' : '' }}>
                                    Completed
                                </option>

                                <option value="In Progress" {{ $status === 'In Progress' ? 'selected' : '' }}>
                                    In Progress
                                </option>

                                <option value="Repeat Required" {{ $status === 'Repeat Required' ? 'selected' : '' }}>
                                    Repeat Required
                                </option>

                            </select>

                        </div>


                        <div class="lg:col-span-2 flex gap-2">

                            <button
                                type="submit"
                                class="flex-1 px-3 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition">
                                Filter
                            </button>


                            <a
                                href="{{ route('principal.dashboard') }}"
                                class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-black text-sm font-semibold rounded-xl transition">
                                Reset
                            </a>

                        </div>

                    </div>

                </form>

            </div>


            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">

                <div class="bg-white rounded-3xl border border-slate-200 shadow-lg overflow-hidden">

                    <div class="px-7 py-5 border-b border-slate-100">

                        <h2 class="text-sm font-bold text-black">
                            PRE Performance
                        </h2>

                        <p class="text-sm text-black mt-1">
                            Number of teachers based on PRE achievement level
                        </p>

                    </div>


                    <div class="p-6">

                        @if($prePerformance['total'] > 0)

                        <div class="h-[300px]">
                            <canvas id="prePerformanceChart"></canvas>
                        </div>

                        @else

                        <div class="h-[300px] flex flex-col items-center justify-center">

                            <p class="font-semibold text-black">
                                No PRE performance data
                            </p>

                            <p class="text-sm text-black mt-1">
                                No submitted PRE results match the selected filters
                            </p>

                        </div>

                        @endif

                    </div>

                </div>


                <div class="bg-white rounded-3xl border border-slate-200 shadow-lg overflow-hidden">

                    <div class="px-7 py-5 border-b border-slate-100 flex items-start justify-between gap-4">

                        <div>

                            <h2 class="text-sm font-bold text-black">
                                PDPC Performance Trend
                            </h2>

                            <p class="text-sm text-black mt-1">
                                Average External and Post PDPC performance by month
                            </p>

                        </div>


                        <span class="shrink-0 inline-flex px-3 py-1.5 rounded-full bg-slate-100 text-black text-xs font-bold">
                            {{ $chartYear }}
                        </span>

                    </div>


                    <div class="p-6">

                        @if($pdpcTrendHasData)

                        <div class="h-[300px]">
                            <canvas id="pdpcTrendChart"></canvas>
                        </div>

                        @else

                        <div class="h-[300px] flex flex-col items-center justify-center">

                            <p class="font-semibold text-black">
                                No PDPC performance data
                            </p>

                            <p class="text-sm text-black mt-1">
                                No submitted PDPC results match the selected filters
                            </p>

                        </div>

                        @endif

                    </div>

                </div>

            </div>


            <div class="bg-white rounded-3xl border border-slate-200 shadow-lg overflow-hidden">


                <div class="px-7 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div>

                        <h2 class="text-xl font-bold text-black">
                            Evaluation Report
                        </h2>

                        <p class="text-sm text-black mt-1">
                            Current evaluation progress of active new teachers
                        </p>

                    </div>


                    <a
                        href="{{ route('principal.result') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition">

                        View Results

                    </a>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr class="bg-slate-50 text-xs uppercase tracking-wider text-black">

                                <th class="px-5 py-4 text-center">
                                    No.
                                </th>

                                <th class="px-5 py-4 text-left">
                                    Teacher
                                </th>

                                <th class="px-5 py-4 text-center">
                                    PRE
                                </th>

                                <th class="px-5 py-4 text-center">
                                    External
                                </th>

                                <th class="px-5 py-4 text-center">
                                    Post
                                </th>

                                <th class="px-5 py-4 text-center">
                                    Overall Status
                                </th>

                                <th class="px-5 py-4 text-center">
                                    Last Evaluation
                                </th>

                                <th class="px-5 py-4 text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @forelse($reportTeachers as $teacher)

                            <tr class="hover:bg-slate-50 transition">

                                <td class="px-5 py-5 text-center text-sm text-black">
                                    {{ $reportTeachers->firstItem() + $loop->index }}
                                </td>

                                <td class="px-5 py-5">

                                    <p class="font-semibold text-black">
                                        {{ $teacher->gn_name }}
                                    </p>

                                </td>

                                <td class="px-5 py-5 text-center font-bold text-black">
                                    {{ $teacher->pre_completed }}/{{ $teacher->pre_total }}
                                </td>

                                <td class="px-5 py-5 text-center font-bold text-black">
                                    {{ $teacher->external_completed }}/{{ $teacher->external_total }}
                                </td>


                                <td class="px-5 py-5 text-center font-bold text-black">
                                    {{ $teacher->post_completed }}/{{ $teacher->post_total }}
                                </td>


                                <td class="px-5 py-5 text-center">

                                    @if($teacher->evaluation_status === 'Completed')

                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                        Completed
                                    </span>


                                    @elseif($teacher->evaluation_status === 'Repeat Required')

                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                        Repeat Required
                                    </span>


                                    @else

                                    <span class="inline-flex px-3 py-1.5 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                        In Progress
                                    </span>

                                    @endif

                                </td>


                                <td class="px-5 py-5 text-center text-sm text-black">

                                    {{ $teacher->last_evaluation_date
                                        ? \Carbon\Carbon::parse($teacher->last_evaluation_date)->format('d/m/Y')
                                        : '-' }}

                                </td>


                                <td class="px-5 py-5 text-center">

                                    <a
                                        href="{{ route('principal.result.show', $teacher->gn_id) }}"
                                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">

                                        View

                                    </a>

                                </td>

                            </tr>


                            @empty

                            <tr>

                                <td colspan="8" class="px-6 py-14 text-center">

                                    <p class="font-semibold text-black">
                                        No evaluation records found
                                    </p>

                                    <p class="text-sm text-black mt-1">
                                        No active new teachers are currently available
                                    </p>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                @if($reportTeachers->hasPages())

                <div class="px-6 py-5 border-t border-slate-100">
                    {{ $reportTeachers->links() }}
                </div>

                @endif

            </div>


        </div>

    </div>


    {{-- CHART DATA --}}
    <div
        id="principalChartData"
        data-pre-labels="{{ json_encode($prePerformance['labels']) }}"
        data-pre-values="{{ json_encode($prePerformance['values']) }}"
        data-pdpc-labels="{{ json_encode($pdpcTrend['labels']) }}"
        data-pdpc-external="{{ json_encode($pdpcTrend['external']) }}"
        data-pdpc-post="{{ json_encode($pdpcTrend['post']) }}"
        data-pdpc-external-count="{{ json_encode($pdpcTrend['external_count']) }}"
        data-pdpc-post-count="{{ json_encode($pdpcTrend['post_count']) }}"
        class="hidden">
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const chartData = document.getElementById('principalChartData');

        const preLabels = JSON.parse(chartData.dataset.preLabels);
        const preValues = JSON.parse(chartData.dataset.preValues);
        const pdpcLabels = JSON.parse(chartData.dataset.pdpcLabels);
        const pdpcExternal = JSON.parse(chartData.dataset.pdpcExternal);
        const pdpcPost = JSON.parse(chartData.dataset.pdpcPost);
        const pdpcExternalCount = JSON.parse(chartData.dataset.pdpcExternalCount);
        const pdpcPostCount = JSON.parse(chartData.dataset.pdpcPostCount);

        Chart.defaults.color = '#000000';
        Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui, sans-serif';


        /* PRE PERFORMANCE */
        const preCanvas = document.getElementById('prePerformanceChart');

        if (preCanvas) {
            new Chart(preCanvas, {
                type: 'bar',

                data: {
                    labels: preLabels,

                    datasets: [{
                        label: 'Teachers',
                        data: preValues,
                        backgroundColor: '#7c3aed',
                        borderColor: '#6d28d9',
                        borderWidth: 1,
                        borderRadius: 6,
                        barThickness: 28,
                        maxBarThickness: 28,
                        categoryPercentage: 0.55,
                        barPercentage: 0.65
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    layout: {
                        padding: {
                            top: 5,
                            left: 5,
                            right: 5,
                            bottom: 0
                        }
                    },

                    scales: {
                        y: {
                            beginAtZero: true,

                            ticks: {
                                color: '#000000',
                                precision: 0,
                                stepSize: 1,
                                font: {
                                    size: 11
                                }
                            },

                            grid: {
                                color: 'rgba(0,0,0,0.08)'
                            },

                            title: {
                                display: true,
                                text: 'Number of Teachers',
                                color: '#000000',
                                font: {
                                    size: 11,
                                    weight: '600'
                                }
                            }
                        },

                        x: {
                            grid: {
                                display: false
                            },

                            ticks: {
                                color: '#000000',
                                maxRotation: 0,
                                minRotation: 0,
                                font: {
                                    size: 10
                                },

                                callback: function(value) {
                                    const label = this.getLabelForValue(value);
                                    const match = label.match(/^(.*?)\s(\(.*\))$/);
                                    return match ? [match[1], match[2]] : label;
                                }
                            }
                        }
                    },

                    plugins: {
                        legend: {
                            display: false
                        },

                        tooltip: {
                            callbacks: {
                                label: context => {
                                    const value = Number(context.raw);
                                    return `${value} teacher${value === 1 ? '' : 's'}`;
                                }
                            }
                        }
                    }
                }
            });
        }


        /* PDPC ACHIEVEMENT LEVEL */
        function getPdpcAchievementLevel(percentage) {
            percentage = Number(percentage);

            if (percentage >= 90) return 'Excellent (90-100%)';
            if (percentage >= 80) return 'Good (80-89%)';
            if (percentage >= 50) return 'Satisfactory (50-79%)';
            if (percentage >= 20) return 'Weak (20-49%)';

            return 'Very Weak (0-19%)';
        }


        /* PDPC PERFORMANCE TREND */
        const pdpcCanvas = document.getElementById('pdpcTrendChart');

        if (pdpcCanvas) {
            new Chart(pdpcCanvas, {
                type: 'line',

                data: {
                    labels: pdpcLabels,

                    datasets: [{
                            label: 'External',
                            data: pdpcExternal,
                            borderColor: '#2563eb',
                            backgroundColor: '#2563eb',
                            pointBackgroundColor: '#2563eb',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.35,
                            fill: false,
                            spanGaps: true
                        },
                        {
                            label: 'Post',
                            data: pdpcPost,
                            borderColor: '#f97316',
                            backgroundColor: '#f97316',
                            pointBackgroundColor: '#f97316',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.35,
                            fill: false,
                            spanGaps: true
                        }
                    ]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    interaction: {
                        mode: 'index',
                        intersect: false
                    },

                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            max: 100,

                            ticks: {
                                color: '#000000',
                                stepSize: 20,
                                font: {
                                    size: 12
                                },
                                callback: value => value + '%'
                            },

                            grid: {
                                color: 'rgba(0,0,0,0.08)'
                            },

                            title: {
                                display: true,
                                text: 'Average Score',
                                color: '#000000',
                                font: {
                                    size: 12,
                                    weight: '600'
                                }
                            }
                        },

                        x: {
                            grid: {
                                display: false
                            },

                            ticks: {
                                color: '#000000',
                                font: {
                                    size: 11
                                }
                            },

                            title: {
                                display: true,
                                text: 'Month',
                                color: '#000000',
                                font: {
                                    size: 12,
                                    weight: '600'
                                }
                            }
                        }
                    },

                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',

                            labels: {
                                color: '#000000',
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 18,
                                font: {
                                    size: 12
                                }
                            }
                        },

                        tooltip: {
                            callbacks: {
                                label: context => {
                                    if (context.raw === null || context.raw === undefined) {
                                        return `${context.dataset.label}: No data`;
                                    }

                                    const index = context.dataIndex;
                                    const percentage = Number(context.raw);

                                    const teacherCount = context.dataset.label === 'External' ?
                                        pdpcExternalCount[index] :
                                        pdpcPostCount[index];

                                    return `${context.dataset.label}: ${percentage.toFixed(2)}% — ${getPdpcAchievementLevel(percentage)} (${teacherCount} teacher${teacherCount === 1 ? '' : 's'})`;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>

</x-app-layout>