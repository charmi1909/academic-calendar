<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnualCalendar extends Model
{
    protected $fillable = [
        'academic_year',
        'start_date',
        'end_date',
        'status',
    ];
}