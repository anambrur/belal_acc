<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReciveAttendenceDataController extends Controller
{
    public function receiveData(Request $request)
    {
        $employees = $request->input('employees');
        $attendanceData = $request->input('attendanceData');
        Log::info('Data received successfully', [
            'employees' => $employees,
            'attendanceData' => $attendanceData,
        ]);

        foreach ($employees as $employee) {
            DB::table('att_employee')->insert([
                'attn_id' => $employee['id'],
                'first_name' => $employee['first_name'],
                // Add other fields as necessary
            ]);
        }

        // Example: Inserting attendance data into the iclock_transaction table
        foreach ($attendanceData as $attendance) {
            DB::table('attendance')->insert([
                'attn_id' => $attendance['id'],
                'emp_code' => $attendance['emp_code'],
                'punch_time' => $attendance['punch_time'],
                'punch_state' => $attendance['punch_state'],
                // Add other fields as necessary
            ]);
        }

        // Return a JSON response confirming the data was received and processed
        return response()->json(['message' => 'Data received and processed successfully'], 200);
    }
}
