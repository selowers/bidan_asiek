@if ($isAdmin)
    @if ($appointment->status === 'scheduled')
        <form class="d-inline" method="POST" action="{{ route('appointments.status', $appointment) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="confirmed">
            <button class="btn btn-sm btn-success" title="Konfirmasi janji"><i class="bi bi-check2 me-1"></i>Konfirmasi</button>
        </form>
        <form class="d-inline" method="POST" action="{{ route('appointments.status', $appointment) }}" onsubmit="return confirm('Tolak pengajuan janji ini?')">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="rejected">
            <button class="btn btn-sm btn-outline-danger" title="Tolak pengajuan" aria-label="Tolak pengajuan"><i class="bi bi-x-lg"></i></button>
        </form>
    @elseif ($appointment->status === 'confirmed')
        <form class="d-inline" method="POST" action="{{ route('appointments.status', $appointment) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="completed">
            <button class="btn btn-sm btn-outline-success" title="Tandai selesai" aria-label="Tandai selesai"><i class="bi bi-check2-all"></i></button>
        </form>
        <form class="d-inline" method="POST" action="{{ route('appointments.status', $appointment) }}" onsubmit="return confirm('Batalkan jadwal yang sudah dikonfirmasi?')">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="cancelled">
            <button class="btn btn-sm btn-outline-danger" title="Batalkan janji" aria-label="Batalkan janji"><i class="bi bi-x-lg"></i></button>
        </form>
    @endif
@elseif ($appointment->canPatientCancel())
    <form class="d-inline" method="POST" action="{{ route('appointments.cancel', $appointment) }}" onsubmit="return confirm('Batalkan janji temu ini?')">
        @csrf
        @method('PATCH')
        <button class="appointment-action appointment-action-delete" title="Batalkan janji"><i class="bi bi-x-lg"></i></button>
    </form>
@endif