@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Activity Logs</h1>

        <table class="table table-sm">
            <thead>
                <tr>
                    <th>When</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Subject</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->diffForHumans() }}</td>
                        <td>{{ $log->user?->name ?? 'System' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ class_basename($log->subject_type) }} #{{ $log->subject_id }}</td>
                        <td style="max-width:400px;overflow:auto">{{ \Illuminate\Support\Str::limit($log->changes, 200) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $logs->links() }}
    </div>
@endsection
