@extends('layouts.academic')

@section('content')

<div class="page-container">

    <div class="page-header">
        <div>
            <div class="breadcrumb">
                Academic Calendar <span>/</span> Holiday <span>/</span> Export
            </div>

            <h1>Export Holidays</h1>
            <p>Choose the columns to include, then pick a file format.</p>
        </div>

        <a href="{{ route('holiday.index') }}" class="back-btn">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M19 12H5m0 0l6-6m-6 6l6 6" />
            </svg>
            Back
        </a>
    </div>

    <div class="export-box">

        <div class="export-section">

            <div class="export-head">
                <h3>Select Columns to Export</h3>
                <p>Only the checked columns appear in the file.</p>
            </div>

            <label class="col-chip all">
                <input type="checkbox" id="selectAll" checked>
                <span>Select All</span>
            </label>

            <div class="columns">

                <label class="col-chip">
                    <input type="checkbox" name="columns[]" value="title" checked>
                    <span>Holiday Name</span>
                </label>

                <label class="col-chip">
                    <input type="checkbox" name="columns[]" value="date" checked>
                    <span>Date</span>
                </label>

                <label class="col-chip">
                    <input type="checkbox" name="columns[]" value="target" checked>
                    <span>Target</span>
                </label>

                <label class="col-chip">
                    <input type="checkbox" name="columns[]" value="department" checked>
                    <span>Department</span>
                </label>

                <label class="col-chip">
                    <input type="checkbox" name="columns[]" value="standard" checked>
                    <span>Standard</span>
                </label>

                <label class="col-chip">
                    <input type="checkbox" name="columns[]" value="division" checked>
                    <span>Division</span>
                </label>

                <label class="col-chip">
                    <input type="checkbox" name="columns[]" value="sms" checked>
                    <span>SMS</span>
                </label>

            </div>

        </div>

        <div class="export-buttons">

            <button type="button" class="btn-excel" onclick="exportFile('excel')">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="4" y="4" width="16" height="16" rx="2.5" />
                    <path d="M4 10h16M4 15h16M10 4v16" />
                </svg>
                Export as Excel
            </button>

            <button type="button" class="btn-pdf" onclick="exportFile('pdf')">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M14 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V8l-5-5z" />
                    <path d="M14 3v5h5M9 13h6M9 17h4" />
                </svg>
                Export as PDF
            </button>

        </div>

    </div>

</div>

<script>

function exportFile(type) {

    let selectedColumns = [];

    document.querySelectorAll('input[name="columns[]"]:checked')
        .forEach(function (checkbox) {

            selectedColumns.push(checkbox.value);

        });

    if (selectedColumns.length === 0) {

        alert('Please select at least one column.');

        return;
    }

    let url = type === 'excel'
        ? "{{ route('holiday.export.excel') }}"
        : "{{ route('holiday.export.pdf') }}";

    url += '?' + selectedColumns
        .map(column => 'columns[]=' + encodeURIComponent(column))
        .join('&');

    window.location.href = url;
}


document.getElementById('selectAll').addEventListener('change', function () {

    document.querySelectorAll('input[name="columns[]"]')
        .forEach(function (checkbox) {

            checkbox.checked = this.checked;

        }, this);

});


document.querySelectorAll('input[name="columns[]"]')
    .forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            let all = document.querySelectorAll(
                'input[name="columns[]"]'
            );

            let checked = document.querySelectorAll(
                'input[name="columns[]"]:checked'
            );

            document.getElementById('selectAll').checked =
                all.length === checked.length;

        });

    });

</script>

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
    --surface: #ffffff;
    --canvas: #f5f6fa;

    --green: #12804a;
    --green-dark: #0e6a3d;
    --red: #c2362f;
    --red-dark: #a62d27;

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


/* EXPORT CARD */

.export-box {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    box-shadow: 0 1px 2px rgba(21, 27, 45, .04);
}

.export-section {
    padding: 26px 30px;
}

.export-head {
    margin-bottom: 20px;
}

.export-head h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: -0.01em;
    color: var(--ink);
}

.export-head p {
    margin: 4px 0 0;
    font-size: 13px;
    color: var(--muted);
}

.columns {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 16px;
    padding-top: 18px;
    border-top: 1px solid var(--line);
}


/* COLUMN CHIPS */

.col-chip {
    position: relative;
    display: inline-flex;
    cursor: pointer;
}

.col-chip input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    margin: 0;
    cursor: pointer;
}

.col-chip span {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    height: 40px;
    padding: 0 16px 0 12px;
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    background: var(--surface);
    color: var(--text);
    font-size: 14px;
    font-weight: 600;
    transition: background .15s ease, border-color .15s ease, color .15s ease;
}

.col-chip span::before {
    content: "";
    width: 18px;
    height: 18px;
    box-sizing: border-box;
    border: 1.5px solid #c5cad8;
    border-radius: 5px;
    background-color: var(--surface);
    background-repeat: no-repeat;
    background-position: center;
    background-size: 12px;
    transition: background-color .15s ease, border-color .15s ease;
}

.col-chip:hover span {
    border-color: #d5d9e4;
    background: var(--canvas);
}

.col-chip input:checked + span {
    background: var(--brand-soft);
    border-color: var(--brand-line);
    color: var(--brand);
}

.col-chip input:checked + span::before {
    background-color: var(--brand);
    border-color: var(--brand);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3.4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M5 12.5l4.5 4.5L19 7.5'/%3E%3C/svg%3E");
}

.col-chip input:focus-visible + span {
    outline: 2px solid var(--brand);
    outline-offset: 2px;
}

.col-chip.all span {
    color: var(--ink);
}

.col-chip.all input:checked + span {
    color: var(--brand);
}


/* BUTTONS */

.export-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px 30px;
    background: #fafbfd;
    border-top: 1px solid var(--line);
    border-radius: 0 0 var(--radius) var(--radius);
}

.export-buttons button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 40px;
    padding: 0 20px;
    border: none;
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    cursor: pointer;
    transition: background .15s ease;
}

.export-buttons .btn-excel {
    background: var(--green);
}

.export-buttons .btn-excel:hover {
    background: var(--green-dark);
}

.export-buttons .btn-pdf {
    background: var(--red);
}

.export-buttons .btn-pdf:hover {
    background: var(--red-dark);
}

.back-btn:focus-visible,
.export-buttons button:focus-visible {
    outline: 2px solid var(--brand);
    outline-offset: 2px;
}


@media (max-width: 700px) {

    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .export-section {
        padding: 22px 18px;
    }

    .export-buttons {
        flex-direction: column;
        padding: 18px;
    }

    .export-buttons button {
        justify-content: center;
    }

}

@media (prefers-reduced-motion: reduce) {

    .page-container * {
        transition: none !important;
    }

}

</style>

@endsection