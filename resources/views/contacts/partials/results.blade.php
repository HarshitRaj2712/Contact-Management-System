@php
    $isTrashed = ($filters['status'] ?? 'active') === 'trashed';
@endphp

@if ($contacts->count() > 0)
    <div class="row g-4">
        @foreach ($contacts as $contact)
            <div class="col-lg-6">
                <div class="d-flex justify-content-end mb-2 pe-1">
                    <input class="form-check-input bulk-select" type="checkbox" name="contact_ids[]" value="{{ $contact->id }}" id="select{{ $contact->id }}">
                </div>
                <div class="card h-100 shadow-sm border-0 {{ $isTrashed ? 'border-danger' : '' }}">
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
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h5 class="card-title mb-0">{{ $contact->full_name }}</h5>
                                        @if ($isTrashed)
                                            <span class="badge text-bg-danger">Trashed</span>
                                        @endif
                                    </div>
                                    @if ($contact->company)
                                        <small class="text-muted">{{ $contact->company }}</small>
                                    @endif
                                    @if ($contact->category)
                                        <div class="mt-1">
                                            <span class="badge text-bg-light border">{{ $contact->category->category_name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @unless ($isTrashed)
                                <button type="button"
                                        id="favoriteBtn{{ $contact->id }}"
                                        class="favorite-star-btn"
                                        aria-pressed="{{ $contact->favorite ? 'true' : 'false' }}"
                                        onclick="toggleFavorite({{ $contact->id }})"
                                        title="Toggle favorite">
                                    <i id="favoriteIcon{{ $contact->id }}" class="fa-star {{ $contact->favorite ? 'fa-solid favorite-star-icon-favorite' : 'fa-regular favorite-star-icon-normal' }}"></i>
                                </button>
                            @endunless
                        </div>

                        @unless ($isTrashed)
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
                                            @if (! $loop->last)<br>@endif
                                        @endforeach
                                    </div>
                                @endif

                                @if ($contact->emails->count() > 0)
                                    <div class="mb-2">
                                        <i class="fas fa-envelope me-2 text-success"></i>
                                        @foreach ($contact->emails as $email)
                                            <span>{{ $email->email }}</span>
                                            @if (! $loop->last)<br>@endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            @if ($contact->tags->count() > 0)
                                <div class="mb-3 d-flex flex-wrap gap-2">
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
                        @else
                            <p class="text-muted mb-3">
                                Deleted {{ $contact->deleted_at?->diffForHumans() }}
                            </p>

                            <div class="d-flex gap-2 flex-wrap">
                                <form method="POST" action="{{ route('contacts.restore', $contact) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-rotate-right me-1"></i>Restore
                                    </button>
                                </form>
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#forceDeleteModal{{ $contact->id }}">
                                    <i class="fas fa-trash-can me-1"></i>Delete Forever
                                </button>
                            </div>
                        @endunless
                    </div>
                </div>

                @unless ($isTrashed)
                    <div class="modal fade" id="deleteModal{{ $contact->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Delete Contact</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete <strong>{{ $contact->full_name }}</strong>?</p>
                                    <p class="text-muted small mb-0">This action moves the contact to trash and can be restored later.</p>
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
                @else
                    <div class="modal fade" id="forceDeleteModal{{ $contact->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content border-danger">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Delete Permanently</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>This will permanently delete <strong>{{ $contact->full_name }}</strong> and remove the profile photo from storage.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form method="POST" action="{{ route('contacts.forceDelete', $contact) }}" style="display: inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete Forever</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endunless
            </div>
        @endforeach
    </div>

    <div class="row mt-4">
        <div class="col-12">
            {{ $contacts->links() }}
        </div>
    </div>
@else
    <div class="alert alert-info text-center py-5" role="alert">
        <i class="fas fa-inbox fs-1 mb-3 d-block"></i>
        <h5>No contacts found</h5>
        <p class="mb-0">
            @if ($isTrashed)
                No trashed contacts match the current filters.
            @else
                Start by <a href="{{ route('contacts.create') }}" class="alert-link">creating your first contact</a>
            @endif
        </p>
    </div>
@endif
