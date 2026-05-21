@extends('layouts.app')

@section('title', 'Transaksi Pulsa & Token')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-primary py-4 px-4 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-white mb-1">Transaksi Pulsa & Token</h4>
                        <p class="text-white-50 mb-0">Isi pulsa, paket internet, atau token listrik dengan cepat.</p>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/pulsa-token') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark small text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">📋 Informasi Pelanggan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-primary"></i></span>
                                <input type="text" name="customer_name" class="form-control custom-input border-start-0" placeholder="Masukkan nama pelanggan" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark small text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">🎯 Pilih Layanan</label>
                            <div class="row g-3">
                                <div class="col-12">
                                    <select name="type" id="type" class="form-select custom-input py-3 shadow-none" onchange="changeType()" required>
                                        <option value="" disabled selected> Pilih Tipe Transaksi </option>
                                        <option value="pulsa">📱 Pulsa Seluler</option>
                                        <option value="paket_internet">🌐 Paket Internet</option>
                                        <option value="token_listrik">⚡ Token Listrik (PLN)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="dynamic-content">
                            <div id="pulsa" class="p-3 border rounded-4 bg-light-subtle mb-4 shadow-sm" style="display:none; border-style: dashed !important;">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-phone me-2"></i>Detail Pulsa</h6>
                                <div class="row g-2">
                                    <div class="col-md-6 mb-2">
                                        <select id="pulsa_provider" name="provider_id" class="form-select custom-input" onchange="loadPulsaNominals()">
                                            <option value="">Pilih Provider</option>
                                            @foreach($providers as $provider)
                                                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="text" name="phone_number" class="form-control custom-input" placeholder="No Handphone">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <select id="pulsa_nominal" name="pulsa_nominal_id" class="form-select custom-input" onchange="updatePulsaPrice()">
                                            <option value="">Pilih Nominal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="text" id="pulsa_price" class="form-control custom-input bg-white" placeholder="Harga Jual" readonly>
                                        <input type="hidden" name="amount" id="pulsa_amount">
                                    </div>
                                </div>
                            </div>

                            <div id="paket" class="p-3 border rounded-4 bg-light-subtle mb-4 shadow-sm" style="display:none; border-style: dashed !important;">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-globe me-2"></i>Detail Paket Internet</h6>
                                <div class="row g-2">
                                    <div class="col-md-6 mb-2">
                                        <select name="provider_id" class="form-select custom-input">
                                            <option value="">Pilih Provider</option>
                                            @foreach($providers as $provider)
                                                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="text" name="phone_number" class="form-control custom-input" placeholder="No Handphone">
                                    </div>
                                    <div class="col-12 mb-2">
                                        <input type="text" name="package_name" class="form-control custom-input" placeholder="Nama Paket (Contoh: 15GB + 5GB Chat)">
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">Rp</span>
                                            <input type="number" name="price" class="form-control custom-input" placeholder="Harga Jual">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="token" class="p-3 border rounded-4 bg-light-subtle mb-4 shadow-sm" style="display:none; border-style: dashed !important;">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-lightning-charge me-2"></i>Detail Token Listrik</h6>
                                <div class="row g-2">
                                    <div class="col-md-6 mb-2">
                                        <input type="text" name="token_number" class="form-control custom-input" placeholder="Meter/No Token">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <select id="token_nominal" name="token_nominal_id" class="form-select custom-input" onchange="updateTokenPrice()">
                                            <option value="">Pilih Nominal</option>
                                            @foreach($tokenNominals as $nominal)
                                                <option value="{{ $nominal->id }}" data-price="{{ $nominal->harga_final + $nominal->profit }}" data-amount="{{ $nominal->nominal_amount }}">
                                                    Rp {{ number_format($nominal->nominal_amount, 0, ',', '.') }} - Rp {{ number_format($nominal->harga_final + $nominal->profit, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <input type="text" id="token_price" class="form-control custom-input bg-white" placeholder="Total Bayar" readonly>
                                        <input type="hidden" name="amount" id="token_amount">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <button type="reset" class="btn btn-light px-4 fw-medium" style="border-radius: 8px;">Reset</button>
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm" style="border-radius: 8px;">
                                <i class="bi bi-check-circle me-2"></i>Simpan Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-input {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        font-size: 0.95rem;
    }
    .custom-input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.1);
    }
    .input-group-text {
        border-radius: 10px 0 0 10px;
        border: 1px solid #e0e0e0;
        background-color: #f8f9fa;
    }
    .bg-light-subtle {
        background-color: #f8f9fa !important;
    }
    .form-label {
        color: #2c3e50 !important;
        font-weight: 600;
        margin-bottom: 0.75rem;
        letter-spacing: 0.3px;
        display: block;
    }
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
</style>

<script>
    function changeType() {
        let type = document.getElementById('type').value;
        document.getElementById('pulsa').style.display = 'none';
        document.getElementById('paket').style.display = 'none';
        document.getElementById('token').style.display = 'none';

        document.querySelectorAll('#pulsa [name], #paket [name], #token [name]').forEach(el => {
            el.required = false;
            el.disabled = true;
        });

        if (type === 'pulsa') {
            document.getElementById('pulsa').style.display = 'block';
            document.getElementById('pulsa').querySelectorAll('[name]').forEach(el => el.disabled = false);
            document.getElementById('pulsa_provider').required = true;
            document.querySelector('#pulsa [name="phone_number"]').required = true;
            document.getElementById('pulsa_nominal').required = true;
        } else if (type === 'paket_internet') {
            document.getElementById('paket').style.display = 'block';
            document.getElementById('paket').querySelectorAll('[name]').forEach(el => el.disabled = false);
            document.querySelector('#paket [name="provider_id"]').required = true;
            document.querySelector('#paket [name="phone_number"]').required = true;
            document.querySelector('#paket [name="package_name"]').required = true;
            document.querySelector('#paket [name="price"]').required = true;
        } else if (type === 'token_listrik') {
            document.getElementById('token').style.display = 'block';
            document.getElementById('token').querySelectorAll('[name]').forEach(el => el.disabled = false);
            document.querySelector('#token [name="token_number"]').required = true;
            document.getElementById('token_nominal').required = true;
        }
    }

    function loadPulsaNominals() {
        let providerId = document.getElementById('pulsa_provider').value;
        let providers = @json($providers);
        let provider = providers.find(p => p.id == providerId);
        let select = document.getElementById('pulsa_nominal');
        
        select.innerHTML = '<option value="">Pilih Nominal</option>';
        
        if (provider && provider.nominals) {
            provider.nominals.forEach(nominal => {
                let option = document.createElement('option');
                option.value = nominal.id;
                let totalPrice = (nominal.nominal_amount * 1000) + nominal.markup;
                option.textContent = `Rp ${(nominal.nominal_amount * 1000).toLocaleString('id-ID')} + Rp ${nominal.markup.toLocaleString('id-ID')} = Rp ${totalPrice.toLocaleString('id-ID')}`;
                option.dataset.price = totalPrice;
                option.dataset.amount = nominal.nominal_amount + 'k';
                select.appendChild(option);
            });
        }
    }

    function updatePulsaPrice() {
        let select = document.getElementById('pulsa_nominal');
        let selected = select.options[select.selectedIndex];
        let price = selected.dataset.price || 0;
        let amount = selected.dataset.amount || '';
        document.getElementById('pulsa_price').value = 'Rp ' + parseInt(price).toLocaleString('id-ID');
        document.getElementById('pulsa_amount').value = amount;
    }

    function updateTokenPrice() {
        let select = document.getElementById('token_nominal');
        let selected = select.options[select.selectedIndex];
        let price = selected.dataset.price || 0;
        let amount = selected.dataset.amount || '';
        document.getElementById('token_price').value = 'Rp ' + parseInt(price).toLocaleString('id-ID');
        document.getElementById('token_amount').value = amount;
    }
</script>
@endsection