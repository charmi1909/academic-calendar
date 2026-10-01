<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Holiday Report</title>

    <style>
        @page {
            margin: 32px 28px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9.5px;
            color: #3a4256;
        }

        .report-header {
            width: 100%;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 3px solid #3b4cca;
        }

        .report-header h2 {
            margin: 0;
            font-size: 20px;
            color: #151b2d;
            text-align: left;
        }

        .report-header p {
            margin: 5px 0 0;
            font-size: 10px;
            color: #7a8296;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        th,
        td {
            border-bottom: 1px solid #e6e9f0;
            padding: 8px 9px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            background: #3b4cca;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            border-bottom: none;
            padding-top: 9px;
            padding-bottom: 9px;
        }

        td {
            color: #3a4256;
            line-height: 1.5;
        }

        tbody tr:nth-child(even) td {
            background: #f7f8fc;
        }

        td:first-child {
            color: #151b2d;
            font-weight: bold;
        }

        .date {
            white-space: nowrap;
        }
    </style>
</head>

<body>

<div class="report-header">
    <h2>Holiday Report</h2>
    <p>Generated on {{ now()->format('d M Y') }}</p>
</div>

<table>

    <thead>

        <tr>

            @foreach($columns as $column)

                <th>
                    @switch($column)

                        @case('title')
                            Holiday Name
                            @break

                        @case('date')
                            Date
                            @break

                        @case('target')
                            Target
                            @break

                        @case('department')
                            Department
                            @break

                        @case('standard')
                            Standard
                            @break

                        @case('division')
                            Division
                            @break

                        @case('sms')
                            SMS
                            @break

                    @endswitch
                </th>

            @endforeach

        </tr>

    </thead>

  <tbody>

    @foreach($holidays as $holiday)

        @php

            $holidayFor = $holiday->holiday_for ?? [];

            if (is_string($holidayFor)) {
                $holidayFor = json_decode($holidayFor, true) ?? [];
            }

            /*
             * If holiday_for is empty because this is an old record,
             * determine Target from the saved fields.
             */

            if (empty($holidayFor)) {

                $hasStaff =
                    !empty($holiday->staff_departments);

                $hasStudent =
                    !empty($holiday->student_departments) ||
                    !empty($holiday->standards) ||
                    !empty($holiday->divisions);

                if ($hasStaff && $hasStudent) {

                    $holidayFor = ['All'];

                } elseif ($hasStaff) {

                    $holidayFor = ['Staff'];

                } elseif ($hasStudent) {

                    $holidayFor = ['Student'];

                }

            }

        @endphp

        <tr>

            @foreach($columns as $column)

                <td class="{{ $column === 'date' ? 'date' : '' }}">

                    @switch($column)

                        @case('title')

                            {{ $holiday->title }}

                            @break


                        @case('date')

                            {{ $holiday->start_date ?? $holiday->date }}

                            @if(
                                $holiday->end_date &&
                                $holiday->end_date != ($holiday->start_date ?? $holiday->date)
                            )
                                <br>
                                to {{ $holiday->end_date }}
                            @endif

                            @break


                        @case('target')

                            @if(!empty($holidayFor))

                                {{ implode(', ', $holidayFor) }}

                            @else

                                -

                            @endif

                            @break


                        @case('department')

                            @php

                                $departments = array_merge(
                                    $holiday->staff_departments ?? [],
                                    $holiday->student_departments ?? []
                                );

                            @endphp

                            @if(!empty($departments))

                                {{ implode(', ', $departments) }}

                            @else

                                -

                            @endif

                            @break


                        @case('standard')

                            @if(!empty($holiday->standards))

                                {{ implode(', ', $holiday->standards) }}

                            @else

                                -

                            @endif

                            @break


                        @case('division')

                            @if(!empty($holiday->divisions))

                                {{ implode(', ', $holiday->divisions) }}

                            @else

                                -

                            @endif

                            @break


                        @case('sms')

                            {{ $holiday->send_sms ? 'Yes' : 'No' }}

                            @break

                    @endswitch

                </td>

            @endforeach

        </tr>

    @endforeach

</tbody>

</table>

</body>
</html>