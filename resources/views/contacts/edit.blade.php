@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                <i class="fas fa-edit me-2"></i>Edit Contact
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('contacts.show', $contact) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">Please fix the following errors:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('contacts.update', $contact) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf @method('PUT')
                
                <!-- Basic Information Section -->
                <h5 class="mb-3 border-bottom pb-2">
                    <i class="fas fa-user me-2"></i>Basic Information
                </h5>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                               id="first_name" name="first_name" value="{{ old('first_name', $contact->first_name) }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                               id="last_name" name="last_name" value="{{ old('last_name', $contact->last_name) }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="company" class="form-label">Company</label>
                        <input type="text" class="form-control @error('company') is-invalid @enderror" 
                               id="company" name="company" value="{{ old('company', $contact->company) }}">
                        @error('company')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="job_title" class="form-label">Job Title</label>
                        <input type="text" class="form-control @error('job_title') is-invalid @enderror" 
                               id="job_title" name="job_title" value="{{ old('job_title', $contact->job_title) }}">
                        @error('job_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Profile Photo -->
                <div class="mb-3">
                    <label for="profile_photo" class="form-label">Profile Photo</label>
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" 
                                       id="profile_photo" name="profile_photo" accept="image/*" onchange="previewImage()">
                                <span class="input-group-text">
                                    <i class="fas fa-image"></i>
                                </span>
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div id="imagePreview" class="mt-2">
                        @if ($contact->profile_photo)
                            <img src="{{ Storage::url($contact->profile_photo) }}" alt="{{ $contact->full_name }}" 
                                 class="img-thumbnail" style="max-width: 150px;">
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="birthday" class="form-label">Birthday</label>
                        <input type="date" class="form-control @error('birthday') is-invalid @enderror" 
                               id="birthday" name="birthday" value="{{ old('birthday', $contact->birthday?->format('Y-m-d')) }}">
                        @error('birthday')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Notes Section -->
                <h5 class="mb-3 border-bottom pb-2 mt-4">
                    <i class="fas fa-sticky-note me-2"></i>Additional Information
                </h5>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" name="notes" rows="4">{{ old('notes', $contact->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Additional information about this contact</small>
                </div>

                <!-- Tags Section -->
                <h5 class="mb-3 border-bottom pb-2 mt-4">
                    <i class="fas fa-tags me-2"></i>Tags
                </h5>

                <div class="mb-3">
                    <label class="form-label">Assign Tags</label>
                    <div class="row g-2">
                        @foreach ($tags as $tag)
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tags[]" 
                                           value="{{ $tag->id }}" id="tag{{ $tag->id }}"
                                           @if($contact->tags->contains($tag->id)) checked @endif>
                                    <label class="form-check-label" for="tag{{ $tag->id }}">
                                        {{ $tag->tag_name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                    <a href="{{ route('contacts.show', $contact) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage() {
        const file = document.getElementById('profile_photo').files[0];
        const preview = document.getElementById('imagePreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 150px;">`;
            };
            reader.readAsDataURL(file);
        }
    }

    // Bootstrap form validation
    (function() {
        'use strict';
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    })();
</script>
@endsection
