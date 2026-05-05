@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Potential Duplicates</h1>

        <h3>By Email</h3>
        @foreach($byEmail as $group)
            <div class="card mb-2">
                <div class="card-body">
                    @foreach($group as $contact)
                        <div><a href="{{ route('contacts.show', $contact) }}">{{ $contact->full_name }}</a> — {{ $contact->emails->pluck('email')->implode(', ') }}</div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <h3>By Phone</h3>
        @foreach($byPhone as $group)
            <div class="card mb-2">
                <div class="card-body">
                    @foreach($group as $contact)
                        <div><a href="{{ route('contacts.show', $contact) }}">{{ $contact->full_name }}</a> — {{ $contact->phones->pluck('number')->implode(', ') }}</div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
