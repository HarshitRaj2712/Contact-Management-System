<?php

namespace App\Http\Controllers;

use App\Models\ContactAddress;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use Illuminate\Http\Request;

class ContactAddressController extends Controller
{
    /**
     * Store a newly created address.
     */
    public function store(StoreAddressRequest $request)
    {
        $address = ContactAddress::create($request->validated());

        return redirect()->back()->with('success', 'Address added successfully.');
    }

    /**
     * Delete an address.
     */
    public function destroy(ContactAddress $address)
    {
        $contact = $address->contact;
        $this->authorize('update', $contact);

        $address->delete();

        return response()->json(['success' => true]);
    }
}
