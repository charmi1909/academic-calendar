<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'date',
        'start_date',
        'end_date',
        'event_for',
        'staff_departments',
        'student_departments',
        'standards',
        'divisions',
        'send_sms',
        'status',
    ];

    protected $casts = [
        'event_for' => 'array',
        'staff_departments' => 'array',
        'student_departments' => 'array',
        'standards' => 'array',
        'divisions' => 'array',
        'send_sms' => 'boolean',
        'status' => 'boolean',
    ];
}