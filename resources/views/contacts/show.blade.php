@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                @if ($contact->profile_photo)
                    <img src="{{ Storage::url($contact->profile_photo) }}" alt="{{ $contact->full_name }}" 
                         class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-user fs-2 text-muted"></i>
                    </div>
                @endif
                <div>
                    <h1 class="h2 mb-1">{{ $contact->full_name }}</h1>
                    @if ($contact->company)
                        <p class="text-muted mb-0">
                            <i class="fas fa-briefcase me-2"></i>{{ $contact->job_title ? $contact->job_title . ' at ' : '' }}{{ $contact->company }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group" role="group">
                <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash me-2"></i>Delete
                </button>
            </div>
            <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary mt-2">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Info Card -->
        <div class="col-lg-8">
            <!-- Contact Details -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">First Name</h6>
                            <p class="mb-0">{{ $contact->first_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Last Name</h6>
                            <p class="mb-0">{{ $contact->last_name }}</p>
                        </div>
                    </div>
                    @if ($contact->birthday)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Birthday</h6>
                                <p class="mb-0">{{ $contact->birthday->format('F d, Y') }}</p>
                            </div>
                        </div>
                    @endif
                    @if ($contact->notes)
                        <div>
                            <h6 class="text-muted mb-2">Notes</h6>
                            <p class="mb-0">{{ $contact->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Phone Numbers -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-phone me-2"></i>Phone Numbers</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPhoneModal">
                            <i class="fas fa-plus me-1"></i>Add
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if ($contact->phones->count() > 0)
                        <div class="list-group">
                            @foreach ($contact->phones as $phone)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-info mb-2">{{ ucfirst($phone->type) }}</span>
                                        <p class="mb-0">{{ $phone->phone_number }}</p>
                                    </div>
                                    <div class="btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-warning btn-sm" 
                                                onclick="editPhone({{ $phone->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                onclick="deletePhone({{ $phone->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4 mb-0">No phone numbers added</p>
                    @endif
                </div>
            </div>

            <!-- Emails -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>Email Addresses</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addEmailModal">
                            <i class="fas fa-plus me-1"></i>Add
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if ($contact->emails->count() > 0)
                        <div class="list-group">
                            @foreach ($contact->emails as $email)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-success mb-2">{{ $email->type }}</span>
                                        <p class="mb-0">{{ $email->email }}</p>
                                    </div>
                                    <div class="btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                onclick="deleteEmail({{ $email->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4 mb-0">No email addresses added</p>
                    @endif
                </div>
            </div>

            <!-- Addresses -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Addresses</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            <i class="fas fa-plus me-1"></i>Add
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if ($contact->addresses->count() > 0)
                        <div class="list-group">
                            @foreach ($contact->addresses as $address)
                                <div class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="mb-1"><strong>{{ $address->address_line }}</strong></p>
                                        <p class="mb-0 text-muted small">
                                            {{ $address->city }}{{ $address->state ? ', ' . $address->state : '' }} 
                                            {{ $address->zip_code }}{{ $address->country ? ', ' . $address->country : '' }}
                                        </p>
                                    </div>
                                    <div class="btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                onclick="deleteAddress({{ $address->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4 mb-0">No addresses added</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Tags -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Tags</h5>
                </div>
                <div class="card-body">
                    @if ($contact->tags->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($contact->tags as $tag)
                                <span class="badge bg-info">{{ $tag->tag_name }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3 mb-0">No tags assigned</p>
                    @endif
                </div>
            </div>

            <!-- Metadata -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Metadata</h5>
                </div>
                <div class="card-body small">
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Created</h6>
                        <p class="mb-0">{{ $contact->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Updated</h6>
                        <p class="mb-0">{{ $contact->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Phone Modal -->
<div class="modal fade" id="addPhoneModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Phone Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('phones.store') }}" method="POST">
                @csrf
                <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="mobile">Mobile</option>
                            <option value="home">Home</option>
                            <option value="work">Work</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Email Modal -->
<div class="modal fade" id="addEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Email Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('emails.store') }}" method="POST">
                @csrf
                <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="email_type" class="form-label">Type</label>
                        <input type="text" class="form-control" id="email_type" name="type" value="personal">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('addresses.store') }}" method="POST">
                @csrf
                <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="address_line" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address_line" name="address_line" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="zip_code" class="form-label">ZIP Code</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
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

<script>
    function deletePhone(phoneId) {
        if (confirm('Delete this phone number?')) {
            fetch(`/phones/${phoneId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            }).then(r => r.ok && location.reload());
        }
    }

    function deleteEmail(emailId) {
        if (confirm('Delete this email address?')) {
            fetch(`/emails/${emailId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            }).then(r => r.ok && location.reload());
        }
    }

    function deleteAddress(addressId) {
        if (confirm('Delete this address?')) {
            fetch(`/addresses/${addressId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            }).then(r => r.ok && location.reload());
        }
    }
</script>
@endsection
