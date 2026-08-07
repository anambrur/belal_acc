<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    /**
     * Show the store profile edit form (singleton).
     */
    public function edit()
    {
        $store = Store::current() ?? new Store([
            'store_name' => '',
            'owner_name' => '',
            'phone' => '',
            'email' => '',
            'address' => '',
            'city' => '',
            'invoice_footer' => '',
        ]);

        return view('store.storeEdit', compact('store'));
    }

    /**
     * Create or update the single store profile.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:100',
            'owner_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:100',
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'invoice_footer' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|max:2048',
        ]);

        $store = Store::current() ?? new Store();

        if ($request->hasFile('logo')) {
            if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                Storage::disk('public')->delete($store->logo);
            }
            $validated['logo'] = $request->file('logo')->store('stores', 'public');
        } else {
            unset($validated['logo']);
        }

        $store->fill($validated);
        $store->save();

        return redirect()->route('store.edit')
            ->with('success', 'Store settings updated successfully.');
    }
}
