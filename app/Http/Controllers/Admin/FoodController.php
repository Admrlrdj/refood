<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Donor;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::orderBy('created_at', 'desc')->get();
        $donors = Donor::where('is_verified', true)->get(); // <-- PASTIKAN BARIS INI ADA
        
        // Pastikan 'donors' masuk ke dalam compact di bawah ini:
        return view('admin.pages.foods.index', compact('foods', 'donors')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'donor_id' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'portion' => 'required|integer|min:1',
            'pickup_address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'expired_at' => 'required|date',
        ]);

        Food::create([
            'donor_id' => $request->donor_id,
            'name' => $request->name,
            'description' => $request->description,
            'portion' => (int) $request->portion,
            'pickup_address' => $request->pickup_address,
            'latitude' => (float) $request->latitude,
            'longitude' => (float) $request->longitude,
            'status' => 'available', // Status awal selalu tersedia
            'expired_at' => \Carbon\Carbon::parse($request->expired_at),
        ]);

        return redirect()->back()->with('success', 'Data Donasi Makanan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $food = Food::findOrFail($id);

        $request->validate([
            'donor_id' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'portion' => 'required|integer|min:1',
            'pickup_address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required|string',
            'expired_at' => 'required|date',
        ]);

        $food->update([
            'donor_id' => $request->donor_id,
            'name' => $request->name,
            'description' => $request->description,
            'portion' => (int) $request->portion,
            'pickup_address' => $request->pickup_address,
            'latitude' => (float) $request->latitude,
            'longitude' => (float) $request->longitude,
            'status' => $request->status,
            'expired_at' => \Carbon\Carbon::parse($request->expired_at),
        ]);

        return redirect()->back()->with('success', 'Data Donasi Makanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Food::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data Makanan berhasil dihapus!');
    }
}
