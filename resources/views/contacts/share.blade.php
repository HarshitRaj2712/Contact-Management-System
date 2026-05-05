@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Share: {{ $contact->full_name }}</h1>

        <div class="mb-3">
            <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={{ $qr }}" alt="QR code">
        </div>

        <h5>vCard</h5>
        <pre class="bg-light p-3">{{ $vcard }}</pre>
    </div>
@endsection
