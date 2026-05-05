@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">Create Tag</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('tags.store') }}" method="POST" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="tag_name" class="form-label">Tag Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('tag_name') is-invalid @enderror"
                           id="tag_name" name="tag_name" value="{{ old('tag_name') }}" required>
                    @error('tag_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create
                    </button>
                    <a href="{{ route('tags.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
