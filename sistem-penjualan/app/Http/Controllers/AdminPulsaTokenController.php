<?php

namespace App\Http\Controllers;

use App\Models\PulsaProvider;
use App\Models\PulsaNominal;
use App\Models\TokenNominal;
use Illuminate\Http\Request;

class AdminPulsaTokenController extends Controller
{
    // ==================== PULSA PROVIDERS ====================

    public function pulsaSettings()
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $providers = PulsaProvider::with('nominals')->orderBy('name')->get();
        return view('admin.pulsa_settings', compact('providers'));
    }

    public function storeProvider(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $request->validate(['name' => 'required|unique:pulsa_providers,name|max:50']);

        PulsaProvider::create(['name' => $request->name, 'active' => true]);

        return back()->with('success', 'Provider ' . $request->name . ' berhasil ditambahkan');
    }

    public function deleteProvider($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $provider = PulsaProvider::findOrFail($id);
        $provider->delete();

        return back()->with('success', 'Provider berhasil dihapus');
    }

    // ==================== PULSA NOMINALS ====================

    public function storeNominal(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $request->validate([
            'provider_id' => 'required|exists:pulsa_providers,id',
            'nominal_amount' => 'required|integer|min:1',
            'markup' => 'required|integer|min:0',
        ]);

        PulsaNominal::create([
            'provider_id' => $request->provider_id,
            'nominal_amount' => $request->nominal_amount,
            'markup' => $request->markup,
            'active' => true,
        ]);

        return back()->with('success', 'Nominal berhasil ditambahkan');
    }

    public function updateNominal(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $request->validate([
            'nominal_amount' => 'required|integer|min:1',
            'markup' => 'required|integer|min:0',
        ]);

        $nominal = PulsaNominal::findOrFail($id);
        $nominal->update([
            'nominal_amount' => $request->nominal_amount,
            'markup' => $request->markup,
        ]);

        return back()->with('success', 'Nominal berhasil diperbarui');
    }

    public function deleteNominal($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $nominal = PulsaNominal::findOrFail($id);
        $nominal->delete();

        return back()->with('success', 'Nominal berhasil dihapus');
    }

    // ==================== TOKEN NOMINALS ====================

    public function tokenSettings()
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $nominals = TokenNominal::orderBy('nominal_amount')->get();
        return view('admin.token_settings', compact('nominals'));
    }

    public function storeTokenNominal(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $request->validate([
            'nominal_amount' => 'required|integer|min:1',
            'harga_final' => 'required|integer|min:1',
            'profit' => 'required|integer|min:0',
        ]);

        TokenNominal::create([
            'nominal_amount' => $request->nominal_amount,
            'harga_final' => $request->harga_final,
            'profit' => $request->profit,
            'active' => true,
        ]);

        return back()->with('success', 'Nominal token berhasil ditambahkan');
    }

    public function updateTokenNominal(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $request->validate([
            'nominal_amount' => 'required|integer|min:1',
            'harga_final' => 'required|integer|min:1',
            'profit' => 'required|integer|min:0',
        ]);

        $nominal = TokenNominal::findOrFail($id);
        $nominal->update([
            'nominal_amount' => $request->nominal_amount,
            'harga_final' => $request->harga_final,
            'profit' => $request->profit,
        ]);

        return back()->with('success', 'Nominal token berhasil diperbarui');
    }

    public function deleteTokenNominal($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $nominal = TokenNominal::findOrFail($id);
        $nominal->delete();

        return back()->with('success', 'Nominal token berhasil dihapus');
    }
}
