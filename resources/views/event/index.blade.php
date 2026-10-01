@extends('layouts.academic')

@section('content')

<div class="holiday-page">

    <!-- Header -->
    <div class="holiday-header">
        <div class="header-text">
            <div class="breadcrumb">
                Academic Calendar <span>/</span> Events
            </div>

            <h1>Event Management</h1>
            <p>Manage school events, targets and notifications.</p>
        </div>

        <div class="header-actions">
            <a href="{{ route('event.export') }}" class="export-btn">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 4v11m0 0l-4-4m4 4l4-4M5 19h14" />
                </svg>
                Export
            </a>

            <a href="{{ route('event.create') }}" class="add-btn">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Add Event
            </a>
        </div>
    </div>


    <!-- Success -->
    @if(session('success'))
        <div class="success-alert" role="status">
            <div class="success-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M5 12.5l4.5 4.5L19 7.5" />
                </svg>
            </div>

            <div>
                <strong>Success</strong>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif


    <!-- Table Card -->
    <div class="holiday-card">

        <!-- Table Header -->
        <div class="table-header">

            <div>
                <h2>All Events</h2>
                <p>View and manage all configured events.</p>
            </div>

            <!-- Search -->
            <form
                method="GET"
                action="{{ route('event.index') }}"
                class="search-box"
            >
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="6.5" />
                    <path d="M20 20l-4-4" />
                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Search events..."
                    aria-label="Search events"
                >
            </form>

        </div>


        <!-- Table -->
        <div class="table-wrapper">

            <table id="eventTable">

                <thead>
                    <tr>
                        <th class="col-number">#</th>
                        <th>Action</th>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Target</th>
                        <th>Department</th>
                        <th>Standard</th>
                        <th>Division</th>
                        <th>SMS</th>
                    </tr>
                </thead>


                <tbody>

                    @forelse($events as $index => $event)

                        @php

                            $eventFor = $event->event_for ?? [];

                            if (is_string($eventFor)) {
                                $eventFor = json_decode(
                                    $eventFor,
                                    true
                                ) ?? [];
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


                            $startDate =
                                $event->start_date ??
                                $event->date;

                            $endDate =
                                $event->end_date ??
                                $startDate;

                        @endphp


                        <tr>

                            <!-- Number -->
                            <td class="col-number">
                                <span class="row-number">
                                    {{ $events->firstItem() + $index }}
                                </span>
                            </td>


                            <!-- Actions -->
                            <td>

                                <div class="action-group">

                                    <!-- Edit -->
                                    <a
                                        href="{{ route(
                                            'event.edit',
                                            $event
                                        ) }}"
                                        class="icon-btn edit"
                                        title="Edit"
                                        aria-label="Edit event"
                                    >
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M4 20h4L19 9a2.1 2.1 0 00-4-4L4 16v4z" />
                                            <path d="M13.5 6.5l4 4" />
                                        </svg>
                                    </a>


                                    <!-- Delete -->
                                    <form
                                        action="{{ route(
                                            'event.destroy',
                                            $event
                                        ) }}"
                                        method="POST"
                                        style="display:inline"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="icon-btn delete"
                                            title="Delete"
                                            aria-label="Delete event"
                                            onclick="return confirm('Delete this event?')"
                                        >
                                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M4 7h16M10 11v6M14 11v6" />
                                                <path d="M6 7l1 12a2 2 0 002 2h6a2 2 0 002-2l1-12M9 7V4h6v3" />
                                            </svg>
                                        </button>

                                    </form>

                                </div>

                            </td>


                            <!-- Event -->
                            <td>

                                <div class="holiday-name">

                                    <div class="holiday-avatar">
                                        {{ strtoupper(
                                            substr(
                                                $event->title ?: '-',
                                                0,
                                                1
                                            )
                                        ) }}
                                    </div>

                                    <div>

                                        <strong>
                                            {{ $event->title ?: '-' }}
                                        </strong>

                                        <small>
                                            Event
                                        </small>

                                    </div>

                                </div>

                            </td>


                            <!-- Date -->
                            <td>

                                @if($startDate)

                                    <div class="date-cell">

                                        <strong>
                                            {{ \Carbon\Carbon::parse(
                                                $startDate
                                            )->format('d M Y') }}
                                        </strong>

                                        @if(
                                            $endDate &&
                                            $endDate != $startDate
                                        )

                                            <span>
                                                to
                                                {{ \Carbon\Carbon::parse(
                                                    $endDate
                                                )->format('d M Y') }}
                                            </span>

                                        @endif

                                    </div>

                                @else

                                    <span class="empty-value">
                                        -
                                    </span>

                                @endif

                            </td>


                            <!-- Target -->
                            <td>

                                @if(!empty($eventFor))

                                    <div class="tag-container">

                                        @foreach($eventFor as $target)

                                            <span class="badge target">
                                                {{ $target }}
                                            </span>

                                        @endforeach

                                    </div>

                                @else

                                    <span class="empty-value">
                                        -
                                    </span>

                                @endif

                            </td>


                            <!-- Department -->
                            <td>

                                @if(!empty($departments))

                                    <div class="tag-container">

                                        @foreach($departments as $department)

                                            <span class="badge department">
                                                {{ $department }}
                                            </span>

                                        @endforeach

                                    </div>

                                @else

                                    <span class="empty-value">
                                        -
                                    </span>

                                @endif

                            </td>


                            <!-- Standard -->
                            <td>

                                @if(!empty($event->standards))

                                    <div class="tag-container">

                                        @foreach(
                                            $event->standards
                                            as $standard
                                        )

                                            <span class="badge standard">
                                                {{ $standard }}
                                            </span>

                                        @endforeach

                                    </div>

                                @else

                                    <span class="empty-value">
                                        -
                                    </span>

                                @endif

                            </td>


                            <!-- Division -->
                            <td>

                                @if(!empty($event->divisions))

                                    <div class="tag-container">

                                        @foreach(
                                            $event->divisions
                                            as $division
                                        )

                                            <span class="badge division">
                                                {{ $division }}
                                            </span>

                                        @endforeach

                                    </div>

                                @else

                                    <span class="empty-value">
                                        -
                                    </span>

                                @endif

                            </td>


                            <!-- SMS -->
                            <td>

                                @if($event->send_sms)

                                    <span class="sms active">
                                        <i></i> SMS On
                                    </span>

                                @else

                                    <span class="sms inactive">
                                        <i></i> Off
                                    </span>

                                @endif

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="empty-table"
                            >

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <rect x="3.5" y="5" width="17" height="15" rx="2.5" />
                                            <path d="M3.5 10h17M8 3v4M16 3v4" />
                                        </svg>
                                    </div>

                                    <h3>No events yet</h3>

                                    <p>
                                        Add your first event
                                        to get started.
                                    </p>

                                    <a
                                        href="{{ route(
                                            'event.create'
                                        ) }}"
                                        class="add-btn"
                                    >
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M12 5v14M5 12h14" />
                                        </svg>
                                        Add Event
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- Pagination -->
        <div class="pagination-wrapper">

            @if($events->total() > 0)
                <p class="page-info">
                    Showing
                    <strong>{{ $events->firstItem() }}</strong>
                    to
                    <strong>{{ $events->lastItem() }}</strong>
                    of
                    <strong>{{ $events->total() }}</strong>
                    results
                </p>
            @endif

            @if($events->hasPages())

                @php
                    $currentPage = $events->currentPage();
                    $lastPage = $events->lastPage();
                    $pageWindow = [];

                    for ($p = 1; $p <= $lastPage; $p++) {
                        if (
                            $p == 1 ||
                            $p == $lastPage ||
                            abs($p - $currentPage) <= 1
                        ) {
                            $pageWindow[] = $p;
                        } elseif (end($pageWindow) !== '...') {
                            $pageWindow[] = '...';
                        }
                    }
                @endphp

                <nav class="pager" aria-label="Pagination">

                    <!-- Previous -->
                    @if($events->onFirstPage())
                        <span class="page-link is-disabled" aria-disabled="true" aria-label="Previous page">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M14.5 6l-6 6 6 6" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $events->previousPageUrl() }}" class="page-link" rel="prev" aria-label="Previous page">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M14.5 6l-6 6 6 6" />
                            </svg>
                        </a>
                    @endif

                    <!-- Pages -->
                    @foreach($pageWindow as $page)

                        @if($page === '...')
                            <span class="page-link is-gap">&hellip;</span>
                        @elseif($page == $currentPage)
                            <span class="page-link is-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $events->url($page) }}" class="page-link" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                        @endif

                    @endforeach

                    <!-- Next -->
                    @if($events->hasMorePages())
                        <a href="{{ $events->nextPageUrl() }}" class="page-link" rel="next" aria-label="Next page">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M9.5 6l6 6-6 6" />
                            </svg>
                        </a>
                    @else
                        <span class="page-link is-disabled" aria-disabled="true" aria-label="Next page">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M9.5 6l6 6-6 6" />
                            </svg>
                        </span>
                    @endif

                </nav>

            @endif

        </div>

    </div>

</div>


<style>

/* ================================
   TOKENS
================================ */

.holiday-page {
    --brand: #3b4cca;
    --brand-dark: #2f3ea8;
    --brand-soft: #eef0fd;

    --ink: #151b2d;
    --text: #3a4256;
    --muted: #7a8296;
    --faint: #a9b0bf;

    --line: #e6e9f0;
    --line-soft: #f0f2f7;
    --surface: #ffffff;
    --canvas: #f5f6fa;

    --green: #12804a;
    --green-soft: #e8f6ee;
    --violet: #6a3fd1;
    --violet-soft: #f1ecfc;
    --amber: #b45309;
    --amber-soft: #fdf1e0;
    --red: #c2362f;
    --red-soft: #fdeceb;

    --radius: 12px;
    --radius-sm: 8px;
}

.holiday-page svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}


/* ================================
   PAGE
================================ */

.holiday-page {
    padding: 32px;
    background: var(--canvas);
    min-height: calc(100vh - 60px);
    color: var(--text);
    font-family: "Segoe UI", system-ui, -apple-system, "Helvetica Neue", Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
}


/* ================================
   HEADER
================================ */

.holiday-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    margin-bottom: 28px;
}

.breadcrumb {
    color: var(--muted);
    font-size: 13px;
    margin-bottom: 10px;
}

.breadcrumb span {
    margin: 0 6px;
    color: var(--faint);
}

.holiday-header h1 {
    margin: 0;
    font-size: 28px;
    line-height: 1.2;
    font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--ink);
}

.holiday-header p {
    margin: 8px 0 0;
    color: var(--muted);
    font-size: 14px;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.add-btn,
.export-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 40px;
    padding: 0 18px;
    border-radius: var(--radius-sm);
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s ease, border-color .15s ease, box-shadow .15s ease;
}

.add-btn {
    background: var(--brand);
    color: #fff;
    border: 1px solid var(--brand);
    box-shadow: 0 1px 2px rgba(59, 76, 202, .25);
}

.export-btn {
    background: var(--surface);
    color: var(--ink);
    border: 1px solid var(--line);
}

.add-btn:hover {
    background: var(--brand-dark);
    border-color: var(--brand-dark);
}

.export-btn:hover {
    background: var(--canvas);
    border-color: #d5d9e4;
}

.add-btn:focus-visible,
.export-btn:focus-visible,
.icon-btn:focus-visible,
.search-box:focus-within {
    outline: 2px solid var(--brand);
    outline-offset: 2px;
}


/* ================================
   SUCCESS
================================ */

.success-alert {
    display: flex;
    align-items: center;
    gap: 14px;
    background: var(--green-soft);
    border: 1px solid #c3e6d2;
    border-left: 4px solid var(--green);
    padding: 14px 18px;
    border-radius: var(--radius-sm);
    margin-bottom: 22px;
    color: #14532d;
}

.success-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--green);
    color: #fff;
    display: grid;
    place-items: center;
}

.success-icon svg {
    width: 15px;
    height: 15px;
    stroke-width: 2.4;
}

.success-alert strong {
    font-size: 14px;
    color: #0f3d22;
}

.success-alert p {
    margin: 2px 0 0;
    font-size: 13px;
}


/* ================================
   TABLE CARD
================================ */

.holiday-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(21, 27, 45, .04);
}

.table-header {
    padding: 22px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    border-bottom: 1px solid var(--line);
}

.table-header h2 {
    margin: 0;
    color: var(--ink);
    font-size: 18px;
    font-weight: 700;
    letter-spacing: -0.01em;
}

.table-header p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 13px;
}


/* ================================
   SEARCH
================================ */

.search-box {
    width: 280px;
    height: 40px;
    display: flex;
    align-items: center;
    gap: 9px;
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    padding: 0 13px;
    background: var(--canvas);
    transition: background .15s ease, border-color .15s ease;
}

.search-box:hover {
    border-color: #d5d9e4;
}

.search-box:focus-within {
    background: var(--surface);
    border-color: var(--brand);
    outline: none;
    box-shadow: 0 0 0 3px var(--brand-soft);
}

.search-box svg {
    color: var(--faint);
}

.search-box input {
    border: none;
    outline: none;
    width: 100%;
    font-size: 13px;
    color: var(--ink);
    background: transparent;
}

.search-box input::placeholder {
    color: var(--faint);
}


/* ================================
   TABLE
================================ */

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    min-width: 1200px;
    border-collapse: separate;
    border-spacing: 0;
}

thead th {
    background: #fafbfd;
    color: var(--muted);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .01em;
    padding: 14px 16px;
    border-bottom: 1px solid var(--line);
    text-align: left;
    white-space: nowrap;
}

tbody td {
    padding: 16px;
    border-bottom: 1px solid var(--line-soft);
    color: var(--text);
    font-size: 13px;
    vertical-align: middle;
}

tbody tr:last-child td {
    border-bottom: none;
}

tbody tr {
    transition: background .12s ease;
}

tbody tr:hover {
    background: #fafbff;
}

.col-number {
    width: 56px;
    padding-left: 24px;
}

.row-number {
    color: var(--faint);
    font-size: 12px;
    font-weight: 600;
    font-variant-numeric: tabular-nums;
}


/* ================================
   NAME
================================ */

.holiday-name {
    display: flex;
    align-items: center;
    gap: 12px;
}

.holiday-avatar {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: var(--brand-soft);
    color: var(--brand);
    display: grid;
    place-items: center;
    font-size: 15px;
    font-weight: 700;
}

.holiday-name strong {
    display: block;
    color: var(--ink);
    font-size: 14px;
    font-weight: 600;
}

.holiday-name small {
    display: block;
    color: var(--faint);
    margin-top: 2px;
    font-size: 12px;
}


/* ================================
   DATE
================================ */

.date-cell strong {
    display: block;
    color: var(--ink);
    font-weight: 600;
    white-space: nowrap;
    font-variant-numeric: tabular-nums;
}

.date-cell span {
    display: block;
    margin-top: 2px;
    color: var(--muted);
    font-size: 12px;
    white-space: nowrap;
}


/* ================================
   BADGES
================================ */

.tag-container {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    max-width: 240px;
}

.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    line-height: 1.3;
    white-space: nowrap;
}

.badge.target {
    background: var(--brand-soft);
    color: var(--brand);
}

.badge.department {
    background: var(--violet-soft);
    color: var(--violet);
}

.badge.standard {
    background: var(--green-soft);
    color: var(--green);
}

.badge.division {
    background: var(--amber-soft);
    color: var(--amber);
}

.empty-value {
    color: var(--faint);
}


/* ================================
   ACTIONS
================================ */

.action-group {
    display: flex;
    gap: 6px;
}

.icon-btn {
    width: 34px;
    height: 34px;
    padding: 0;
    border-radius: var(--radius-sm);
    display: grid;
    place-items: center;
    border: 1px solid var(--line);
    background: var(--surface);
    cursor: pointer;
    text-decoration: none;
    transition: background .15s ease, border-color .15s ease, color .15s ease;
}

.icon-btn.edit {
    color: var(--brand);
}

.icon-btn.delete {
    color: var(--red);
}

.icon-btn.edit:hover {
    background: var(--brand-soft);
    border-color: #cfd4f6;
}

.icon-btn.delete:hover {
    background: var(--red-soft);
    border-color: #f6cac7;
}


/* ================================
   SMS
================================ */

.sms {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 5px 11px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.sms i {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: currentColor;
}

.sms.active {
    background: var(--green-soft);
    color: var(--green);
}

.sms.inactive {
    background: #f1f2f6;
    color: var(--muted);
}


/* ================================
   EMPTY
================================ */

.empty-table {
    padding: 64px 24px !important;
    text-align: center;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.empty-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: var(--brand-soft);
    color: var(--brand);
    display: grid;
    place-items: center;
    margin-bottom: 16px;
}

.empty-icon svg {
    width: 26px;
    height: 26px;
}

.empty-state h3 {
    margin: 0;
    color: var(--ink);
    font-size: 16px;
    font-weight: 700;
}

.empty-state p {
    color: var(--muted);
    margin: 6px 0 20px;
    font-size: 13px;
}


/* ================================
   PAGINATION
================================ */

.pagination-wrapper {
    padding: 16px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
    border-top: 1px solid var(--line);
    background: var(--surface);
}

.page-info {
    margin: 0;
    font-size: 13px;
    color: var(--muted);
}

.page-info strong {
    color: var(--ink);
    font-weight: 600;
    font-variant-numeric: tabular-nums;
}

.pager {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-left: auto;
}

.page-link {
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    background: var(--surface);
    color: var(--text);
    font-size: 13px;
    font-weight: 600;
    line-height: 1;
    text-decoration: none;
    font-variant-numeric: tabular-nums;
    transition: background .15s ease, border-color .15s ease, color .15s ease;
}

a.page-link:hover {
    background: var(--brand-soft);
    border-color: #cfd4f6;
    color: var(--brand);
}

a.page-link:focus-visible {
    outline: 2px solid var(--brand);
    outline-offset: 2px;
}

.page-link.is-active {
    background: var(--brand);
    border-color: var(--brand);
    color: #fff;
    box-shadow: 0 1px 2px rgba(59, 76, 202, .3);
}

.page-link.is-disabled {
    background: #fafbfd;
    color: var(--faint);
    cursor: not-allowed;
}

.page-link.is-gap {
    border-color: transparent;
    background: transparent;
    color: var(--faint);
    min-width: 24px;
    padding: 0;
}


/* ================================
   RESPONSIVE
================================ */

@media (max-width: 700px) {

    .holiday-page {
        padding: 18px;
    }

    .holiday-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .header-actions {
        width: 100%;
    }

    .header-actions a {
        flex: 1;
        justify-content: center;
    }

    .table-header {
        flex-direction: column;
        align-items: stretch;
        padding: 18px;
    }

    .search-box {
        width: auto;
    }

    .pagination-wrapper {
        flex-direction: column;
        justify-content: center;
        padding: 14px;
    }

    .pager {
        margin-left: 0;
        flex-wrap: wrap;
        justify-content: center;
    }

}

@media (prefers-reduced-motion: reduce) {

    .holiday-page * {
        transition: none !important;
    }

}

</style>

@endsection
