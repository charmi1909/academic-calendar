<?php

namespace App\Http\Controllers;

use App\Models\Ptm;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Barryvdh\DomPDF\Facade\Pdf;

class PtmController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $ptms = Ptm::query()
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->orderBy('start_date', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('ptm.index', compact('ptms', 'search'));
    }


    public function create()
    {
        return view('ptm.create');
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
            'ptm_for_type' => 'required|in:Staff,Student,All',
            'staff_departments' => 'nullable|array',
            'student_departments' => 'nullable|array',
            'standards' => 'nullable|array',
            'divisions' => 'nullable|array',
            'send_sms' => 'nullable|boolean',
        ]);

        $ptmFor = $request->ptm_for_type;

        Ptm::create([
            'title' => $request->title,

            // Keep date for Annual Calendar
            'date' => $request->start_date,

            'start_date' => $request->start_date,

            'end_date' => $request->end_date
                ?: $request->start_date,

            'ptm_for' => [$ptmFor],

            'staff_departments' =>
                in_array($ptmFor, ['Staff', 'All'])
                    ? ($request->staff_departments ?? [])
                    : [],

            'student_departments' =>
                in_array($ptmFor, ['Student', 'All'])
                    ? ($request->student_departments ?? [])
                    : [],

            'standards' =>
                in_array($ptmFor, ['Student', 'All'])
                    ? ($request->standards ?? [])
                    : [],

            'divisions' =>
                in_array($ptmFor, ['Student', 'All'])
                    ? ($request->divisions ?? [])
                    : [],

            'send_sms' => $request->boolean('send_sms'),

            'status' => true,
        ]);

        return redirect()
            ->route('ptm.index')
            ->with('success', 'PTM added successfully.');
    }


    public function edit(Ptm $ptm)
    {
        return view('ptm.edit', compact('ptm'));
    }


    public function update(Request $request, Ptm $ptm)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
            'ptm_for_type' => 'required|in:Staff,Student,All',
            'staff_departments' => 'nullable|array',
            'student_departments' => 'nullable|array',
            'standards' => 'nullable|array',
            'divisions' => 'nullable|array',
            'send_sms' => 'nullable|boolean',
        ]);

        $ptmFor = $request->ptm_for_type;

        $ptm->update([
            'title' => $request->title,

            // Keep date for Annual Calendar
            'date' => $request->start_date,

            'start_date' => $request->start_date,

            'end_date' => $request->end_date
                ?: $request->start_date,

            'ptm_for' => [$ptmFor],

            'staff_departments' =>
                in_array($ptmFor, ['Staff', 'All'])
                    ? ($request->staff_departments ?? [])
                    : [],

            'student_departments' =>
                in_array($ptmFor, ['Student', 'All'])
                    ? ($request->student_departments ?? [])
                    : [],

            'standards' =>
                in_array($ptmFor, ['Student', 'All'])
                    ? ($request->standards ?? [])
                    : [],

            'divisions' =>
                in_array($ptmFor, ['Student', 'All'])
                    ? ($request->divisions ?? [])
                    : [],

            'send_sms' => $request->boolean('send_sms'),
        ]);

        return redirect()
            ->route('ptm.index')
            ->with('success', 'PTM updated successfully.');
    }


    public function destroy(Ptm $ptm)
    {
        $ptm->delete();

        return redirect()
            ->route('ptm.index')
            ->with('success', 'PTM deleted successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | Export Page
    |--------------------------------------------------------------------------
    */

    public function export()
    {
        return view('ptm.export');
    }


    /*
    |--------------------------------------------------------------------------
    | Excel Export
    |--------------------------------------------------------------------------
    */

    public function exportExcel(Request $request)
    {
        $ptms = Ptm::orderBy('start_date', 'asc')->get();

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

                'title' => 'PTM Name',

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


        /*
        |--------------------------------------------------------------------------
        | Data
        |--------------------------------------------------------------------------
        */

        foreach ($ptms as $ptm) {

            $ptmFor = $ptm->ptm_for ?? [];


            if (is_string($ptmFor)) {

                $ptmFor =
                    json_decode($ptmFor, true) ?? [];
            }


            /*
            |--------------------------------------------------------------------------
            | Fallback for old records
            |--------------------------------------------------------------------------
            */

            if (empty($ptmFor)) {

                $hasStaff =
                    !empty($ptm->staff_departments);

                $hasStudent =
                    !empty($ptm->student_departments) ||
                    !empty($ptm->standards) ||
                    !empty($ptm->divisions);


                if ($hasStaff && $hasStudent) {

                    $ptmFor = ['All'];

                } elseif ($hasStaff) {

                    $ptmFor = ['Staff'];

                } elseif ($hasStudent) {

                    $ptmFor = ['Student'];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Departments
            |--------------------------------------------------------------------------
            */

            $departments = array_merge(
                $ptm->staff_departments ?? [],
                $ptm->student_departments ?? []
            );


            /*
            |--------------------------------------------------------------------------
            | Columns
            |--------------------------------------------------------------------------
            */

            foreach ($columns as $index => $column) {

                $value = match ($column) {

                    'title' =>
                        $ptm->title ?: '-',

                    'date' =>
                        $ptm->start_date
                        ?? $ptm->date
                        ?? '-',

                    'target' =>
                        !empty($ptmFor)
                        ? implode(', ', $ptmFor)
                        : '-',

                    'department' =>
                        !empty($departments)
                        ? implode(', ', $departments)
                        : '-',

                    'standard' =>
                        !empty($ptm->standards)
                        ? implode(', ', $ptm->standards)
                        : '-',

                    'division' =>
                        !empty($ptm->divisions)
                        ? implode(', ', $ptm->divisions)
                        : '-',

                    'sms' =>
                        $ptm->send_sms
                        ? 'Yes'
                        : 'No',

                    default => '-',
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
        | Auto Size
        |--------------------------------------------------------------------------
        */

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
            'ptms.xlsx'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PDF Export
    |--------------------------------------------------------------------------
    */

    public function exportPdf(Request $request)
    {
        $ptms =
            Ptm::orderBy('start_date', 'asc')->get();


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
            'ptm.pdf',
            compact('ptms', 'columns')
        )
            ->setPaper('a4', 'landscape')
            ->download('ptms.pdf');
    }
}