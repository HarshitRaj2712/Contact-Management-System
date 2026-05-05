<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ActivityLog;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $this->contactFilters($request);

        $contacts = $this->contactIndexQuery($request->user(), $filters)
            ->paginate(10)
            ->withQueryString();

        $categories = $request->user()->categories()
            ->orderBy('category_name')
            ->get();

        $tags = Tag::orderBy('tag_name')->get();

        if ($request->boolean('ajax') || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('contacts.partials.results', compact('contacts', 'filters'))->render(),
                'suggestions' => $this->contactSuggestions($request->user()->id, $filters),
                'total' => $contacts->total(),
            ]);
        }

        return view('contacts.index', compact('contacts', 'categories', 'tags', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = auth()->user()->categories()->orderBy('category_name')->get();
        $tags = Tag::orderBy('tag_name')->get();

        return view('contacts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('contacts', 'public');
        }

        $contact = auth()->user()->contacts()->create($data);

        // Activity log
        ActivityLog::create([
            'user_id' => auth()->id(),
            'subject_type' => Contact::class,
            'subject_id' => $contact->id,
            'action' => 'created',
            'changes' => json_encode($data),
        ]);

        if ($request->filled('tags')) {
            $contact->tags()->sync($request->input('tags', []));
        }

        return redirect()->route('contacts.show', $contact)
            ->with('success', 'Contact created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        $this->authorize('view', $contact);

        $contact->load('phones', 'emails', 'addresses', 'tags', 'category');

        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        $this->authorize('update', $contact);

        $categories = auth()->user()->categories()->orderBy('category_name')->get();
        $tags = Tag::orderBy('tag_name')->get();
        $contact->load('tags', 'category');

        return view('contacts.edit', compact('contact', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $this->authorize('update', $contact);

        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('profile_photo')) {
            // Delete old image if exists
            if ($contact->profile_photo) {
                Storage::disk('public')->delete($contact->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('contacts', 'public');
        }

        $original = $contact->getOriginal();
        $contact->update($data);

        $changes = $contact->getChanges();
        ActivityLog::create([
            'user_id' => auth()->id(),
            'subject_type' => Contact::class,
            'subject_id' => $contact->id,
            'action' => 'updated',
            'changes' => json_encode(['before' => $original, 'after' => $changes]),
        ]);

        if ($request->filled('tags')) {
            $contact->tags()->sync($request->input('tags', []));
        }

        return redirect()->route('contacts.show', $contact)
            ->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Contact $contact)
    {
        $this->authorize('delete', $contact);

        $contact->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'subject_type' => Contact::class,
            'subject_id' => $contact->id,
            'action' => 'deleted',
            'changes' => null,
        ]);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact moved to trash.');
    }

    /**
     * View trash/deleted contacts.
     */
    public function trash()
    {
        $deletedContacts = auth()->user()->contacts()
            ->onlyTrashed()
            ->with('phones', 'emails', 'addresses', 'category')
            ->paginate(20);

        return view('contacts.trash', compact('deletedContacts'));
    }

    /**
     * Restore a soft deleted contact.
     */
    public function restore(Contact $contact)
    {
        $this->authorize('restore', $contact);

        $contact->restore();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'subject_type' => Contact::class,
            'subject_id' => $contact->id,
            'action' => 'restored',
            'changes' => null,
        ]);

        return redirect()->route('contacts.show', $contact)
            ->with('success', 'Contact restored successfully.');
    }

    /**
     * Permanently delete a contact.
     */
    public function forceDelete(Contact $contact)
    {
        $this->authorize('forceDelete', $contact);

        // Delete profile photo if exists
        if ($contact->profile_photo) {
            Storage::disk('public')->delete($contact->profile_photo);
        }

        // Delete related records
        $contact->phones()->forceDelete();
        $contact->emails()->forceDelete();
        $contact->addresses()->forceDelete();
        $contact->tags()->detach();

        $contact->forceDelete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'subject_type' => Contact::class,
            'subject_id' => $contact->id,
            'action' => 'force_deleted',
            'changes' => null,
        ]);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact permanently deleted.');
    }

    /**
     * Toggle favorite status via AJAX.
     */
    public function toggleFavorite(Contact $contact)
    {
        $this->authorize('update', $contact);

        $contact->update(['favorite' => !$contact->favorite]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'subject_type' => Contact::class,
            'subject_id' => $contact->id,
            'action' => 'favorite_toggled',
            'changes' => json_encode(['favorite' => $contact->favorite]),
        ]);

        return response()->json(['success' => true, 'favorite' => $contact->favorite]);
    }

    /**
     * Share contact vCard / QR view
     */
    public function share(Contact $contact)
    {
        $this->authorize('view', $contact);

        $vcard = "BEGIN:VCARD\nVERSION:3.0\nFN:" . $contact->full_name . "\n";
        if ($contact->company) $vcard .= "ORG:" . $contact->company . "\n";
        if ($contact->emails->isNotEmpty()) $vcard .= "EMAIL:" . $contact->emails->first()->email . "\n";
        if ($contact->phones->isNotEmpty()) $vcard .= "TEL:" . $contact->phones->first()->number . "\n";
        if ($contact->birthday) $vcard .= "BDAY:" . $contact->birthday . "\n";
        $vcard .= "END:VCARD";

        $qr = urlencode($vcard);

        return view('contacts.share', compact('contact', 'vcard', 'qr'));
    }

    /**
     * Build the contact query used for listing and AJAX search.
     */
    private function contactIndexQuery($user, array $filters)
    {
        return Contact::query()
            ->forUser($user->id)
            ->with(['phones', 'emails', 'addresses', 'tags', 'category'])
            ->search($filters['search'])
            ->inCategory($filters['category_id'])
            ->withTagIds($filters['tag_ids'])
            ->birthdayMonth($filters['birthday_month'])
            ->recentlyAddedWindow($filters['recently_added'])
            ->status($filters['status'])
            ->sortBy($filters['sort']);
    }

    /**
     * Normalize filter values from the request.
     */
    private function contactFilters(Request $request): array
    {
        return [
            'search' => trim((string) $request->input('search', '')),
            'category_id' => $request->input('category_id'),
            'tag_ids' => array_values(array_filter((array) $request->input('tag_ids', []))),
            'birthday_month' => $request->input('birthday_month'),
            'recently_added' => $request->boolean('recently_added'),
            'status' => $request->input('status', 'active'),
            'sort' => $request->input('sort', 'az'),
        ];
    }

    /**
     * Build live search suggestions for the AJAX dropdown.
     */
    private function contactSuggestions(int $userId, array $filters): array
    {
        if ($filters['search'] === '') {
            return [];
        }

        return Contact::query()
            ->forUser($userId)
            ->search($filters['search'])
            ->inCategory($filters['category_id'])
            ->withTagIds($filters['tag_ids'])
            ->birthdayMonth($filters['birthday_month'])
            ->recentlyAddedWindow($filters['recently_added'])
            ->status($filters['status'])
            ->sortBy($filters['sort'])
            ->limit(5)
            ->get(['id', 'first_name', 'last_name', 'company'])
            ->map(function (Contact $contact) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->full_name,
                    'company' => $contact->company,
                    'url' => route('contacts.show', $contact),
                ];
            })
            ->all();
    }
}
