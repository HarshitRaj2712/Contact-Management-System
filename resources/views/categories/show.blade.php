@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">{{ $category->category_name }}</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <p class="text-muted mb-0">Created {{ $category->created_at->format('F d, Y H:i') }}</p>
        </div>
    </div>
</div>
@endsection
