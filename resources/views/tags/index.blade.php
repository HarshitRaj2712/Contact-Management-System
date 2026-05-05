@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">
                <i class="fas fa-tags me-2"></i>Tags
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('tags.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Tag
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($tags->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tag Name</th>
                        <th>Contacts</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tags as $tag)
                        <tr>
                            <td>{{ $tag->tag_name }}</td>
                            <td><span class="badge bg-info">{{ $tag->contacts_count }}</span></td>
                            <td><small class="text-muted">{{ $tag->created_at->format('M d, Y') }}</small></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('tags.show', $tag) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('tags.edit', $tag) }}" class="btn btn-outline-warning">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $tag->id }}">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="deleteModal{{ $tag->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Delete Tag</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Delete <strong>{{ $tag->tag_name }}</strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form method="POST" action="{{ route('tags.destroy', $tag) }}" style="display: inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                {{ $tags->links() }}
            </div>
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-inbox fs-1 mb-3 d-block"></i>
            <p class="mb-0">No tags yet. <a href="{{ route('tags.create') }}">Create one now</a></p>
        </div>
    @endif
</div>
@endsection
