@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">{{ $tag->tag_name }}</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('tags.edit', $tag) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('tags.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Contacts with this tag ({{ $tag->contacts->count() }})</h5>
        </div>
        <div class="card-body">
            @if ($tag->contacts->count() > 0)
                <div class="list-group">
                    @foreach ($tag->contacts as $contact)
                        <a href="{{ route('contacts.show', $contact) }}" class="list-group-item list-group-item-action">
                            {{ $contact->full_name }}
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-muted text-center py-4 mb-0">No contacts with this tag</p>
            @endif
        </div>
    </div>
</div>
@endsection
