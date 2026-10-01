<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Barryvdh\DomPDF\Facade\Pdf;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $events = Event::query()
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->orderBy('start_date', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('event.index', compact('events', 'search'));
    }

    public function create()
    {
        return view('event.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',

            'start_date' => 'required|date',

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'event_for_type' => 'required|in:Staff,Student,All',

            'staff_departments' => 'nullable|array',
            'student_departments' => 'nullable|array',
            'standards' => 'nullable|array',
            'divisions' => 'nullable|array',

            'send_sms' => 'nullable|boolean',
        ]);

        $eventFor = $request->event_for_type;

        Event::create([
            'title' => $request->title,

            // Keep old date column for Annual Calendar
            'date' => $request->start_date,

            'start_date' => $request->start_date,

            'end_date' => $request->end_date
                ?: $request->start_date,

            'event_for' => [$eventFor],

            'staff_departments' =>
                in_array($eventFor, ['Staff', 'All'])
                    ? ($request->staff_departments ?? [])
                    : [],

            'student_departments' =>
                in_array($eventFor, ['Student', 'All'])
                    ? ($request->student_departments ?? [])
                    : [],

            'standards' =>
                in_array($eventFor, ['Student', 'All'])
                    ? ($request->standards ?? [])
                    : [],

            'divisions' =>
                in_array($eventFor, ['Student', 'All'])
                    ? ($request->divisions ?? [])
                    : [],

            'send_sms' => $request->boolean('send_sms'),

            'status' => true,
        ]);

        return redirect()
            ->route('event.index')
            ->with('success', 'Event added successfully.');
    }

    public function edit(Event $event)
    {
        return view('event.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',

            'start_date' => 'required|date',

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'event_for_type' => 'required|in:Staff,Student,All',

            'staff_departments' => 'nullable|array',
            'student_departments' => 'nullable|array',
            'standards' => 'nullable|array',
            'divisions' => 'nullable|array',

            'send_sms' => 'nullable|boolean',
        ]);

        $eventFor = $request->event_for_type;

        $event->update([
            'title' => $request->title,

            // Keep old date column updated
            'date' => $request->start_date,

            'start_date' => $request->start_date,

            'end_date' => $request->end_date
                ?: $request->start_date,

            'event_for' => [$eventFor],

            'staff_departments' =>
                in_array($eventFor, ['Staff', 'All'])
                    ? ($request->staff_departments ?? [])
                    : [],

            'student_departments' =>
                in_array($eventFor, ['Student', 'All'])
                    ? ($request->student_departments ?? [])
                    : [],

            'standards' =>
                in_array($eventFor, ['Student', 'All'])
                    ? ($request->standards ?? [])
                    : [],

            'divisions' =>
                in_array($eventFor, ['Student', 'All'])
                    ? ($request->divisions ?? [])
                    : [],

            'send_sms' => $request->boolean('send_sms'),
        ]);

        return redirect()
            ->route('event.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()
            ->route('event.index')
            ->with('success', 'Event deleted successfully.');
    }

    public function export()
    {
        return view('event.export');
    }

    public function exportExcel(Request $request)
    {
        $events = Event::orderBy('start_date', 'asc')->get();

        $columns = $request->input('columns', [
            'title',
            'date',
            'target',
            'department',
            'standard',
            'division',
            'sms',
        ]);

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        foreach ($columns as $index => $column) {

            $header = match ($column) {

                'title' => 'Event Name',

                'date' => 'Date',

                'target' => 'Target',

                'department' => 'Department',

                'standard' => 'Standard',

                'division' => 'Division',

                'sms' => 'SMS',

                default => $column,
            };

            $excelColumn =
                Coordinate::stringFromColumnIndex($index + 1);

            $sheet->setCellValue(
                $excelColumn . '1',
                $header
            );
        }

        $row = 2;

        foreach ($events as $event) {

            $eventFor = $event->event_for ?? [];

            if (is_string($eventFor)) {
                $eventFor =
                    json_decode($eventFor, true) ?? [];
            }

            // Fallback for old records
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

            foreach ($columns as $index => $column) {

                $value = match ($column) {

                    'title' =>
                        $event->title ?: '-',

                    'date' =>
                        $event->start_date
                            ?? $event->date
                            ?? '-',

                    'target' =>
                        !empty($eventFor)
                            ? implode(', ', $eventFor)
                            : '-',

                    'department' =>
                        !empty($departments)
                            ? implode(', ', $departments)
                            : '-',

                    'standard' =>
                        !empty($event->standards)
                            ? implode(', ', $event->standards)
                            : '-',

                    'division' =>
                        !empty($event->divisions)
                            ? implode(', ', $event->divisions)
                            : '-',

                    'sms' =>
                        $event->send_sms
                            ? 'Yes'
                            : 'No',

                    default => '-',
                };

                $excelColumn =
                    Coordinate::stringFromColumnIndex($index + 1);

                $sheet->setCellValue(
                    $excelColumn . $row,
                    $value
                );
            }

            $row++;
        }

        foreach (
            range(1, count($columns))
            as $column
        ) {

            $excelColumn =
                Coordinate::stringFromColumnIndex($column);

            $sheet
                ->getColumnDimension($excelColumn)
                ->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            'events.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $events = Event::orderBy('start_date', 'asc')->get();

        $columns = $request->input('columns', [
            'title',
            'date',
            'target',
            'department',
            'standard',
            'division',
            'sms',
        ]);

        return Pdf::loadView(
            'event.pdf',
            compact('events', 'columns')
        )
            ->setPaper('a4', 'landscape')
            ->download('events.pdf');
    }
}