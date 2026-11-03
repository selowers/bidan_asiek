@extends('layouts.app')
@section('title', 'Metode Pembayaran')
@section('content')
<div class="store-page">
    <div class="store-heading">
        <span class="store-kicker"><i class="bi bi-wallet2"></i> Admin</span>
        <h1>Kelola Metode Pembayaran</h1>
        <p>Atur rekening transfer dan QRIS yang tersedia untuk pasien saat checkout.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card store-admin-orders">
                <h2 class="h5 mb-3">Tambah metode</h2>

                <form method="POST" action="{{ route('payment-methods.store') }}" enctype="multipart/form-data" class="row g-3" id="payment-method-form">
                    @csrf
                    <input type="hidden" name="name" id="payment-method-name" value="QRIS">

                    <div class="col-12">
                        <label class="form-label">Tipe</label>
                        <select name="type" class="form-select" id="payment-method-type" required>
                            <option value="bank">Bank transfer</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <div class="col-12" id="bank-name-field">
                        <label class="form-label">Nama bank</label>
                        <input type="text" name="bank_name" class="form-control" placeholder="Bank Central Asia">
                    </div>

                    <div class="col-12" id="account-name-field">
                        <label class="form-label">Nama penerima</label>
                        <input type="text" name="account_name" class="form-control" placeholder="Bidan Asiek" required>
                    </div>

                    <div class="col-12" id="account-number-field">
                        <label class="form-label">Nomor rekening</label>
                        <input type="text" name="account_number" class="form-control" placeholder="1234567890">
                    </div>

                    <div class="col-12 d-none" id="qris-upload-field">
                        <label class="form-label">QRIS image</label>
                        <input type="file" name="qr_code" class="form-control" accept="image/*">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Status</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                            <label class="form-check-label">Aktif</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn store-primary w-100">Simpan metode</button>
                    </div>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const typeSelect = document.getElementById('payment-method-type');
                        const nameInput = document.getElementById('payment-method-name');
                        const qrisField = document.getElementById('qris-upload-field');
                        const bankNameField = document.getElementById('bank-name-field');
                        const accountNumberField = document.getElementById('account-number-field');

                        function updatePaymentForm() {
                            const type = typeSelect.value;
                            const bankName = document.querySelector('input[name="bank_name"]')?.value || '';

                            if (type === 'qris') {
                                nameInput.value = 'QRIS';
                                qrisField.classList.remove('d-none');
                                bankNameField.classList.add('d-none');
                                accountNumberField.classList.add('d-none');
                            } else {
                                nameInput.value = bankName ? 'Transfer ' + bankName : 'Bank Transfer';
                                qrisField.classList.add('d-none');
                                bankNameField.classList.remove('d-none');
                                accountNumberField.classList.remove('d-none');
                            }
                        }

                        typeSelect.addEventListener('change', updatePaymentForm);
                        document.querySelector('input[name="bank_name"]').addEventListener('input', function () {
                            if (typeSelect.value === 'bank') {
                                nameInput.value = this.value ? 'Transfer ' + this.value : 'Bank Transfer';
                            }
                        });

                        updatePaymentForm();
                    });
                </script>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card store-admin-orders">
                <h2 class="h5 mb-3">Daftar metode</h2>

                @if ($paymentMethods->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Tipe</th>
                                    <th>Detail</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paymentMethods as $paymentMethod)
                                    <tr>
                                        <td>{{ $paymentMethod->type === 'bank' ? 'Bank transfer' : 'QRIS' }}</td>
                                        <td>
                                            @if ($paymentMethod->type === 'bank')
                                                {{ $paymentMethod->bank_name ?? '-' }}<br>
                                                <small class="text-muted">{{ $paymentMethod->account_name }} / {{ $paymentMethod->account_number }}</small>
                                            @else
                                                {{ $paymentMethod->account_name }}
                                                @if ($paymentMethod->qr_code_path)
                                                    <br><small class="text-muted">QR tersedia</small>
                                                @endif
                                            @endif
                                        </td>
                                        <td><span class="store-order-status store-order-status-{{ $paymentMethod->is_active ? 'paid' : 'pending' }}">{{ $paymentMethod->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                                        <td class="text-end">
                                            <form method="POST" action="{{ route('payment-methods.destroy', $paymentMethod) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Belum ada metode pembayaran yang disimpan.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
