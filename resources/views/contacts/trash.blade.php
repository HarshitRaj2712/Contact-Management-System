@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                <i class="fas fa-trash me-2"></i>Trash
            </h1>
            <p class="text-muted">Deleted contacts are kept for 30 days before being permanently removed</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Contacts
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($deletedContacts->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Contact Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Deleted On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deletedContacts as $contact)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($contact->profile_photo)
                                        <img src="{{ Storage::url($contact->profile_photo) }}"
                                             alt="{{ $contact->full_name }}"
                                             class="rounded-circle me-2"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2"
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                    @endif
                                    <span>{{ $contact->full_name }}</span>
                                </div>
                            </td>
                            <td>{{ $contact->company ?? '-' }}</td>
                            <td>
                                @if ($contact->emails->count() > 0)
                                    {{ $contact->emails->first()->email }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($contact->phones->count() > 0)
                                    {{ $contact->phones->first()->phone_number }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $contact->deleted_at->format('M d, Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-success"
                                            onclick="restoreContact({{ $contact->id }})">
                                        <i class="fas fa-redo me-1"></i>Restore
                                    </button>
                                    <button type="button" class="btn btn-outline-danger"
                                            data-bs-toggle="modal" data-bs-target="#permanentDeleteModal{{ $contact->id }}">
                                        <i class="fas fa-times me-1"></i>Delete Permanently
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Permanent Delete Modal -->
                        <div class="modal fade" id="permanentDeleteModal{{ $contact->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Permanently Delete Contact</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-0">
                                            <strong class="text-danger">⚠ Warning:</strong> This will permanently delete
                                            <strong>{{ $contact->full_name }}</strong> and all associated data. This action cannot be undone.
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form method="POST" action="{{ route('contacts.forceDelete', $contact) }}" style="display: inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Permanently Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12">
                {{ $deletedContacts->links() }}
            </div>
        </div>
    @else
        <div class="alert alert-info text-center py-5" role="alert">
            <i class="fas fa-check fs-1 mb-3 d-block"></i>
            <h5>Trash is Empty</h5>
            <p class="mb-0">All your deleted contacts have been permanently removed or restored</p>
        </div>
    @endif
</div>

<script>
    function restoreContact(contactId) {
        if (confirm('Restore this contact?')) {
            fetch(`/contacts/${contactId}/restore`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            }).catch(err => {
                console.error(err);
                alert('Error restoring contact');
            });
        }
    }
</script>
@endsection
