<?php

namespace App\Http\Controllers;

use App\Models\AnnualCalendar;
use App\Models\Holiday;
use App\Models\Event;
use App\Models\Ptm;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnnualCalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        $date = Carbon::createFromFormat('Y-m', $month);

        $calendar = [
            'month' => $date->month,
            'year' => $date->year,
            'name' => $date->format('F Y'),
            'daysInMonth' => $date->daysInMonth,
            'startDay' => $date->copy()->startOfMonth()->dayOfWeek,
        ];
$upcomingHolidays = Holiday::whereDate('date', '>=', now()->toDateString())
    ->where('status', true)
    ->orderBy('date', 'asc')
    ->get();

$upcomingEvents = Event::whereDate('date', '>=', now()->toDateString())
    ->where('status', true)
    ->orderBy('date', 'asc')
    ->get();

$upcomingPtms = Ptm::whereDate('date', '>=', now()->toDateString())
    ->where('status', true)
    ->orderBy('date', 'asc')
    ->get();

        /*
        |--------------------------------------------------------------------------
        | Previous / Next Month
        |--------------------------------------------------------------------------
        */

        $previousMonth = $date->copy()
            ->subMonth()
            ->format('Y-m');

        $nextMonth = $date->copy()
            ->addMonth()
            ->format('Y-m');


        /*
        |--------------------------------------------------------------------------
        | Holidays
        |--------------------------------------------------------------------------
        */

        $holidays = Holiday::whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->where('status', true)
            ->get()
            ->groupBy(function ($holiday) {

                return Carbon::parse($holiday->date)
                    ->format('Y-m-d');

            });


        /*
        |--------------------------------------------------------------------------
        | Events
        |--------------------------------------------------------------------------
        */

        $events = Event::whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->where('status', true)
            ->get()
            ->groupBy(function ($event) {

                return Carbon::parse($event->date)
                    ->format('Y-m-d');

            });


        /*
        |--------------------------------------------------------------------------
        | PTM
        |--------------------------------------------------------------------------
        */

        $ptms = Ptm::whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->where('status', true)
            ->get()
            ->groupBy(function ($ptm) {

                return Carbon::parse($ptm->date)
                    ->format('Y-m-d');

            });


       return view(
    'annual_calendar.index',
    compact(
        'calendar',
        'previousMonth',
        'nextMonth',
        'holidays',
        'events',
        'ptms',
        'upcomingHolidays',
        'upcomingEvents',
        'upcomingPtms'
    )
);
    }


    public function create()
    {
        return view('annual_calendar.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'academic_year' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        AnnualCalendar::create([
            'academic_year' => $request->academic_year,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()
            ->route('annual_calendar.index')
            ->with('success', 'Academic year added successfully.');
    }
}