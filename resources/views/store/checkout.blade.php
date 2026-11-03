@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<div class="store-page store-narrow">
    <div class="store-heading">
        <span class="store-kicker"><i class="bi bi-credit-card"></i> Checkout</span>
        <h1>Pilih metode pembayaran</h1>
        <p>Silakan pilih rekening transfer atau QRIS yang tersedia dari admin.</p>
    </div>

    <div class="card store-cart-card">
        <div class="store-cart-total mb-4">
            <span>Total pesanan</span>
            <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
        </div>

        <form method="POST" action="{{ route('orders.store') }}" enctype="multipart/form-data">
            @csrf

            @if ($paymentMethods->isEmpty())
                <div class="alert alert-warning">
                    Belum ada metode pembayaran aktif dari admin saat iniii.
                </div>
            @else
                <div class="row g-3">
                    @foreach ($paymentMethods as $method)
                        <div class="col-12">
                            <label class="d-block border rounded-4 p-3 cursor-pointer">
                                <input
                                    type="radio"
                                    name="payment_method_id"
                                    value="{{ $method->id }}"
                                    data-type="{{ $method->type }}"
                                    data-bank-name="{{ $method->bank_name ?? '' }}"
                                    data-account-name="{{ $method->account_name ?? '' }}"
                                    data-account-number="{{ $method->account_number ?? '' }}"
                                    data-qr-url="{{ $method->qr_code_path ? asset('storage/'.$method->qr_code_path) : '' }}"
                                    required>
                                <div class="d-flex justify-content-between align-items-start gap-3 mt-2">
                                    <div>
                                        <div class="fw-semibold">{{ $method->type === 'bank' ? ($method->bank_name ?? 'Transfer Bank') : 'QRIS' }}</div>
                                        @if ($method->type === 'bank')
                                            <small class="text-muted d-block">{{ $method->bank_name ?? 'Bank transfer' }}</small>
                                            <small class="text-muted d-block">{{ $method->account_name }} • {{ $method->account_number }}</small>
                                        @else
                                            <small class="text-muted d-block">{{ $method->account_name }}</small>
                                            @if ($method->qr_code_path)
                                                <small class="text-muted d-block">QR tersedia</small>
                                            @endif
                                        @endif
                                    </div>
                                    <span class="badge bg-light text-dark text-uppercase">{{ $method->type }}</span>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div id="payment-details" class="alert alert-light border mt-4 d-none">
                    <div id="bank-detail" class="d-none">
                        <div class="fw-semibold mb-2">Detail rekening</div>
                        <div class="mb-1"><strong>Bank:</strong> <span id="detail-bank-name">-</span></div>
                        <div class="mb-1"><strong>Nama pemilik:</strong> <span id="detail-account-name">-</span></div>
                        <div><strong>No rekening:</strong> <span id="detail-account-number">-</span></div>
                    </div>

                    <div id="qris-detail" class="d-none text-center d-flex flex-column align-items-center justify-content-center">
                        <div class="fw-semibold mb-3">Kode QRIS</div>
                        <img id="detail-qris-image" src="" alt="QRIS payment" class="img-fluid rounded border mx-auto" style="max-width: 230px; display: block;">
                    </div>
                </div>

                <div class="mt-4">
                    <label for="payment_proof" class="form-label">Bukti pembayaran</label>
                    <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept="image/*" required>
                    <small class="text-muted">Upload bukti pembayaran yang sudah Anda lakukan agar pesanan bisa dibuat.</small>
                </div>
            @endif

            <div class="text-end mt-4">
                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary me-2">Kembali</a>
                <button type="submit" id="create-order-btn" class="btn store-primary" @disabled($paymentMethods->isEmpty())>
                    <i class="bi bi-check-circle me-2"></i>Buat Pesanan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radioButtons = document.querySelectorAll('input[name="payment_method_id"]');
        const paymentDetails = document.getElementById('payment-details');
        const bankDetail = document.getElementById('bank-detail');
        const qrisDetail = document.getElementById('qris-detail');
        const bankName = document.getElementById('detail-bank-name');
        const accountName = document.getElementById('detail-account-name');
        const accountNumber = document.getElementById('detail-account-number');
        const qrisImage = document.getElementById('detail-qris-image');
        const proofInput = document.getElementById('payment_proof');
        const createOrderBtn = document.getElementById('create-order-btn');

        function updateSubmitState() {
            const hasMethod = Array.from(radioButtons).some((radio) => radio.checked);
            const hasProof = Boolean(proofInput && proofInput.files && proofInput.files.length > 0);

            if (createOrderBtn) {
                createOrderBtn.disabled = !hasMethod || !hasProof;
            }
        }

        function updatePaymentDetails() {
            const checked = document.querySelector('input[name="payment_method_id"]:checked');

            if (!checked) {
                paymentDetails.classList.add('d-none');
                bankDetail.classList.add('d-none');
                qrisDetail.classList.add('d-none');
                return;
            }

            paymentDetails.classList.remove('d-none');

            const type = checked.dataset.type;

            if (type === 'bank') {
                bankDetail.classList.remove('d-none');
                qrisDetail.classList.add('d-none');
                bankName.textContent = checked.dataset.bankName || '-';
                accountName.textContent = checked.dataset.accountName || '-';
                accountNumber.textContent = checked.dataset.accountNumber || '-';
                return;
            }

            bankDetail.classList.add('d-none');
            qrisDetail.classList.remove('d-none');
            const qrUrl = checked.dataset.qrUrl || '';
            qrisImage.src = qrUrl;
            qrisImage.alt = 'QRIS payment';
            if (!qrUrl) {
                qrisImage.style.display = 'none';
            } else {
                qrisImage.style.display = 'block';
            }
        }

        radioButtons.forEach((radio) => {
            radio.addEventListener('change', function () {
                updatePaymentDetails();
                updateSubmitState();
            });
        });

        if (proofInput) {
            proofInput.addEventListener('change', updateSubmitState);
        }

        updatePaymentDetails();
        updateSubmitState();
    });
</script>
@endsection
