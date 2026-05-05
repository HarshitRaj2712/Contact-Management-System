<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = auth()->user()->contacts()
            ->with('phones', 'emails', 'addresses', 'tags')
            ->paginate(10);

        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = auth()->user()->categories;
        $tags = Tag::all();

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

        if ($request->has('tags')) {
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

        $contact->load('phones', 'emails', 'addresses', 'tags');

        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        $this->authorize('update', $contact);

        $categories = auth()->user()->categories;
        $tags = Tag::all();
        $contact->load('tags');

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

        $contact->update($data);

        if ($request->has('tags')) {
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
            ->with('phones', 'emails', 'addresses')
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

        return response()->json(['success' => true, 'favorite' => $contact->favorite]);
    }
}
