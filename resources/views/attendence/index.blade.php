@extends('layout')
@section('title')
    Category List
@endsection


@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Product Company list</h4>
                    <h6>View/Search product Company</h6>
                </div>
                <div class="page-btn">

                </div>
            </div>

            <hr>
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                   

                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">

                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                @for ($day = 1; $day <= 31; $day++)
                                    <th>{{ $day }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td>{{ $employee->first_name }}</td>
                                    @for ($day = 1; $day <= 31; $day++)
                                        @php
                                            $status = 'A';
                                            foreach ($attendances as $attendance) {
                                                $punchDate = \Carbon\Carbon::parse($attendance->punch_time);
                                                if ($attendance->emp_code == $employee->id && $punchDate->day == $day) {
                                                    if (
                                                        $attendance->punch_state == 0 ||
                                                        $attendance->punch_state == 4
                                                    ) {
                                                        $status = 'P';
                                                    } elseif (
                                                        $attendance->punch_state == 1 ||
                                                        $attendance->punch_state == 5
                                                    ) {
                                                        $status = 'A';
                                                    }
                                                    break;
                                                }
                                            }
                                        @endphp
                                        <td class="{{ $status == 'P' ? 'text-success' : 'text-danger' }}">
                                            {!! $status == 'P'
                                                ? '<i class="fa-solid fa-check fa-lg" style="color: #00ad1d;"></i>'
                                                : ($status == 'A'
                                                    ? '<i class="fa-solid fa-xmark fa-flip-both fa-lg" style="color: #ff0000;"></i>'
                                                    : '') !!}
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
