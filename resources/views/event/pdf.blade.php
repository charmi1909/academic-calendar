<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Events</title>

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

    </style>

</head>


<body>

<div class="report-header">
    <h2>Events</h2>
    <p>Generated on {{ now()->format('d M Y') }}</p>
</div>


<table>

    <thead>

        <tr>

            @foreach($columns as $column)

                <th>

                    @switch($column)

                        @case('title')
                            Event Name
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

    @foreach($events as $event)

        @php

            $eventFor = $event->event_for ?? [];

            if (is_string($eventFor)) {
                $eventFor =
                    json_decode($eventFor, true) ?? [];
            }

            if (empty($eventFor)) {

                $hasStaff =
                    !empty($event->staff_departments);

                $hasStudent =
                    !empty($event->student_departments) ||
                    !empty($event->standards) ||
                    !empty($event->divisions);

                if ($hasStaff && $hasStudent) {
                    $eventFor = ['All'];
                } elseif ($hasStaff) {
                    $eventFor = ['Staff'];
                } elseif ($hasStudent) {
                    $eventFor = ['Student'];
                }
            }

            $departments = array_merge(
                $event->staff_departments ?? [],
                $event->student_departments ?? []
            );

        @endphp


        <tr>

            @foreach($columns as $column)

                <td>

                    @switch($column)

                        @case('title')

                            {{ $event->title ?: '-' }}

                            @break


                        @case('date')

                            @if($event->start_date)

                                @if(
                                    $event->end_date &&
                                    $event->end_date != $event->start_date
                                )

                                    {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}

                                @else

                                    {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}

                                @endif

                            @else

                                -

                            @endif

                            @break


                        @case('target')

                            {{ !empty($eventFor)
                                ? implode(', ', $eventFor)
                                : '-' }}

                            @break


                        @case('department')

                            {{ !empty($departments)
                                ? implode(', ', $departments)
                                : '-' }}

                            @break


                        @case('standard')

                            {{ !empty($event->standards)
                                ? implode(', ', $event->standards)
                                : '-' }}

                            @break


                        @case('division')

                            {{ !empty($event->divisions)
                                ? implode(', ', $event->divisions)
                                : '-' }}

                            @break


                        @case('sms')

                            {{ $event->send_sms ? 'Yes' : 'No' }}

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