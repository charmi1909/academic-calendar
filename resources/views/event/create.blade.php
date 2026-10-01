@extends('layouts.academic')

@section('title', 'Add Event')

@section('content')

<div class="page-container">

    <div class="page-header">
        <div>
            <div class="breadcrumb">
                Academic Calendar <span>/</span> Events <span>/</span> Add
            </div>

            <h1>Add Event</h1>
            <p>Set the dates, choose who it applies to and decide whether to send an SMS.</p>
        </div>

        <a href="{{ route('event.index') }}" class="back-btn">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M19 12H5m0 0l6-6m-6 6l6 6" />
            </svg>
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="error-box" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="form-card">

        <form
            action="{{ route('event.store') }}"
            method="POST"
        >

            @csrf

            <!-- Event details -->

            <div class="form-section">

                <div class="section-head">
                    <h2>Event details</h2>
                    <p>Name the event and set when it starts and ends.</p>
                </div>


                <!-- Event Name -->

                <div class="form-group">

                    <label>
                        Event Name <span>*</span>
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Enter event name"
                        required
                    >

                </div>


                <!-- Start Date -->

                <div class="form-group">

                    <label>
                        Start Date <span>*</span>
                    </label>

                    <input
                        type="date"
                        name="start_date"
                        value="{{ old('start_date') }}"
                        required
                    >

                </div>


                <!-- End Date -->

                <div class="form-group">

                    <label>
                        End Date
                    </label>

                    <input
                        type="date"
                        name="end_date"
                        value="{{ old('end_date') }}"
                    >

                    <p class="field-hint">
                        Leave empty for a single day event.
                    </p>

                </div>

            </div>


            <!-- Audience -->

            <div class="form-section">

                <div class="section-head">
                    <h2>Audience</h2>
                    <p>Choose who this event applies to.</p>
                </div>


                <!-- Event For -->

                <div class="form-group">

                    <label>
                        Event For <span>*</span>
                    </label>

                    <select
                        name="event_for_type"
                        id="eventFor"
                        required
                    >

                        <option value="">
                            Select Event For
                        </option>

                    <option value="Staff">
                        Staff
                    </option>

                    <option value="Student">
                        Student
                    </option>

                    <option value="All">
                        All
                    </option>

                    </select>

                </div>


                <!-- STAFF -->

                <div
                    id="staffSection"
                    class="conditional-section"
                >

                    <div class="section-title">
                        Staff Details
                    </div>


                    <div class="form-group">

                        <label>
                            Department (Staff)
                        </label>

                    <div
                        class="multi-select"
                        data-name="staff_departments[]"
                        data-options='["Teaching","Non Teaching","Administration","Account","Transport"]'
                    >

                        <div class="selected-items"></div>

                        <div class="select-input">
                            <span>Select Department</span>
                            <svg class="chev" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6" /></svg>
                        </div>

                        <div class="options"></div>

                    </div>

                    </div>

                </div>


                <!-- STUDENT -->

                <div
                    id="studentSection"
                    class="conditional-section"
                >

                    <div class="section-title">
                        Student Details
                    </div>


                    <!-- Student Department -->

                    <div class="form-group">

                        <label>
                            Department (Student)
                        </label>

                    <div
                        class="multi-select"
                        data-name="student_departments[]"
                        data-options='["Science","Commerce","Arts","General"]'
                    >

                        <div class="selected-items"></div>

                        <div class="select-input">
                            <span>Select Department</span>
                            <svg class="chev" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6" /></svg>
                        </div>

                        <div class="options"></div>

                    </div>

                    </div>


                    <!-- Standard -->

                    <div class="form-group">

                        <label>
                            Standard
                        </label>

                    <div
                        class="multi-select"
                        data-name="standards[]"
                        data-options='["1","2","3","4","5","6","7","8","9","10","11","12"]'
                    >

                        <div class="selected-items"></div>

                        <div class="select-input">
                            <span>Select Standard</span>
                            <svg class="chev" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6" /></svg>
                        </div>

                        <div class="options"></div>

                    </div>

                    </div>


                    <!-- Division -->

                    <div class="form-group">

                        <label>
                            Division
                        </label>

                    <div
                        class="multi-select"
                        data-name="divisions[]"
                        data-options='["A","B","C","D"]'
                    >

                        <div class="selected-items"></div>

                        <div class="select-input">
                            <span>Select Division</span>
                            <svg class="chev" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6" /></svg>
                        </div>

                        <div class="options"></div>

                    </div>

                    </div>

                </div>

            </div>


            <!-- Notifications -->

            <div class="form-section">

                <div class="section-head">
                    <h2>Notifications</h2>
                    <p>Control how people are told about this event.</p>
                </div>


                <!-- SMS -->

                <div class="toggle-row">

                    <div class="toggle-text">
                        <strong>Send SMS Notification</strong>
                        <p>Notify the selected audience by SMS.</p>
                    </div>

                    <label class="switch">

                        <input
                            type="checkbox"
                            name="send_sms"
                            value="1"
                        >

                        <span class="slider"></span>

                    </label>

                </div>

            </div>


            <!-- BUTTONS -->

            <div class="form-actions">

                <a
                    href="{{ route('event.index') }}"
                    class="cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="save-btn"
                >
                    Save Event
                </button>

            </div>

        </form>

    </div>

</div>

@endsection


@push('styles')

<style>

.page-container {
    --brand: #3b4cca;
    --brand-dark: #2f3ea8;
    --brand-soft: #eef0fd;
    --brand-line: #cfd4f6;

    --ink: #151b2d;
    --text: #3a4256;
    --muted: #7a8296;
    --faint: #a9b0bf;

    --line: #e6e9f0;
    --line-soft: #f0f2f7;
    --surface: #ffffff;
    --canvas: #f5f6fa;

    --red: #c2362f;
    --red-soft: #fdeceb;

    --radius: 12px;
    --radius-sm: 8px;

    max-width: 820px;
    margin: 0 auto;
    padding: 8px 0 40px;
    color: var(--text);
    font-family: "Segoe UI", system-ui, -apple-system, "Helvetica Neue", Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
}

.page-container svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}


/* HEADER */

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    margin-bottom: 26px;
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

.page-header h1 {
    margin: 0;
    font-size: 28px;
    line-height: 1.2;
    font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--ink);
}

.page-header p {
    margin: 8px 0 0;
    color: var(--muted);
    font-size: 14px;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 40px;
    padding: 0 16px;
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    background: var(--surface);
    color: var(--ink);
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: background .15s ease, border-color .15s ease;
}

.back-btn:hover {
    background: var(--canvas);
    border-color: #d5d9e4;
}


/* ERROR */

.error-box {
    background: var(--red-soft);
    border: 1px solid #f6cac7;
    border-left: 4px solid var(--red);
    color: #8f2420;
    padding: 14px 18px;
    border-radius: var(--radius-sm);
    margin-bottom: 20px;
    font-size: 13px;
    line-height: 1.6;
}


/* FORM CARD */

.form-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    box-shadow: 0 1px 2px rgba(21, 27, 45, .04);
}

.form-section {
    padding: 26px 30px;
    border-bottom: 1px solid var(--line);
}

.section-head {
    margin-bottom: 20px;
}

.section-head h2 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: -0.01em;
    color: var(--ink);
}

.section-head p {
    margin: 4px 0 0;
    font-size: 13px;
    color: var(--muted);
}

.form-group {
    margin-bottom: 20px;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-group > label {
    display: block;
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--ink);
}

.form-group > label span {
    color: var(--red);
}

.field-hint {
    margin: 6px 0 0;
    font-size: 12px;
    color: var(--muted);
}

.form-group input[type="text"],
.form-group input[type="date"],
.form-group > select {
    width: 100%;
    height: 42px;
    box-sizing: border-box;
    padding: 0 14px;
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    background: var(--surface);
    color: var(--ink);
    font-family: inherit;
    font-size: 14px;
    transition: border-color .15s ease, box-shadow .15s ease;
}

.form-group input::placeholder {
    color: var(--faint);
}

.form-group > select {
    appearance: none;
    -webkit-appearance: none;
    padding-right: 40px;
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%237a8296' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 16px;
}

.form-group input[type="text"]:hover,
.form-group input[type="date"]:hover,
.form-group > select:hover {
    border-color: #d5d9e4;
}

.form-group input[type="text"]:focus,
.form-group input[type="date"]:focus,
.form-group > select:focus {
    outline: none;
    border-color: var(--brand);
    box-shadow: 0 0 0 3px var(--brand-soft);
}


/* CONDITIONAL SECTIONS */

.conditional-section {
    display: none;
    margin-top: 20px;
    padding: 20px;
    background: #fafbfd;
    border: 1px solid var(--line);
    border-radius: var(--radius);
}

.conditional-section + .conditional-section {
    margin-top: 14px;
}

.section-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--line);
}


/* MULTI SELECT */

.multi-select {
    position: relative;
    width: 100%;
}

.select-input {
    min-height: 42px;
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    background: var(--surface);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 14px;
    box-sizing: border-box;
    cursor: pointer;
    color: var(--faint);
    font-size: 14px;
    transition: border-color .15s ease, box-shadow .15s ease;
}

.select-input:hover {
    border-color: #d5d9e4;
}

.multi-select.open .select-input {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px var(--brand-soft);
}

.select-input .chev {
    color: var(--muted);
    transition: transform .15s ease;
}

.multi-select.open .chev {
    transform: rotate(180deg);
}

.options {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    width: 100%;
    box-sizing: border-box;
    padding: 6px;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    box-shadow: 0 12px 28px rgba(21, 27, 45, .12);
    z-index: 100;
    max-height: 220px;
    overflow-y: auto;
}

.multi-select.open .options {
    display: block;
}

.option {
    padding: 9px 12px;
    border-radius: 6px;
    font-size: 14px;
    color: var(--text);
    cursor: pointer;
}

.option:hover {
    background: var(--brand-soft);
    color: var(--brand);
}


/* SELECTED TAGS */

.selected-items {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 8px;
}

.selected-items:empty {
    display: none;
}

.selected-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 6px 4px 11px;
    background: var(--brand-soft);
    border: 1px solid var(--brand-line);
    border-radius: 999px;
    color: var(--brand);
    font-size: 13px;
    font-weight: 600;
}

.remove-tag {
    width: 20px;
    height: 20px;
    display: grid;
    place-items: center;
    border: none;
    border-radius: 50%;
    background: transparent;
    color: var(--brand);
    font-size: 16px;
    line-height: 1;
    cursor: pointer;
    padding: 0;
    transition: background .15s ease;
}

.remove-tag:hover {
    background: var(--brand-line);
}


/* SMS */

.toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 16px 18px;
    background: #fafbfd;
    border: 1px solid var(--line);
    border-radius: var(--radius);
}

.toggle-text strong {
    display: block;
    font-size: 14px;
    color: var(--ink);
}

.toggle-text p {
    margin: 3px 0 0;
    font-size: 12px;
    color: var(--muted);
}

.switch {
    position: relative;
    display: inline-block;
    flex-shrink: 0;
    width: 46px;
    height: 26px;
}

.switch input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    margin: 0;
    cursor: pointer;
    z-index: 1;
}

.slider {
    position: absolute;
    inset: 0;
    background: #d5d9e4;
    border-radius: 999px;
    transition: background .2s ease;
}

.slider:before {
    content: "";
    position: absolute;
    width: 20px;
    height: 20px;
    left: 3px;
    top: 3px;
    background: white;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(21, 27, 45, .25);
    transition: transform .2s ease;
}

.switch input:checked + .slider {
    background: var(--brand);
}

.switch input:checked + .slider:before {
    transform: translateX(20px);
}

.switch input:focus-visible + .slider {
    outline: 2px solid var(--brand);
    outline-offset: 2px;
}


/* BUTTONS */

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px 30px;
    background: #fafbfd;
    border-radius: 0 0 var(--radius) var(--radius);
}

.cancel-btn,
.save-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 40px;
    padding: 0 20px;
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: background .15s ease, border-color .15s ease;
}

.cancel-btn {
    border: 1px solid var(--line);
    background: var(--surface);
    color: var(--ink);
}

.cancel-btn:hover {
    background: var(--canvas);
    border-color: #d5d9e4;
}

.save-btn {
    border: 1px solid var(--brand);
    background: var(--brand);
    color: #fff;
    box-shadow: 0 1px 2px rgba(59, 76, 202, .25);
}

.save-btn:hover {
    background: var(--brand-dark);
    border-color: var(--brand-dark);
}

.back-btn:focus-visible,
.cancel-btn:focus-visible,
.save-btn:focus-visible {
    outline: 2px solid var(--brand);
    outline-offset: 2px;
}


@media (max-width: 700px) {

    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .form-section {
        padding: 22px 18px;
    }

    .form-actions {
        padding: 18px;
    }

    .form-actions a,
    .form-actions button {
        flex: 1;
    }

    .toggle-row {
        align-items: flex-start;
    }

}

@media (prefers-reduced-motion: reduce) {

    .page-container * {
        transition: none !important;
    }

}

</style>

@endpush


@push('scripts')

<script>

/* =========================================
   EVENT FOR
========================================= */

const eventFor = document.getElementById('eventFor');

const staffSection = document.getElementById('staffSection');

const studentSection = document.getElementById('studentSection');


function updateEventSections() {

    staffSection.style.display = 'none';

    studentSection.style.display = 'none';


    if (eventFor.value === 'Staff') {

        staffSection.style.display = 'block';

    }

    if (eventFor.value === 'Student') {

        studentSection.style.display = 'block';

    }

    if (eventFor.value === 'All') {

        staffSection.style.display = 'block';

        studentSection.style.display = 'block';

    }

}


eventFor.addEventListener(
    'change',
    updateEventSections
);


/* =========================================
   MULTI SELECT
========================================= */

document.querySelectorAll('.multi-select').forEach(function (multiSelect) {

    const selectInput =
        multiSelect.querySelector('.select-input');

    const optionsContainer =
        multiSelect.querySelector('.options');

    const selectedContainer =
        multiSelect.querySelector('.selected-items');

    const fieldName =
        multiSelect.dataset.name;

    const options =
        JSON.parse(multiSelect.dataset.options);

    let selectedValues =
        JSON.parse(
            multiSelect.dataset.selected || '[]'
        );


    /* SELECT */

    function selectOption(value) {

        if (!selectedValues.includes(value)) {

            selectedValues.push(value);

        }

        renderSelected();

        renderOptions();

    }


    /* REMOVE */

    function removeOption(value) {

        selectedValues =
            selectedValues.filter(function (item) {

                return item !== value;

            });

        renderSelected();

        renderOptions();

    }


    /* SELECTED TAGS */

    function renderSelected() {

        selectedContainer.innerHTML = '';

        selectedValues.forEach(function (value) {

            const tag =
                document.createElement('div');

            tag.className = 'selected-tag';

            tag.innerHTML = `
                ${value}

                <button
                    type="button"
                    class="remove-tag"
                    aria-label="Remove ${value}"
                >
                    ×
                </button>

                <input
                    type="hidden"
                    name="${fieldName}"
                    value="${value}"
                >
            `;

            tag
                .querySelector('.remove-tag')
                .addEventListener('click', function (event) {

                    event.stopPropagation();

                    removeOption(value);

                });

            selectedContainer.appendChild(tag);

        });

    }


    /* OPTIONS */

    function renderOptions() {

        optionsContainer.innerHTML = '';

        options.forEach(function (value) {

            if (!selectedValues.includes(value)) {

                const option =
                    document.createElement('div');

                option.className = 'option';

                option.textContent = value;

                option.addEventListener(
                    'click',
                    function (event) {

                        event.stopPropagation();

                        selectOption(value);

                    }
                );

                optionsContainer.appendChild(option);

            }

        });


        if (optionsContainer.children.length === 0) {

            const empty =
                document.createElement('div');

            empty.className = 'option';

            empty.textContent =
                'All options selected';

            empty.style.color =
                '#9ca3af';

            optionsContainer.appendChild(empty);

        }

    }


    /* OPEN / CLOSE */

    selectInput.addEventListener('click', function (event) {

        event.stopPropagation();

        document
            .querySelectorAll('.multi-select')
            .forEach(function (other) {

                if (other !== multiSelect) {

                    other.classList.remove('open');

                }

            });

        multiSelect.classList.toggle('open');

        renderOptions();

    });


    renderSelected();

    renderOptions();

});


/* CLOSE OUTSIDE */

document.addEventListener('click', function () {

    document
        .querySelectorAll('.multi-select')
        .forEach(function (multiSelect) {

            multiSelect.classList.remove('open');

        });

});


/* SHOW CORRECT SECTIONS ON PAGE LOAD */

updateEventSections();

</script>

@endpush