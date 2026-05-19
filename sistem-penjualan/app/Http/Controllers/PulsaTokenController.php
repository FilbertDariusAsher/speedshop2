<?php

namespace App\Http\Controllers;

use App\Models\PulsaToken;
use App\Models\PulsaProvider;
use App\Models\PulsaNominal;
use App\Models\TokenNominal;
use Illuminate\Http\Request;

class PulsaTokenController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'karyawan') {
            abort(403);
        }

        $providers = PulsaProvider::with('nominals')->where('active', true)->get();
        $tokenNominals = TokenNominal::where('active', true)->orderBy('nominal_amount')->get();
        return view('pulsa_token', compact('providers', 'tokenNominals'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'karyawan') {
            abort(403);
        }
        try {
            $request->validate([
                'customer_name' => 'required',
                'type' => 'required|in:pulsa,paket_internet,token_listrik',
                'provider' => 'nullable|string',
                'provider_id' => 'nullable|exists:pulsa_providers,id',
                'phone_number' => 'nullable|string',
                'token_number' => 'nullable|string',
                'price' => 'nullable|numeric',
                'pulsa_nominal_id' => 'nullable|exists:pulsa_nominals,id',
                'token_nominal_id' => 'nullable|exists:token_nominals,id',
                'amount' => 'nullable|string',
                'package_name' => 'nullable|string',
            ]);

            $price = $request->price;
            $amount = $request->amount;
            $provider = $request->provider;
            $packageName = $request->package_name;

            if ($request->type == 'pulsa') {
                $providerModel = PulsaProvider::find($request->provider_id);
                $nominal = PulsaNominal::find($request->pulsa_nominal_id);

                if (!$providerModel || !$nominal) {
                    return redirect('/pulsa-token')->with('error', 'Provider atau nominal pulsa tidak valid');
                }

                $price = $nominal->getTotalPrice();
                $amount = $nominal->nominal_amount . 'k';
                $provider = $providerModel->name;
            } elseif ($request->type == 'token_listrik') {
                $tokenNominal = TokenNominal::find($request->token_nominal_id);

                if (!$tokenNominal) {
                    return redirect('/pulsa-token')->with('error', 'Nominal token tidak valid');
                }

                $price = $tokenNominal->harga_final + $tokenNominal->profit;
                $amount = $tokenNominal->nominal_amount;
            } elseif ($request->type == 'paket_internet') {
                $providerModel = PulsaProvider::find($request->provider_id);
                if (!$providerModel) {
                    return redirect('/pulsa-token')->with('error', 'Provider paket internet tidak valid');
                }

                if (empty($packageName)) {
                    return redirect('/pulsa-token')->with('error', 'Nama paket internet harus diisi');
                }

                $price = $request->price ?? 0;
                $amount = $packageName;
                $provider = $providerModel->name;
            }

            if (empty($price) || $price == 0) {
                return redirect('/pulsa-token')->with('error', 'Harga tidak boleh kosong');
            }

            if (empty($amount)) {
                return redirect('/pulsa-token')->with('error', 'Nominal / deskripsi paket harus diisi');
            }

            PulsaToken::create([
                'customer_name' => $request->customer_name,
                'type' => $request->type,
                'provider' => $provider,
                'phone_number' => $request->phone_number ?? null,
                'token_number' => $request->token_number ?? null,
                'amount' => $amount,
                'price' => $price,
                'transaction_date' => now(),
            ]);

            return redirect('/pulsa-token')->with('success', 'Transaksi berhasil disimpan');
        } catch (\Exception $e) {
            return redirect('/pulsa-token')->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        if (auth()->user()->role !== 'karyawan') {
            abort(403);
        }

        $query = PulsaToken::query();

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->date) {
            $query->whereDate('transaction_date', $request->date);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $request->search . '%')
                  ->orWhere('token_number', 'like', '%' . $request->search . '%');
            });
        }

        $transactions = $query->latest()->get();

        return view('log_pulsa_token', compact('transactions'));
    }
}