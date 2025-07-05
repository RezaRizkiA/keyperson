{{-- @php
    $person = $isExpert ? $appointment->user : optional($appointment->expert)->user;
    $badgeClasses = [
        'need_confirmation' => 'text-bg-warning',
        'progress' => 'text-bg-primary',
        'payment' => 'text-bg-danger',
        'completed' => 'text-bg-success',
    ];
    $badgeClass = $badgeClasses[$appointment->status] ?? 'text-bg-secondary';
@endphp

<div class="chat-list chat {{ $active ? 'active-chat' : '' }}" data-user-id="{{ $index }}">
    <div class="hstack align-items-start mb-7 pb-1 align-items-center justify-content-between flex-wrap gap-6">
        <div class="d-flex align-items-center gap-2">
            <img src=""
                alt="{{ $person->name ?? 'User' }}" width="48" height="48" class="rounded-circle">
            <div>
                <h6 class="fw-semibold mb-0">{{ $person->name ?? '-' }}</h6>
                <p class="mb-0">{{ $person->email ?? '-' }}</p>
            </div>
        </div>
        <span class="badge {{ $badgeClass }}">
            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
        </span>
    </div>
    <div class="border-bottom pb-7 mb-7">
        <p class="mb-3 text-dark">{{ $appointment->appointment }}</p>

        <div class="d-flex justify-content-end gap-2">
            @if ($isExpert)
                @if ($appointment->status == 'need_confirmation')
                    <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="progress">
                        <button type="submit" class="btn btn-rounded btn-primary">Confirm</button>
                    </form>
                    <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="declined">
                        <button type="submit" class="btn btn-rounded btn-danger">Decline</button>
                    </form>
                @elseif ($appointment->status == 'progress')
                    <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-rounded btn-success">Mark as Completed</button>
                    </form>
                @endif
            @else
                @if (in_array($appointment->status, ['need_confirmation', 'progress']))
                    <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="canceled">
                        <button type="submit" class="btn btn-rounded btn-warning">Cancel</button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</div> --}}

@php
    // Ambil data person (konselor/user) berdasarkan peran
    $person = $isExpert ? $appointment->user : optional($appointment->expert)->user;
    $dt = \Carbon\Carbon::parse($appointment->date_time); // Parse tanggal dan waktu

    // Kelas badge untuk status
    $badgeClasses = [
        'need_confirmation' => 'bg-warning-subtle text-warning', // Lebih lembut
        'progress' => 'bg-primary-subtle text-primary',       // Lebih lembut
        'payment' => 'bg-danger-subtle text-danger',         // Lebih lembut
        'completed' => 'bg-success-subtle text-success',     // Lebih lembut
        'declined' => 'bg-secondary-subtle text-secondary',  // Tambahan untuk declined
        'canceled' => 'bg-dark-subtle text-dark',            // Tambahan untuk canceled
    ];
    $badgeClass = $badgeClasses[$appointment->status] ?? 'bg-secondary-subtle text-secondary';

    // Mendapatkan URL avatar (contoh, sesuaikan dengan logic aplikasi Anda)
    $avatarUrl = $person->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($person->name ?? 'N A') . '&background=random&color=fff&size=128';

    // Teks untuk metode konseling (sesuaikan dengan data appointment Anda)
    $counselingMethod = $appointment->method ?? 'Online (Video Call)'; // Asumsi ada field 'method'

    // Link akses ruang konseling (contoh, sesuaikan)
    $meetingLink = '#'; // Ganti dengan link meeting asli jika ada
    $showMeetingLink = ($appointment->status == 'progress' && $dt->isFuture()); // Hanya tampil jika status progress dan belum lewat waktu
@endphp

<div class="chat-list chat {{ $active ? 'active-chat' : '' }}" data-user-id="{{ $index }}">
    {{-- Header Detail Appointment --}}
    <div class="hstack align-items-start mb-7 pb-1 align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ $avatarUrl }}" alt="{{ $person->name ?? 'User' }}" width="64" height="64" class="rounded-circle shadow-sm">
            <div>
                <h4 class="fw-semibold mb-0">{{ $person->name ?? '-' }}</h4>
                <p class="mb-0 text-muted fs-3">{{ $person->email ?? '-' }}</p>
            </div>
        </div>
        {{-- Status Badge di pojok kanan atas --}}
        <span class="badge {{ $badgeClass }} fs-3 py-2 px-3 rounded-pill">
            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
        </span>
    </div>

    {{-- Detail Penting Appointment --}}
    <div class="appointment-details-section mb-7 border-bottom pb-7">
        <h2 class="fw-bold mb-4 text-dark">{{ $appointment->appointment_topic ?? 'Tanpa Topik' }}</h2>

        <div class="row g-3 mb-5">
            <div class="col-md-6 col-lg-4">
                <p class="mb-1 fs-2 text-muted">Tanggal</p>
                <h6 class="fw-semibold mb-0 fs-4 d-flex align-items-center gap-2">
                    <i class="ti ti-calendar fs-5 text-primary"></i>
                    <span>{{ $dt->translatedFormat('l, d F Y') }}</span>
                </h6>
            </div>
            <div class="col-md-6 col-lg-4">
                <p class="mb-1 fs-2 text-muted">Waktu</p>
                <h6 class="fw-semibold mb-0 fs-4 d-flex align-items-center gap-2">
                    <i class="ti ti-clock fs-5 text-primary"></i>
                    <span>{{ $dt->format('H:i') }} WIB</span>
                </h6>
            </div>
            <div class="col-md-6 col-lg-4">
                <p class="mb-1 fs-2 text-muted">Metode Konseling</p>
                <h6 class="fw-semibold mb-0 fs-4 d-flex align-items-center gap-2">
                    @if (Str::contains(Str::lower($counselingMethod), 'online'))
                        <i class="ti ti-device-laptop fs-5 text-primary"></i>
                    @else
                        <i class="ti ti-map-pin fs-5 text-primary"></i>
                    @endif
                    <span>{{ $counselingMethod }}</span>
                </h6>
            </div>
            @if(isset($appointment->hours))
            <div class="col-md-6 col-lg-4">
                <p class="mb-1 fs-2 text-muted">Durasi</p>
                <h6 class="fw-semibold mb-0 fs-4 d-flex align-items-center gap-2">
                    <i class="ti ti-hourglass fs-5 text-primary"></i>
                    <span>{{ $appointment->hours }} jam</span>
                </h6>
            </div>
            @endif
            @if(isset($appointment->price))
            <div class="col-md-6 col-lg-4">
                <p class="mb-1 fs-2 text-muted">Harga</p>
                <h6 class="fw-semibold mb-0 fs-4 d-flex align-items-center gap-2">
                    <i class="ti ti-currency-dollar fs-5 text-primary"></i>
                    <span>Rp{{ number_format($appointment->price, 0, ',', '.') }}</span>
                </h6>
            </div>
            @endif
        </div>

        {{-- Deskripsi Appointment / Catatan --}}
        <p class="mb-2 fs-3 text-muted">Detail Masalah/Catatan</p>
        <p class="mb-3 text-dark fs-4">
            {{ $appointment->appointment_description ?? 'Tidak ada detail deskripsi yang diberikan.' }}
        </p>

        {{-- Link Akses Sesi (khusus online & status progress & belum lewat waktu) --}}
        @if ($showMeetingLink && $counselingMethod == 'Online (Video Call)')
            <div class="mt-4 pt-4 border-top">
                <a href="{{ $meetingLink }}" target="_blank" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-3">
                    <i class="ti ti-brand-zoom fs-5"></i>
                    <span>Gabung Sesi Konseling Sekarang</span>
                </a>
                <p class="mt-2 text-center text-muted fs-2">Link akan aktif saat waktu janji temu tiba.</p>
            </div>
        @endif
    </div>

    {{-- Tombol Aksi --}}
    <div class="d-flex align-items-center gap-3 flex-wrap">
        @if ($isExpert)
            @if ($appointment->status == 'need_confirmation')
                <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="progress">
                    <button type="submit" class="btn btn-success py-2 px-4 rounded-pill d-flex align-items-center gap-1">
                        <i class="ti ti-check fs-5"></i> Confirm
                    </button>
                </form>
                <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="declined">
                    <button type="submit" class="btn btn-danger py-2 px-4 rounded-pill d-flex align-items-center gap-1">
                        <i class="ti ti-x fs-5"></i> Decline
                    </button>
                </form>
            @elseif ($appointment->status == 'progress')
                <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn btn-info py-2 px-4 rounded-pill d-flex align-items-center gap-1">
                        <i class="ti ti-medal fs-5"></i> Mark as Completed
                    </button>
                </form>
            @endif
        @else {{-- Jika ini adalah user (anggota) --}}
            {{-- TOMBOL CANCEL DENGAN KONDISI BARU --}}
            @if (in_array($appointment->status, ['need_confirmation', 'payment']))
                <form action="{{ route('appointment.update_status', $appointment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="canceled">
                    <button type="submit" class="btn btn-warning py-2 px-4 rounded-pill d-flex align-items-center gap-1">
                        <i class="ti ti-alert-triangle fs-5"></i> Batalkan Janji Temu
                    </button>
                </form>
            @endif
            {{-- @if ($appointment->status == 'completed' && !$appointment->is_reviewed)
                <a href="{{ route('appointment.review', $appointment->id) }}" class="btn btn-primary py-2 px-4 rounded-pill d-flex align-items-center gap-1">
                    <i class="ti ti-star fs-5"></i> Beri Ulasan
                </a>
            @endif
            @if ($appointment->status == 'payment')
                <a href="{{ route('payment.pay', $appointment->id) }}" class="btn btn-primary py-2 px-4 rounded-pill d-flex align-items-center gap-1">
                    <i class="ti ti-credit-card fs-5"></i> Lanjutkan Pembayaran
                </a>
            @endif --}}
        @endif
        {{-- Tombol Edit (jika relevan) --}}
        {{-- Tombol edit ini akan muncul jika statusnya 'need_confirmation' atau 'progress' dan bukan expert --}}
        {{-- @if (!$isExpert && in_array($appointment->status, ['need_confirmation', 'progress']))
        <a href="{{ route('appointment.edit', $appointment->id) }}" class="btn btn-secondary py-2 px-4 rounded-pill d-flex align-items-center gap-1">
            <i class="ti ti-pencil fs-5"></i> Edit
        </a>
        @endif --}}
    </div>
</div>