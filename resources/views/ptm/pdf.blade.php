<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>PTM Export</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }

        th,
        td {
            border: 1px solid #cccccc;
            padding: 7px;
            vertical-align: top;
        }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            margin: 1px;
            border: 1px solid #dddddd;
            border-radius: 3px;
        }

        .empty {
            color: #777;
        }
    </style>

</head>

<body>

    <h2>
        PTM
    </h2>


    <table>

        <thead>

            <tr>

                @foreach ($columns as $column)

                    <th>

                        @switch($column)

                            @case('title')
                                PTM Name
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

                            @default
                                {{ $column }}

                        @endswitch

                    </th>

                @endforeach

            </tr>

        </thead>


        <tbody>

            @foreach ($ptms as $ptm)

                @php

                    $ptmFor =
                        $ptm->ptm_for ?? [];

                    $staffDepartments =
                        $ptm->staff_departments ?? [];

                    $studentDepartments =
                        $ptm->student_departments ?? [];

                    $departments =
                        array_merge(
                            $staffDepartments,
                            $studentDepartments
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Fallback for old records
                    |--------------------------------------------------------------------------
                    */

                    if (empty($ptmFor)) {

                        $hasStaff =
                            !empty(
                                $staffDepartments
                            );

                        $hasStudent =
                            !empty(
                                $studentDepartments
                            ) ||
                            !empty(
                                $ptm->standards
                            ) ||
                            !empty(
                                $ptm->divisions
                            );


                        if (
                            $hasStaff &&
                            $hasStudent
                        ) {

                            $ptmFor = ['All'];

                        } elseif ($hasStaff) {

                            $ptmFor = ['Staff'];

                        } elseif ($hasStudent) {

                            $ptmFor = ['Student'];

                        }
                    }

                @endphp


                <tr>

                    @foreach ($columns as $column)

                        <td>

                            @switch($column)

                                {{-- PTM Name --}}
                                @case('title')

                                    {{ $ptm->title ?: '-' }}

                                    @break


                                {{-- Date --}}
                                @case('date')

                                    @if ($ptm->start_date)

                                        {{ \Carbon\Carbon::parse($ptm->start_date)->format('d M Y') }}

                                        @if (
                                            $ptm->end_date &&
                                            $ptm->end_date != $ptm->start_date
                                        )

                                            -
                                            {{ \Carbon\Carbon::parse($ptm->end_date)->format('d M Y') }}

                                        @endif

                                    @else

                                        -

                                    @endif

                                    @break


                                {{-- Target --}}
                                @case('target')

                                    @if (!empty($ptmFor))

                                        {{ implode(', ', $ptmFor) }}

                                    @else

                                        -

                                    @endif

                                    @break


                                {{-- Department --}}
                                @case('department')

                                    @if (!empty($departments))

                                        {{ implode(', ', $departments) }}

                                    @else

                                        -

                                    @endif

                                    @break


                                {{-- Standard --}}
                                @case('standard')

                                    @if (!empty($ptm->standards))

                                        {{ implode(', ', $ptm->standards) }}

                                    @else

                                        -

                                    @endif

                                    @break


                                {{-- Division --}}
                                @case('division')

                                    @if (!empty($ptm->divisions))

                                        {{ implode(', ', $ptm->divisions) }}

                                    @else

                                        -

                                    @endif

                                    @break


                                {{-- SMS --}}
                                @case('sms')

                                    {{ $ptm->send_sms ? 'Yes' : 'No' }}

                                    @break


                                @default

                                    -

                            @endswitch

                        </td>

                    @endforeach

                </tr>

            @endforeach

        </tbody>

    </table>

</body>
</html>