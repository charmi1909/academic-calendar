@extends('layouts.academic')

@section('title', 'Academic Calendar')

@section('content')

<style>
    .calendar-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Header */

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .calendar-header h1 {
        margin: 0;
        font-size: 28px;
        color: #111827;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .year-select {
        padding: 9px 35px 9px 12px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: white;
        font-size: 14px;
        cursor: pointer;
    }

    .list-btn {
        padding: 9px 15px;
        border: 1px solid #d1d5db;
        background: white;
        color: #374151;
        border-radius: 7px;
        font-size: 14px;
        cursor: pointer;
        font-weight: 600;
    }

    .list-btn:hover {
        background: #f3f4f6;
    }

    .list-btn.active {
        background: #111827;
        color: white;
        border-color: #111827;
    }

    /* Calendar */

    .calendar-card {
        background: white;
        border-radius: 14px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .calendar-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .month-title {
        font-size: 18px;
        font-weight: 600;
        color: #111827;
    }

    .calendar-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .nav-btn {
        width: 38px;
        height: 38px;
        border: 1px solid #e5e7eb;
        background: white;
        border-radius: 7px;
        text-decoration: none;
        color: #374151;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .nav-btn:hover {
        background: #f3f4f6;
    }

    .today-btn {
        padding: 9px 15px;
        border: 1px solid #2563eb;
        color: #2563eb;
        background: white;
        border-radius: 7px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .today-btn:hover {
        background: #eff6ff;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }

    .weekday {
        padding: 14px 10px;
        text-align: center;
        font-size: 12px;
        font-weight: 700;
        color: #6b7280;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .day {
        min-height: 95px;
        padding: 8px;
        border-right: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        background: white;
        position: relative;
    }

    .day:nth-child(7n) {
        border-right: none;
    }

    .empty-day {
        background: #fafafa;
    }

    .day-number {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        border-radius: 50%;
    }

    .today .day-number {
        background: #2563eb;
        color: white;
    }

    /* Holiday */

    .holiday-event {
        margin-top: 6px;
        padding: 5px 7px;
        background: #dcfce7;
        color: #166534;
        border-left: 3px solid #16a34a;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.3;
    }

    /* Event */

    .event-item {
        margin-top: 6px;
        padding: 5px 7px;
        background: #dbeafe;
        color: #1d4ed8;
        border-left: 3px solid #2563eb;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.3;
    }

    /* PTM */

    .ptm-item {
        margin-top: 7px;
        padding: 5px 7px;
        background: #fce7f3;
        color: #be185d;
        border-left: 3px solid #ec4899;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.3;
    }

    /* Legend */

    .legend {
        display: flex;
        align-items: center;
        gap: 25px;
        padding: 18px 25px;
        border-top: 1px solid #e5e7eb;
        background: #fafafa;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #4b5563;
    }

    .legend-dot {
        width: 11px;
        height: 11px;
        border-radius: 3px;
    }

    .holiday-dot {
        background: #16a34a;
    }

    .event-dot {
        background: #2563eb;
    }

    .ptm-dot {
        background: #ec4899;
    }

    /* Upcoming List */

    .upcoming-list {
        display: none;
        background: white;
        border-radius: 14px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    .upcoming-list.show {
        display: block;
    }

    .list-header {
        padding: 20px 25px;
        border-bottom: 1px solid #e5e7eb;
    }

    .list-header h2 {
        margin: 0;
        font-size: 20px;
        color: #111827;
    }

    .list-header p {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 13px;
    }

    .upcoming-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 25px;
        border-bottom: 1px solid #f1f5f9;
    }

    .upcoming-item:last-child {
        border-bottom: none;
    }

    .upcoming-date {
        width: 70px;
        text-align: center;
        flex-shrink: 0;
    }

    .upcoming-date .day {
        min-height: auto;
        padding: 0;
        border: none;
        background: none;
        font-size: 22px;
        font-weight: bold;
    }

    .upcoming-date .month {
        font-size: 11px;
        color: #6b7280;
        text-transform: uppercase;
        font-weight: 600;
    }

    .upcoming-info {
        flex: 1;
    }

    .upcoming-title {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
    }

    .upcoming-type {
        display: inline-block;
        margin-top: 5px;
        padding: 4px 9px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 700;
    }

    /* Upcoming Holiday */

    .upcoming-holiday {
        border-left: 4px solid #16a34a;
    }

    .upcoming-holiday .day {
        color: #16a34a;
    }

    .type-holiday {
        background: #dcfce7;
        color: #166534;
    }

    /* Upcoming Event */

    .upcoming-event {
        border-left: 4px solid #2563eb;
    }

    .upcoming-event .day {
        color: #2563eb;
    }

    .type-event {
        background: #dbeafe;
        color: #1d4ed8;
    }

    /* Upcoming PTM */

    .upcoming-ptm {
        border-left: 4px solid #ec4899;
    }

    .upcoming-ptm .day {
        color: #ec4899;
    }

    .type-ptm {
        background: #fce7f3;
        color: #be185d;
    }

    .no-upcoming {
        padding: 40px;
        text-align: center;
        color: #6b7280;
        font-size: 14px;
    }

    /* Mobile */

    @media (max-width: 800px) {

        .calendar-container {
            width: 100%;
        }

        .calendar-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .header-actions {
            width: 100%;
        }

        .year-select {
            flex: 1;
        }

        .list-btn {
            white-space: nowrap;
        }

        .day {
            min-height: 90px;
            padding: 6px;
        }

        .holiday-event,
        .event-item,
        .ptm-item {
            font-size: 9px;
        }

        .legend {
            gap: 15px;
        }
    }
</style>

<div class="calendar-container">

    <!-- Header -->

    <div class="calendar-header">

        <h1>
            Academic Calendar
        </h1>

        <div class="header-actions">

            <select
                class="year-select"
                onchange="changeYear(this.value)"
            >

                @for($year = 2021; $year <= 2030; $year++)

                    <option
                        value="{{ $year }}"
                        {{ $calendar['year'] == $year ? 'selected' : '' }}
                    >
                        {{ $year }}
                    </option>

                @endfor

            </select>

            <button
                type="button"
                class="list-btn"
                id="listButton"
                onclick="toggleUpcomingList()"
            >
                ☷ List
            </button>

        </div>

    </div>


    <!-- Calendar -->

    <div class="calendar-card" id="calendarCard">

        <!-- Calendar Top -->

        <div class="calendar-top">

            <div class="month-title">
                {{ $calendar['name'] }}
            </div>

            <div class="calendar-actions">

                <a
                    href="{{ route('annual_calendar.index', ['month' => $previousMonth]) }}"
                    class="nav-btn"
                >
                    ←
                </a>

                <a
                    href="{{ route('annual_calendar.index', ['month' => now()->format('Y-m')]) }}"
                    class="today-btn"
                >
                    Today
                </a>

                <a
                    href="{{ route('annual_calendar.index', ['month' => $nextMonth]) }}"
                    class="nav-btn"
                >
                    →
                </a>

            </div>

        </div>


        <!-- Weekdays -->

        <div class="calendar-grid">

            <div class="weekday">SUN</div>
            <div class="weekday">MON</div>
            <div class="weekday">TUE</div>
            <div class="weekday">WED</div>
            <div class="weekday">THU</div>
            <div class="weekday">FRI</div>
            <div class="weekday">SAT</div>


            <!-- Empty Days -->

            @for($i = 0; $i < $calendar['startDay']; $i++)

                <div class="day empty-day"></div>

            @endfor


            <!-- Calendar Days -->

            @for($day = 1; $day <= $calendar['daysInMonth']; $day++)

                @php

                    $currentDate = sprintf(
                        '%04d-%02d-%02d',
                        $calendar['year'],
                        $calendar['month'],
                        $day
                    );

                    $isToday = $currentDate == now()->format('Y-m-d');

                @endphp


                <div class="day {{ $isToday ? 'today' : '' }}">

                    <div class="day-number">
                        {{ $day }}
                    </div>


                    <!-- Holiday -->

                    @if(isset($holidays[$currentDate]))

                        @foreach($holidays[$currentDate] as $holiday)

                            <div class="holiday-event">
                                {{ $holiday->title }}
                            </div>

                        @endforeach

                    @endif


                    <!-- Event -->

                    @if(isset($events[$currentDate]))

                        @foreach($events[$currentDate] as $event)

                            <div class="event-item">
                                {{ $event->title }}
                            </div>

                        @endforeach

                    @endif


                    <!-- PTM -->

                    @if(isset($ptms[$currentDate]))

                        @foreach($ptms[$currentDate] as $ptm)

                            <div class="ptm-item">
                                {{ $ptm->title }}
                            </div>

                        @endforeach

                    @endif

                </div>

            @endfor

        </div>


        <!-- Legend -->

        <div class="legend">

            <div class="legend-item">
                <span class="legend-dot holiday-dot"></span>
                Holiday
            </div>

            <div class="legend-item">
                <span class="legend-dot event-dot"></span>
                Event
            </div>

            <div class="legend-item">
                <span class="legend-dot ptm-dot"></span>
                PTM
            </div>

        </div>

    </div>


    <!-- Upcoming List -->

    <div class="upcoming-list" id="upcomingList">

        <div class="list-header">

            <h2>
                Upcoming
            </h2>

            <p>
                All upcoming holidays, events and PTMs
            </p>

        </div>


        @php

            $upcomingItems = collect();

            foreach ($upcomingHolidays as $holiday) {

                $upcomingItems->push([
                    'title' => $holiday->title,
                    'date' => $holiday->date,
                    'type' => 'Holiday',
                    'class' => 'upcoming-holiday',
                    'typeClass' => 'type-holiday',
                ]);

            }

            foreach ($upcomingEvents as $event) {

                $upcomingItems->push([
                    'title' => $event->title,
                    'date' => $event->date,
                    'type' => 'Event',
                    'class' => 'upcoming-event',
                    'typeClass' => 'type-event',
                ]);

            }

            foreach ($upcomingPtms as $ptm) {

                $upcomingItems->push([
                    'title' => $ptm->title,
                    'date' => $ptm->date,
                    'type' => 'PTM',
                    'class' => 'upcoming-ptm',
                    'typeClass' => 'type-ptm',
                ]);

            }

            $upcomingItems = $upcomingItems->sortBy('date');

        @endphp


        @forelse($upcomingItems as $item)

            @php
                $itemDate = \Carbon\Carbon::parse($item['date']);
            @endphp


            <div class="upcoming-item {{ $item['class'] }}">

                <div class="upcoming-date">

                    <div class="day">
                        {{ $itemDate->format('d') }}
                    </div>

                    <div class="month">
                        {{ $itemDate->format('M') }}
                    </div>

                </div>


                <div class="upcoming-info">

                    <div class="upcoming-title">
                        {{ $item['title'] }}
                    </div>

                    <span class="upcoming-type {{ $item['typeClass'] }}">
                        {{ $item['type'] }}
                    </span>

                </div>

            </div>

        @empty

            <div class="no-upcoming">
                No upcoming holidays, events or PTMs.
            </div>

        @endforelse

    </div>

</div>


<script>

    function changeYear(year) {

        const currentMonth = "{{ $calendar['month'] }}";

        const month = String(currentMonth).padStart(2, '0');

        window.location.href =
            "{{ route('annual_calendar.index') }}?month=" + year + "-" + month;

    }


    function toggleUpcomingList() {

        const calendar = document.getElementById('calendarCard');

        const list = document.getElementById('upcomingList');

        const button = document.getElementById('listButton');


        if (list.classList.contains('show')) {

            list.classList.remove('show');

            calendar.style.display = 'block';

            button.classList.remove('active');

            button.innerHTML = '☷ List';

        } else {

            calendar.style.display = 'none';

            list.classList.add('show');

            button.classList.add('active');

            button.innerHTML = '▦ Calendar';

        }

    }

</script>

@endsection