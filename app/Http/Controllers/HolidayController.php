<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Barryvdh\DomPDF\Facade\Pdf;

class HolidayController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Holiday List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->input('search');

        $holidays = Holiday::query()
            ->when($search, function ($query) use ($search) {
                $query->where(
                    'title',
                    'like',
                    '%' . $search . '%'
                );
            })
            ->orderBy('start_date', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view(
            'holiday.index',
            compact('holidays', 'search')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('holiday.create');
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',

            'start_date' => 'required|date',

            'end_date' =>
                'nullable|date|after_or_equal:start_date',

            'holiday_for' => 'nullable|array',

            'staff_departments' => 'nullable|array',

            'student_departments' => 'nullable|array',

            'standards' => 'nullable|array',

            'divisions' => 'nullable|array',

            'send_sms' => 'nullable|boolean',
        ]);


        Holiday::create([

            'title' => $request->title,

            // Keep old date column for Annual Calendar
            'date' => $request->start_date,

            'start_date' => $request->start_date,

            'end_date' =>
                $request->end_date
                ?: $request->start_date,

            'holiday_for' =>
                $request->holiday_for ?? [],

            'staff_departments' =>
                $request->staff_departments ?? [],

            'student_departments' =>
                $request->student_departments ?? [],

            'standards' =>
                $request->standards ?? [],

            'divisions' =>
                $request->divisions ?? [],

            'send_sms' =>
                $request->boolean('send_sms'),
        ]);


        return redirect()
            ->route('holiday.index')
            ->with(
                'success',
                'Holiday added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(Holiday $holiday)
    {
        return view(
            'holiday.edit',
            compact('holiday')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Holiday $holiday
    ) {
        $request->validate([
            'title' => 'required|string|max:255',

            'start_date' => 'required|date',

            'end_date' =>
                'nullable|date|after_or_equal:start_date',

            'holiday_for' => 'nullable|array',

            'staff_departments' => 'nullable|array',

            'student_departments' => 'nullable|array',

            'standards' => 'nullable|array',

            'divisions' => 'nullable|array',

            'send_sms' => 'nullable|boolean',
        ]);


        $holiday->update([

            'title' => $request->title,

            // Keep old date column synchronized
            'date' => $request->start_date,

            'start_date' => $request->start_date,

            'end_date' =>
                $request->end_date
                ?: $request->start_date,

            'holiday_for' =>
                $request->holiday_for ?? [],

            'staff_departments' =>
                $request->staff_departments ?? [],

            'student_departments' =>
                $request->student_departments ?? [],

            'standards' =>
                $request->standards ?? [],

            'divisions' =>
                $request->divisions ?? [],

            'send_sms' =>
                $request->boolean('send_sms'),
        ]);


        return redirect()
            ->route('holiday.index')
            ->with(
                'success',
                'Holiday updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()
            ->route('holiday.index')
            ->with(
                'success',
                'Holiday deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Export Page
    |--------------------------------------------------------------------------
    */

    public function export()
    {
        return view('holiday.export');
    }


    /*
    |--------------------------------------------------------------------------
    | Export Excel
    |--------------------------------------------------------------------------
    */

    public function exportExcel(Request $request)
    {
        $holidays = Holiday::orderBy(
            'start_date',
            'asc'
        )->get();


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


        /*
        |--------------------------------------------------------------------------
        | Headers
        |--------------------------------------------------------------------------
        */

        foreach ($columns as $index => $column) {

            $header = match ($column) {

                'title' =>
                    'Holiday Name',

                'date' =>
                    'Date',

                'target' =>
                    'Target',

                'department' =>
                    'Department',

                'standard' =>
                    'Standard',

                'division' =>
                    'Division',

                'sms' =>
                    'SMS',

                default =>
                    $column,
            };


            $excelColumn =
                Coordinate::stringFromColumnIndex(
                    $index + 1
                );


            $sheet->setCellValue(
                $excelColumn . '1',
                $header
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Data
        |--------------------------------------------------------------------------
        */

        $row = 2;


        foreach ($holidays as $holiday) {

            /*
            |--------------------------------------------------------------------------
            | Holiday Target
            |--------------------------------------------------------------------------
            */

            $holidayFor =
                $holiday->holiday_for ?? [];


            if (is_string($holidayFor)) {

                $holidayFor =
                    json_decode(
                        $holidayFor,
                        true
                    ) ?? [];
            }


            /*
            |--------------------------------------------------------------------------
            | Target fallback for old records
            |--------------------------------------------------------------------------
            */

            if (empty($holidayFor)) {

                $hasStaff =
                    !empty(
                        $holiday->staff_departments
                    );


                $hasStudent =
                    !empty(
                        $holiday->student_departments
                    )
                    ||
                    !empty(
                        $holiday->standards
                    )
                    ||
                    !empty(
                        $holiday->divisions
                    );


                if (
                    $hasStaff &&
                    $hasStudent
                ) {

                    $holidayFor = ['All'];

                } elseif ($hasStaff) {

                    $holidayFor = ['Staff'];

                } elseif ($hasStudent) {

                    $holidayFor = ['Student'];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Departments
            |--------------------------------------------------------------------------
            */

            $departments = array_merge(

                $holiday->staff_departments ?? [],

                $holiday->student_departments ?? []
            );


            /*
            |--------------------------------------------------------------------------
            | Column Data
            |--------------------------------------------------------------------------
            */

            foreach ($columns as $index => $column) {

                $value = match ($column) {

                    'title' =>
                        $holiday->title
                        ?: '-',

                    'date' =>
                        $holiday->start_date
                        ?? $holiday->date
                        ?? '-',

                    'target' =>
                        !empty($holidayFor)
                        ? implode(
                            ', ',
                            $holidayFor
                        )
                        : '-',

                    'department' =>
                        !empty($departments)
                        ? implode(
                            ', ',
                            $departments
                        )
                        : '-',

                    'standard' =>
                        !empty($holiday->standards)
                        ? implode(
                            ', ',
                            $holiday->standards
                        )
                        : '-',

                    'division' =>
                        !empty($holiday->divisions)
                        ? implode(
                            ', ',
                            $holiday->divisions
                        )
                        : '-',

                    'sms' =>
                        $holiday->send_sms
                        ? 'Yes'
                        : 'No',

                    default =>
                        '-',
                };


                $excelColumn =
                    Coordinate::stringFromColumnIndex(
                        $index + 1
                    );


                $sheet->setCellValue(
                    $excelColumn . $row,
                    $value
                );
            }


            $row++;
        }


        /*
        |--------------------------------------------------------------------------
        | Auto Size Columns
        |--------------------------------------------------------------------------
        */

        foreach (
            range(1, count($columns))
            as $column
        ) {

            $excelColumn =
                Coordinate::stringFromColumnIndex(
                    $column
                );


            $sheet
                ->getColumnDimension($excelColumn)
                ->setAutoSize(true);
        }


        /*
        |--------------------------------------------------------------------------
        | Download Excel
        |--------------------------------------------------------------------------
        */

        $writer = new Xlsx($spreadsheet);


        return response()->streamDownload(
            function () use ($writer) {

                $writer->save(
                    'php://output'
                );

            },
            'holidays.xlsx'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Export PDF
    |--------------------------------------------------------------------------
    */

    public function exportPdf(Request $request)
    {
        $holidays = Holiday::orderBy(
            'start_date',
            'asc'
        )->get();


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
            'holiday.pdf',
            compact(
                'holidays',
                'columns'
            )
        )
        ->setPaper(
            'a4',
            'landscape'
        )
        ->download(
            'holidays.pdf'
        );
    }
}