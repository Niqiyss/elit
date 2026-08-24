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

        /* Base */
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
            font-size: 9pt;
            line-height: 1.2;
        }


        /* Toolbar */
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


        /* A4 paper */
        .paper {
            width: 210mm;
            height: 297mm;
            margin: 15px auto;
            padding: 8mm 12mm;
            background: white;
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.15);
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
            width: 82%;
            margin: 0 auto 4mm;
            font-size: 9pt;
        }

        .meta-row {
            display: grid;
            grid-template-columns: 38mm 5mm 1fr;
            min-height: 4.6mm;
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
            min-height: 4.2mm;
            padding: 1px 2px;
            border-bottom: 1px solid #111;
            text-align: left;
            font-weight: 400;
        }


        /* Section */
        .print-section {
            margin-bottom: 3mm;
            page-break-inside: avoid;
        }


        /* Section tables */
        .display-table,
        .answer-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 9pt;
        }

        .display-table th,
        .display-table td,
        .answer-table th,
        .answer-table td {
            border: 1px solid #111;
            padding: 2.2mm 3mm;
        }


        /* Section heading */
        .section-title {
            background: #dbeafe;
            text-align: left;
            vertical-align: middle;
            font-size: 9.5pt;
            font-weight: 700;
            text-transform: uppercase;
        }


        /* Display fields */
        .display-table td {
            vertical-align: middle;
            text-align: left;
            font-weight: 400;
        }


        /* Answer label */
        .answer-label {
            width: 24%;
            background: #f8fafc;
            text-align: left;
            vertical-align: top;
            font-weight: 600;
        }


        /* Answer value */
        .answer-value {
            width: 76%;
            text-align: left;
            vertical-align: top;
            font-weight: 400;
            white-space: normal;
            overflow-wrap: break-word;
            word-break: normal;
        }


        /* Long answer */
        .answer-value.long-answer {
            line-height: 1.25;
        }


        /* Signature */
        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 35mm;
            width: 82%;
            margin: 7mm auto 0;
            page-break-inside: avoid;
        }

        .signature-box {
            font-size: 9pt;
            font-weight: 400;
            text-align: center;
        }

        .signature-title {
            margin: 0;
            text-align: center;
            font-weight: 400;
        }

        .signature-line {
            width: 75%;
            margin: 11mm auto 2mm;
            border-top: 1px solid #111;
        }

        .signature-name {
            margin: 0;
            text-align: center;
            font-weight: 400;
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
                height: 297mm;
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
                padding: 8mm 12mm;
                box-shadow: none;
                overflow: hidden;
            }

            .print-section,
            .signature-section {
                page-break-inside: avoid;
                break-inside: avoid;
            }

        }

    </style>

</head>


<body>


    {{-- Toolbar --}}
    <div class="toolbar">

        <div class="toolbar-title">
            {{ $response->observation_stage }} Observation Print Preview
        </div>


        <div class="toolbar-actions">

            <a
                href="{{ route(
                    $role === 'observer'
                        ? 'observer.manage'
                        : 'external.manage',
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



    {{-- Printable page --}}
    <main class="paper">


        {{-- Header --}}
        <header class="report-header">

            <h1>
                PUSAT PENDIDIKAN AL AMIN BERHAD (PPAAB)
            </h1>

            <p>
                {{ strtoupper($form->form_name) }}
            </p>

        </header>



        {{-- Observation information --}}
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
                    Time
                </div>

                <div class="meta-colon">
                    :
                </div>

                <div class="meta-value">

                    {{
                        $response->observation_time
                            ? \Carbon\Carbon::parse(
                                $response->observation_time
                            )->format('h:i A')
                            : '-'
                    }}

                </div>

            </div>


            <div class="meta-row">

                <div class="meta-label">

                    {{
                        $role === 'observer'
                            ? 'Observer'
                            : 'External Observer'
                    }}

                </div>

                <div class="meta-colon">
                    :
                </div>

                <div class="meta-value">

                    {{ strtoupper(
                        Auth::guard('teacher')->user()->teacher_name
                    ) }}

                </div>

            </div>


            <div class="meta-row">

                <div class="meta-label">
                    School
                </div>

                <div class="meta-colon">
                    :
                </div>

                <div class="meta-value">

                    {{ $guru->school?->school_name ?? '-' }}

                </div>

            </div>


        </section>



        {{-- Dynamic sections --}}
        @foreach($form->sections as $section)

            @php

                $displayFields =
                    $section->fields
                        ->where('field_type', 'display')
                        ->values();

                $inputFields =
                    $section->fields
                        ->where('field_type', '!=', 'display')
                        ->values();

            @endphp



            {{-- Display fields --}}
            @if($displayFields->isNotEmpty())

                <section class="print-section">

                    <table class="display-table">

                        <thead>

                            <tr>

                                <th
                                    colspan="4"
                                    class="section-title">

                                    {{ $section->section_name }}

                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($displayFields->chunk(4) as $row)

                                <tr>

                                    @foreach($row as $field)

                                        <td>
                                            {{ $field->field_label }}
                                        </td>

                                    @endforeach


                                    @if($row->count() < 4)

                                        @for($i = $row->count(); $i < 4; $i++)

                                            <td></td>

                                        @endfor

                                    @endif

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </section>

            @endif



            {{-- Input fields --}}
            @if($inputFields->isNotEmpty())

                <section class="print-section">

                    <table class="answer-table">

                        <thead>

                            <tr>

                                <th
                                    colspan="2"
                                    class="section-title">

                                    {{ $section->section_name }}

                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($inputFields as $field)

                                @php

                                    $answer =
                                        $existingAnswers[
                                            $field->fieldID
                                        ] ?? null;


                                    if (is_array($answer)) {

                                        $displayAnswer =
                                            implode(
                                                ', ',
                                                $answer
                                            );

                                    } else {

                                        $displayAnswer =
                                            $answer;
                                    }

                                @endphp


                                <tr>

                                    {{-- Label --}}
                                    <td class="answer-label">

                                        {{ $field->field_label }}

                                    </td>


                                    {{-- Answer --}}
                                    <td class="
                                        answer-value
                                        {{
                                            $field->field_type === 'textarea'
                                                ? 'long-answer'
                                                : ''
                                        }}">

                                        {{
                                            $displayAnswer !== null &&
                                            $displayAnswer !== ''
                                                ? $displayAnswer
                                                : '-'
                                        }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </section>

            @endif

        @endforeach



        {{-- Signature --}}
        <section class="signature-section">


            <div class="signature-box">

                <div class="signature-title">

                    Observed By,

                </div>


                <div class="signature-line"></div>


                <div class="signature-name">

                    (
                    {{ strtoupper(
                        Auth::guard('teacher')->user()->teacher_name
                    ) }}
                    )

                </div>

            </div>



            <div class="signature-box">

                <div class="signature-title">
                    Teacher Observed,
                </div>


                <div class="signature-line"></div>


                <div class="signature-name">

                    (
                    {{ strtoupper($guru->gn_name) }}
                    )

                </div>

            </div>


        </section>


    </main>


</body>

</html>