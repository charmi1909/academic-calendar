<!DOCTYPE html>
<html>
<head>
    <title>Add Annual Calendar</title>
</head>
<body>

    <h1>Add Annual Calendar</h1>

    <form action="{{ route('annual_calendar.store') }}" method="POST">

        @csrf

        <div>
            <label>Academic Year</label>
            <input
                type="text"
                name="academic_year"
                placeholder="2026-2027"
            >
        </div>

        <br>

        <div>
            <label>Start Date</label>
            <input type="date" name="start_date">
        </div>

        <br>

        <div>
            <label>End Date</label>
            <input type="date" name="end_date">
        </div>

        <br>

        <div>
            <label>Description</label>
            <br>
            <textarea name="description"></textarea>
        </div>

        <br>

        <button type="submit">Save</button>

    </form>

    <br>

    <a href="{{ route('annual_calendar.index') }}">
        Back
    </a>

</body>
</html>