@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Contacts Export</h1>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Emails</th>
                    <th>Phones</th>
                    <th>Tags</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $c)
                    <tr>
                        <td>{{ $c->full_name }}</td>
                        <td>{{ $c->company }}</td>
                        <td>{{ $c->emails->pluck('email')->implode(', ') }}</td>
                        <td>{{ $c->phones->pluck('number')->implode(', ') }}</td>
                        <td>{{ $c->tags->pluck('tag_name')->implode(', ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
