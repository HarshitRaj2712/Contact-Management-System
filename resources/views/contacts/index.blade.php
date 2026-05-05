@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-8">
            <h1 class="h2 mb-1">
                <i class="fas fa-address-book me-2"></i>My Contacts
            </h1>
            <p class="text-muted mb-0">Search across names, phone numbers, emails, and company data.</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('contacts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Contact
            </a>
            <a href="{{ route('contacts.trash') }}" class="btn btn-outline-secondary ms-2">
                <i class="fas fa-trash me-2"></i>Trash
            </a>
            <div class="mt-2">
                <a href="{{ route('contacts.export') }}" class="btn btn-sm btn-outline-success mt-2">
                    <i class="fas fa-file-csv me-1"></i>Export CSV
                </a>
                <a href="{{ route('contacts.exportPdf') }}" class="btn btn-sm btn-outline-secondary mt-2">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('contacts.duplicates') }}" class="btn btn-sm btn-outline-warning mt-2">
                    <i class="fas fa-exclamation-triangle me-1"></i>Duplicates
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <form id="contact-filter-form" action="{{ route('contacts.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-lg-5 position-relative">
                    <label for="contact-search" class="form-label">Global Search</label>
                    <input type="search"
                           class="form-control form-control-lg"
                           id="contact-search"
                           name="search"
                           placeholder="Search names, phones, emails, or company"
                           value="{{ $filters['search'] ?? '' }}">
                    <div id="contact-suggestions" class="list-group position-absolute w-100 shadow-sm d-none" style="z-index: 1050;"></div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="active" @selected(($filters['status'] ?? 'active') === 'active')>Active</option>
                        <option value="trashed" @selected(($filters['status'] ?? '') === 'trashed')>Trashed</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <label for="sort" class="form-label">Sort</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="az" @selected(($filters['sort'] ?? 'az') === 'az')>A-Z</option>
                        <option value="za" @selected(($filters['sort'] ?? '') === 'za')>Z-A</option>
                        <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Recently Added</option>
                        <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Oldest First</option>
                        <option value="favorites" @selected(($filters['sort'] ?? '') === 'favorites')>Favorites First</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <label for="tag_ids" class="form-label">Tags</label>
                    <select name="tag_ids[]" id="tag_ids" class="form-select" multiple size="4">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @selected(in_array($tag->id, $filters['tag_ids'] ?? [], false))>
                                {{ $tag->tag_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Hold Ctrl or Cmd to select multiple tags.</div>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <label for="birthday_month" class="form-label">Birthday Month</label>
                    <select name="birthday_month" id="birthday_month" class="form-select">
                        <option value="">Any month</option>
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}" @selected(($filters['birthday_month'] ?? '') == $month)>
                                {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <div class="form-check form-switch pt-4 mt-1">
                        <input class="form-check-input" type="checkbox" id="recently_added" name="recently_added" value="1" @checked(!empty($filters['recently_added']))>
                        <label class="form-check-label" for="recently_added">Added in last 30 days</label>
                    </div>
                </div>

                <div class="col-12 d-flex flex-wrap gap-2 pt-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-rotate me-2"></i>Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <form id="bulk-action-form" method="POST" action="{{ route('contacts.bulkDelete') }}">
        @csrf
        <div class="d-flex justify-content-between mb-2">
            <div>
                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Move selected contacts to trash?')">
                    <i class="fas fa-trash me-1"></i>Bulk Delete
                </button>
            </div>
            <div>
                <a href="{{ route('contacts.import') }}" class="btn btn-outline-primary btn-sm ms-2" onclick="document.getElementById('import-file').click(); return false;">
                    <i class="fas fa-file-import me-1"></i>Import CSV
                </a>
                <input type="file" id="import-file" name="csv_file" accept=".csv" form="import-form" class="d-none">
            </div>
        </div>

        <div id="contact-results">
            @include('contacts.partials.results', ['contacts' => $contacts, 'filters' => $filters])
        </div>
    </form>

    <form id="import-form" method="POST" action="{{ route('contacts.import') }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" id="import-file-hidden" class="d-none">
    </form>
</div>

<script>
    const filterForm = document.getElementById('contact-filter-form');
    const searchInput = document.getElementById('contact-search');
    const suggestionsContainer = document.getElementById('contact-suggestions');
    const resultsContainer = document.getElementById('contact-results');
    let searchTimer = null;

    async function loadContacts(url = null) {
        const queryString = new URLSearchParams(new FormData(filterForm));
        queryString.set('ajax', '1');

        const requestUrl = url ? `${url}${url.includes('?') ? '&' : '?'}ajax=1` : `${filterForm.action}?${queryString.toString()}`;
        const response = await fetch(requestUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        });

        if (!response.ok) {
            return;
        }

        const data = await response.json();
        resultsContainer.innerHTML = data.html;
        renderSuggestions(data.suggestions || []);
        bindPagination();
    }

    function escapeHtml(value) {
        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#39;');
    }

    function renderSuggestions(suggestions) {
        const term = searchInput.value.trim();

        if (!term || suggestions.length === 0) {
            suggestionsContainer.innerHTML = '';
            suggestionsContainer.classList.add('d-none');
            return;
        }

        suggestionsContainer.innerHTML = suggestions.map((suggestion) => `
            <button type="button" class="list-group-item list-group-item-action" data-suggestion-term="${escapeHtml(suggestion.name)}">
                <div class="d-flex justify-content-between align-items-center">
                    <span>${escapeHtml(suggestion.name)}</span>
                    ${suggestion.company ? `<small class="text-muted ms-3">${escapeHtml(suggestion.company)}</small>` : ''}
                </div>
            </button>
        `).join('');
        suggestionsContainer.classList.remove('d-none');
    }

    function bindPagination() {
        resultsContainer.querySelectorAll('.pagination a').forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                loadContacts(link.href);
            });
        });
    }

    filterForm.addEventListener('submit', (event) => {
        event.preventDefault();
        loadContacts();
    });

    filterForm.querySelectorAll('select, input[type="checkbox"]').forEach((element) => {
        element.addEventListener('change', () => loadContacts());
    });

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => loadContacts(), 250);
    });

    suggestionsContainer.addEventListener('click', (event) => {
        const button = event.target.closest('[data-suggestion-term]');

        if (!button) {
            return;
        }

        searchInput.value = button.dataset.suggestionTerm;
        suggestionsContainer.classList.add('d-none');
        loadContacts();
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('#contact-suggestions') && event.target !== searchInput) {
            suggestionsContainer.classList.add('d-none');
        }
    });

    bindPagination();

    async function toggleFavorite(id) {
        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch(`/contacts/${id}/favorite`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({}),
            });

            if (!res.ok) return;
            const data = await res.json();
            const btn = document.getElementById('favoriteBtn' + id);
            const icon = document.getElementById('favoriteIcon' + id);

            if (btn) {
                btn.setAttribute('aria-pressed', data.favorite ? 'true' : 'false');
            }

            if (icon) {
                icon.classList.toggle('fa-solid', !!data.favorite);
                icon.classList.toggle('fa-regular', !data.favorite);
                icon.classList.toggle('favorite-star-icon-favorite', !!data.favorite);
                icon.classList.toggle('favorite-star-icon-normal', !data.favorite);
            }
        } catch (e) {
            console.error('Favorite toggle failed', e);
        }
    }
    // Import file handling
    document.getElementById('import-file')?.addEventListener('change', function(){
        const f = this.files[0];
        if (!f) return;
        const importForm = document.getElementById('import-form');
        const hidden = document.getElementById('import-file-hidden');
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(f);
        hidden.files = dataTransfer.files;
        importForm.submit();
    });
</script>
@endsection
