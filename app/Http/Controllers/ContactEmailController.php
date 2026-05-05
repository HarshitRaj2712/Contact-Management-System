<?php

namespace App\Http\Controllers;

use App\Models\ContactEmail;
use App\Http\Requests\StoreEmailRequest;
use App\Http\Requests\UpdateEmailRequest;
use Illuminate\Http\Request;

class ContactEmailController extends Controller
{
    /**
     * Store a newly created email address.
     */
    public function store(StoreEmailRequest $request)
    {
        $email = ContactEmail::create($request->validated());

        return redirect()->back()->with('success', 'Email address added successfully.');
    }

    /**
     * Delete an email address.
     */
    public function destroy(ContactEmail $email)
    {
        $contact = $email->contact;
        $this->authorize('update', $contact);

        $email->delete();

        return response()->json(['success' => true]);
    }
}
