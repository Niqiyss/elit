<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        {{ $response->observation_stage }} - {{ $form->form_name }} - {{ $guru->gn_name }}
    </title>


    <style>
        * {
            box-sizing: border-box;
        }


        html,
        body {
            margin: 0;
            padding: 0;
        }


        body {
            background: #eef2f7;
            color: #111;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.2;
        }



        /* Preview toolbar */
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 50;

            display: flex;
            justify-content: space-between;
            align-items: center;

            padding: 12px 24px;

            background: #0f172a;
            color: white;

            font-size: 14px;
        }


        .toolbar-title {
            font-size: 14px;
            font-weight: 600;
        }


        .toolbar-actions {
            display: flex;
            gap: 10px;
        }


        .button {
            display: inline-block;

            padding: 9px 15px;

            border: 0;
            border-radius: 7px;

            background: #2563eb;
            color: white;

            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            font-weight: 600;

            text-decoration: none;
            cursor: pointer;
        }


        .button.secondary {
            background: white;
            color: #334155;
        }



        /* A4 page */
        .paper {
            width: 210mm;
            height: 297mm;

            margin: 15px auto;

            padding:
                8mm 12mm 8mm;

            background: white;

            box-shadow:
                0 8px 25px rgba(15, 23, 42, 0.15);

            overflow: hidden;
        }



        /* Report header */
        .report-header {
            margin: 0 0 4mm;

            text-align: center;

            line-height: 1.2;
        }


        .report-header h1 {
            margin: 0 0 2px;
            font-size: 10pt;
            font-weight: 700;
        }

        .report-header p {
            margin: 0;
            font-size: 10pt;
            font-weight: 700;
        }



        /* Observation information */
        .meta {
            width: 76%;

            margin:
                0 auto 4mm;

            font-size: 10pt;
        }


        .meta-row {
            display: grid;

            grid-template-columns:
                42mm 5mm 1fr;

            min-height: 5mm;

            align-items: center;
        }


        .meta-label {
            font-weight: 400;
            text-transform: uppercase;
        }


        .meta-colon {
            font-weight: 400;
        }


        .meta-value {
            min-height: 4.5mm;

            padding:
                1px 2px;

            border-bottom: 1px solid #111;

            font-weight: 400;

            text-align: left;
        }



        /* Evaluation table */
        .score-table {
            width: 100%;

            border-collapse: collapse;
            table-layout: fixed;

            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
        }


        .score-table th,
        .score-table td {
            border: 1px solid #111;

            padding:
                3px 5px;

            line-height: 1.15;
        }


        .score-table thead th {
            height: 7mm;

            background: white;

            text-align: center;
            vertical-align: middle;

            font-size: 9pt;
            font-weight: 600;
        }


        .no-column {
            width: 6%;

            text-align: center;
            vertical-align: top;

            font-weight: 400;
        }


        .criteria-column {
            width: 55%;

            text-align: left;
            vertical-align: top;

            font-weight: 400;
        }


        .score-column {
            width: 11%;

            text-align: center;
            vertical-align: middle;

            font-size: 9pt;
            font-weight: 400;
        }


        .comment-column {
            width: 28%;

            padding:
                3px 5px !important;

            text-align: left !important;
            vertical-align: top !important;

            font-size: 9pt;
            font-weight: 400;

            white-space: normal !important;
            word-break: break-word;
        }


        .comment-text {
            display: block;

            width: 100%;

            margin: 0;
            padding: 0;

            text-align: left !important;

            font-weight: 400;

            white-space: normal !important;
        }


        .section-title {
            margin: 0 0 2px;
            padding: 0;

            font-weight: 600;

            text-transform: uppercase;

            text-align: left;
        }


        .criteria-text {
            margin: 0;
            padding: 0;

            line-height: 1.15;

            text-align: left;
        }


        .criteria-letter {
            font-weight: 400;
        }


        .criteria-row td {
            height: auto;
        }



        /* Total row */
        .total-row td {
            padding:
                3px 5px;

            font-size: 9pt;
        }


        .total-empty {
            background: white;
        }


        .total-label {
            text-align: right;

            font-weight: 600;
        }


        .total-score {
            text-align: center;

            font-weight: 400;
        }


        .total-percentage {
            text-align: left;
            font-weight: 700;
        }



        /* Page 2 */
        .page-two {
            page-break-before: always;
        }



        /* Other comment */
        .other-comment-section {
            margin: 0;
            padding: 0;
        }


        .page-two-title {
            margin:
                0 0 4mm;

            padding: 0;

            font-size: 10pt;
            font-weight: 700;

            text-transform: uppercase;

            text-align: left;
        }


        .other-comment-box {
            width: 100%;

            min-height: 55mm;

            padding:
                2mm 1mm;

            border: none;

            font-size: 10pt;
            font-weight: 400;

            line-height: 1.4;

            text-align: left !important;

            white-space: normal;
            word-break: break-word;
        }



        /* Achievement Level */
        .achievement-section {
            width: 82%;

            margin:
                10mm auto 0;

            page-break-inside: avoid;
        }


        .achievement-table {
            width: 100%;

            border-collapse: collapse;
            table-layout: fixed;

            font-size: 10pt;
        }


        .achievement-table th,
        .achievement-table td {
            height: 9mm;

            padding: 4px;

            border: 1px solid #111;

            text-align: center;
            vertical-align: middle;

            font-weight: 400;
        }


        .achievement-heading {
            height: 10mm !important;

            font-weight: 700 !important;
        }


        .achievement-active {
            background: #dbeafe !important;

            font-weight: 400 !important;
        }



        /* Signature */
        .signature-section {
            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 35mm;

            width: 82%;

            margin:
                15mm auto 0;

            page-break-inside: avoid;
        }


        .signature-box {
            font-size: 10pt;
            font-weight: 400;

            text-align: center;
        }


        .signature-title {
            margin: 0;

            font-weight: 400;

            text-align: center;
        }


        .signature-line {
            width: 75%;

            margin:
                18mm auto 2mm;

            border-top: 1px solid #111;
        }


        .signature-name {
            margin: 0;

            font-weight: 400;

            text-align: center;
        }



        /* Print */
        @page {
            size: A4 portrait;
            margin: 0;
        }


        @media print {

            html,
            body {
                width: 210mm;

                margin: 0;
                padding: 0;

                background: white;

                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }


            .toolbar {
                display: none !important;
            }


            .paper {
                width: 210mm;
                height: 297mm;

                margin: 0;

                padding:
                    8mm 12mm 8mm;

                box-shadow: none;

                overflow: hidden;
            }


            .page-one {
                page-break-after: always;
            }


            .page-two {
                page-break-before: auto;
                page-break-after: auto;
            }


            .score-table tr {
                page-break-inside: avoid;
            }


            .achievement-section,
            .signature-section {
                page-break-inside: avoid;
            }

        }
    </style>

</head>


<body>


    {{-- Preview toolbar --}}
    <div class="toolbar">

        <div class="toolbar-title">
            Pre-Observation Print Preview
        </div>


        <div class="toolbar-actions">

            <a
                href="{{ route(
                    'observer.manage',
                    $guru->gn_id
                ) }}"
                class="button secondary">

                Back

            </a>


            <button
                type="button"
                class="button"
                onclick="window.print()">

                Print / Save PDF

            </button>

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- PAGE 1 --}}
    {{-- ===================================================== --}}

    <main class="paper page-one">


        {{-- Report Header --}}
        <header class="report-header">

            <h1>
                PUSAT PENDIDIKAN AL AMIN BERHAD (PPAAB)
            </h1>

            <p>
                {{ strtoupper($form->form_name) }}
            </p>

        </header>



        {{-- Observation Information --}}
        <section class="meta">


            <div class="meta-row">

                <div class="meta-label">
                    Teacher Name
                </div>

                <div class="meta-colon">
                    :
                </div>

                <div class="meta-value">
                    {{ strtoupper($guru->gn_name) }}
                </div>

            </div>



            <div class="meta-row">

                <div class="meta-label">
                    Subject & Class
                </div>

                <div class="meta-colon">
                    :
                </div>

                <div class="meta-value">

                    {{ $response->subject_name ?? '-' }}

                    /

                    {{ $response->class_name ?? '-' }}

                </div>

            </div>



            <div class="meta-row">

                <div class="meta-label">
                    Date
                </div>

                <div class="meta-colon">
                    :
                </div>

                <div class="meta-value">

                    {{
                        $response->observation_date
                            ? \Carbon\Carbon::parse(
                                $response->observation_date
                            )->format('d/m/Y')
                            : '-'
                    }}

                </div>

            </div>



            <div class="meta-row">

                <div class="meta-label">
                    Observer
                </div>

                <div class="meta-colon">
                    :
                </div>

                <div class="meta-value">

                    {{
                        strtoupper(
                            $response->observer?->teacher?->teacher_name
                            ?? Auth::guard('teacher')->user()->teacher_name
                        )
                    }}

                </div>

            </div>


        </section>



        {{-- Evaluation Table --}}
        <table class="score-table">


            <thead>

                <tr>

                    <th class="no-column">
                        NO.
                    </th>

                    <th class="criteria-column">
                        SECTION / CRITERIA
                    </th>

                    <th class="score-column">
                        SCORE
                    </th>

                    <th class="comment-column">
                        COMMENT
                    </th>

                </tr>

            </thead>



            <tbody>


                @foreach($form->sections as $section)

                @if($section->criteria->isNotEmpty())


                @php

                $criteriaCount =
                $section->criteria->count();

                @endphp



                @foreach($section->criteria as $criteria)


                @php

                $letter =
                chr(
                96 + $loop->iteration
                );

                $selectedScore =
                $scores->get(
                $criteria->criteriaID
                );

                @endphp



                <tr class="criteria-row">


                    {{-- Number --}}
                    @if($loop->first)

                    <td
                        rowspan="{{ $criteriaCount }}"
                        class="no-column">

                        {{ $loop->parent->iteration }}

                    </td>

                    @endif



                    {{-- Criteria --}}
                    <td class="criteria-column">


                        @if($loop->first)

                        <div class="section-title">

                            {{ $section->section_name }}

                        </div>

                        @endif


                        <div class="criteria-text">

                            <span class="criteria-letter">

                                {{ $letter }}.

                            </span>

                            {{ $criteria->criteria_label }}

                        </div>


                    </td>



                    {{-- Score --}}
                    <td class="score-column">

                        {{ $selectedScore ?? '-' }}

                    </td>



                    {{-- One Comment per Section --}}
                    @if($loop->first)

                    <td
                        rowspan="{{ $criteriaCount }}"
                        class="comment-column">

                        <span class="comment-text">{{ $sectionComments->get($section->sectionID) ?? '-' }}</span>

                    </td>

                    @endif


                </tr>


                @endforeach


                @endif


                @endforeach



                {{-- Total --}}
                <tr class="total-row">


                    <td class="total-empty">
                    </td>


                    <td class="total-label">

                        TOTAL

                    </td>


                    <td class="total-score">

                        {{ $response->total_score ?? 0 }}

                        /

                        {{
                            $form->sections
                                ->flatMap(
                                    fn($section) =>
                                        $section->criteria
                                )
                                ->count() * 5
                        }}

                    </td>


                    <td class="total-percentage">

                        PERCENTAGE :

                        {{
                            number_format(
                                $response->percentage ?? 0,
                                1
                            )
                        }}%

                    </td>


                </tr>


            </tbody>


        </table>


    </main>



    {{-- ===================================================== --}}
    {{-- PAGE 2 --}}
    {{-- ===================================================== --}}

    <main class="paper page-two">


        {{-- Other Comment --}}
        <section class="other-comment-section">


            <div class="page-two-title">

                Other Comment :

            </div>


            <div class="other-comment-box">{{ $response->other_comment ?: '-' }}</div>


        </section>



        {{-- Achievement Level --}}
        <section class="achievement-section">


            <table class="achievement-table">


                <thead>

                    <tr>

                        <th
                            colspan="5"
                            class="achievement-heading">

                            Achievement Level :

                        </th>

                    </tr>

                </thead>



                <tbody>


                    <tr>


                        <td class="{{
                            $response->achievement_level === 'Weak'
                                ? 'achievement-active'
                                : ''
                        }}">

                            Weak

                        </td>


                        <td class="{{
                            $response->achievement_level === 'Satisfactory'
                                ? 'achievement-active'
                                : ''
                        }}">

                            Satisfactory

                        </td>


                        <td class="{{
                            $response->achievement_level === 'Good'
                                ? 'achievement-active'
                                : ''
                        }}">

                            Good

                        </td>


                        <td class="{{
                            $response->achievement_level === 'Very Good'
                                ? 'achievement-active'
                                : ''
                        }}">

                            Very Good

                        </td>


                        <td class="{{
                            $response->achievement_level === 'Excellent'
                                ? 'achievement-active'
                                : ''
                        }}">

                            Excellent

                        </td>


                    </tr>



                    <tr>


                        <td class="{{
                            $response->achievement_level === 'Weak'
                                ? 'achievement-active'
                                : ''
                        }}">

                            0% - 39%

                        </td>


                        <td class="{{
                            $response->achievement_level === 'Satisfactory'
                                ? 'achievement-active'
                                : ''
                        }}">

                            40% - 59%

                        </td>


                        <td class="{{
                            $response->achievement_level === 'Good'
                                ? 'achievement-active'
                                : ''
                        }}">

                            60% - 79%

                        </td>


                        <td class="{{
                            $response->achievement_level === 'Very Good'
                                ? 'achievement-active'
                                : ''
                        }}">

                            80% - 89%

                        </td>


                        <td class="{{
                            $response->achievement_level === 'Excellent'
                                ? 'achievement-active'
                                : ''
                        }}">

                            90% - 100%

                        </td>


                    </tr>


                </tbody>


            </table>


        </section>



        {{-- Signature --}}
        <section class="signature-section">


            <div class="signature-box">


                <div class="signature-title">

                    Observed By,

                </div>


                <div class="signature-line">
                </div>


                <div class="signature-name">

                    ( {{
                        strtoupper(
                            $response->observer?->teacher?->teacher_name
                            ?? Auth::guard('teacher')->user()->teacher_name
                        )
                    }} )

                </div>


            </div>



            <div class="signature-box">


                <div class="signature-title">

                    Teacher Observed,

                </div>


                <div class="signature-line">
                </div>


                <div class="signature-name">

                    ( {{ strtoupper($guru->gn_name) }} )

                </div>


            </div>


        </section>


    </main>


</body>

</html>