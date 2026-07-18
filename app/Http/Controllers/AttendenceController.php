<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendenceController extends Controller
{

    public function showAttendence()
    {
        $employees = DB::select('SELECT id, first_name FROM att_employee');
        $attendances = DB::select('SELECT id, emp_code, punch_time, punch_state FROM attendance');


        return view('attendence.index', compact('employees', 'attendances'));
    }
}
