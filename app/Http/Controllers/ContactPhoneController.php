<?php

namespace App\Http\Controllers;

use App\Models\ContactPhone;
use App\Http\Requests\StorePhoneRequest;
use App\Http\Requests\UpdatePhoneRequest;
use Illuminate\Http\Request;

class ContactPhoneController extends Controller
{
    /**
     * Store a newly created phone number.
     */
    public function store(StorePhoneRequest $request)
    {
        $phone = ContactPhone::create($request->validated());

        return redirect()->back()->with('success', 'Phone number added successfully.');
    }

    /**
     * Delete a phone number.
     */
    public function destroy(ContactPhone $phone)
    {
        $contact = $phone->contact;
        $this->authorize('update', $contact);

        $phone->delete();

        return response()->json(['success' => true]);
    }
}
