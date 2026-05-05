@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                <i class="fas fa-address-book me-2"></i>My Contacts
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('contacts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Contact
            </a>
            <a href="{{ route('contacts.trash') }}" class="btn btn-outline-secondary">
                <i class="fas fa-trash me-2"></i>Trash
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($contacts->count() > 0)
        <div class="row g-4">
            @foreach ($contacts as $contact)
                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    @if ($contact->profile_photo)
                                        <img src="{{ Storage::url($contact->profile_photo) }}" 
                                             alt="{{ $contact->full_name }}" 
                                             class="rounded-circle me-3" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-user fs-5 text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="card-title mb-0">{{ $contact->full_name }}</h5>
                                        @if ($contact->company)
                                            <small class="text-muted">{{ $contact->company }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="favorite{{ $contact->id }}"
                                           @checked($contact->favorite) 
                                           onchange="toggleFavorite({{ $contact->id }})">
                                    <label class="form-check-label" for="favorite{{ $contact->id }}">
                                        <i class="fas fa-star text-warning"></i>
                                    </label>
                                </div>
                            </div>

                            @if ($contact->job_title)
                                <p class="text-muted mb-2">
                                    <i class="fas fa-briefcase me-2"></i>{{ $contact->job_title }}
                                </p>
                            @endif

                            <div class="small mb-3">
                                @if ($contact->phones->count() > 0)
                                    <div class="mb-2">
                                        <i class="fas fa-phone me-2 text-primary"></i>
                                        @foreach ($contact->phones as $phone)
                                            <span>{{ $phone->phone_number }}</span>
                                            @if (!$loop->last)<br>@endif
                                        @endforeach
                                    </div>
                                @endif

                                @if ($contact->emails->count() > 0)
                                    <div class="mb-2">
                                        <i class="fas fa-envelope me-2 text-success"></i>
                                        @foreach ($contact->emails as $email)
                                            <span>{{ $email->email }}</span>
                                            @if (!$loop->last)<br>@endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            @if ($contact->tags->count() > 0)
                                <div class="mb-3">
                                    @foreach ($contact->tags as $tag)
                                        <span class="badge bg-info">{{ $tag->tag_name }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('contacts.show', $contact) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $contact->id }}">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteModal{{ $contact->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Delete Contact</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete <strong>{{ $contact->full_name }}</strong>?</p>
                                    <p class="text-muted small">This action moves the contact to trash and can be restored later.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form method="POST" action="{{ route('contacts.destroy', $contact) }}" style="display: inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row mt-4">
                <div class="col-12">
                    {{ $contacts->links() }}
                </div>
            </div>
    @else
        <div class="alert alert-info text-center py-5" role="alert">
            <i class="fas fa-inbox fs-1 mb-3 d-block"></i>
            <h5>No contacts found</h5>
            <p class="mb-0">Start by <a href="{{ route('contacts.create') }}" class="alert-link">creating your first contact</a></p>
        </div>
    @endif
</div>

<script>
    function toggleFavorite(contactId) {
        // AJAX call to update favorite status
        fetch(`/contacts/${contactId}/favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        }).catch(err => console.error(err));
    }
</script>
@endsection
