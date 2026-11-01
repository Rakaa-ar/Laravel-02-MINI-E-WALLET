<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini E-Wallet | Raka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-bold mb-1 text-white">Overview Keuangan</h3>
                <p class="text-secondary mb-0">Halo {{ Auth::user()->name ?? 'Raka' }}, kelola saldomu di sini.</p>
            </div>

            <!-- Wrapper buat tombol Logout & Avatar -->
            <div class="d-flex align-items-center gap-3">

                <!-- Tombol Logout -->
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit"
                        class="btn btn-outline-danger btn-sm rounded-pill px-3 d-flex align-items-center gap-2 fw-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                            <path fill-rule="evenodd"
                                d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                        </svg>
                        Logout
                    </button>
                </form>

                <div class="bg-white p-1 rounded-circle shadow-sm d-flex">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Raka Pradiva') }}&background=6f42c1&color=fff"
                        alt="Profile" class="rounded-circle" width="48" height="48">
                </div>

            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card wallet-card p-4 mb-4">
                    <p class="mb-2 opacity-75 small fw-semibold">TOTAL SALDO</p>
                    <h2 class="fw-bold mb-4">Rp {{ number_format($dompet->saldo, 0, ',', '.') }}</h2>
                    <div class="d-flex">
                        <span class="badge bg-white bg-opacity-25 text-white px-3 py-2 rounded-pill">
                            <i class="bi bi-wallet2 me-1"></i> ID Dompet: #{{ $dompet->id }}
                        </span>
                    </div>
                </div>

                <div class="card card-custom p-4">
                    <h5 class="fw-bold mb-4">Buat Transaksi</h5>
                    <form action="/dompet/transaksi" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">JENIS TRANSAKSI</label>
                            <select class="form-select" name="jenis" required>
                                <option value="masuk">Top-Up Saldo</option>
                                <option value="keluar">Tarik Tunai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">NOMINAL (RP)</label>
                            <input type="text" id="jumlah_formatted" class="form-control" name="jumlah"
                                min="1000" placeholder="Contoh 50.000" required>
                            <input type="hidden" name="jumlah" id="jumlah">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-semibold">KETERANGAN</label>
                            <input type="text" class="form-control" name="keterangan"
                                placeholder="Contoh: Jajan siomay">
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send-fill me-2"></i>Proses
                            Sekarang</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-custom p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Riwayat Transaksi</h5>
                    </div>

                    @if (session('success'))
                        <div id="alert-box" class="alert alert-success alert-dismissible fade show border-0 bg-masuk"
                            role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div id="alert-box" class="alert alert-danger alert-dismissible fade show border-0 bg-keluar"
                            role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        </div>
                    @endif
                    <script>
                        setTimeout(function() {
                            let alertBox = document.getElementById('alert-box');
                            if (alertBox) {
                                // Efek fade-out pelan-pelan atau langsung hilangkan
                                alertBox.style.transition = "opacity 0.5s ease";
                                alertBox.style.opacity = "0";
                                setTimeout(function() {
                                    alertBox.remove();
                                }, 500); // Hapus elemen dari HTML setelah transisi selesai
                            }
                        }, 3000); // Waktu 3 detik
                    </script>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr class="text-uppercase">
                                    <th>Detail Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th class="text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $item)
                                    <tr class="border-bottom">
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div
                                                    class="icon-box {{ $item->jenis == 'masuk' ? 'bg-masuk' : 'bg-keluar' }}">
                                                    <i
                                                        class="bi {{ $item->jenis == 'masuk' ? 'bi-arrow-down-left' : 'bi-arrow-up-right' }}"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold text-dark">
                                                        {{ $item->keterangan ?? 'Transaksi Sistem' }}</h6>
                                                    <small class="text-muted">{{ ucfirst($item->jenis) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted small fw-medium">
                                            {{ $item->created_at->format('d M Y, H:i') }}</td>
                                        <td><span
                                                class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Selesai</span>
                                        </td>
                                        <td
                                            class="text-end fw-bold {{ $item->jenis == 'masuk' ? 'text-success' : 'text-dark' }}">
                                            {{ $item->jenis == 'masuk' ? '+' : '-' }} Rp
                                            {{ number_format($item->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i class="bi bi-inbox fs-1 d-block mb-2 text-light"></i>
                                            Belum ada riwayat transaksi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4 w-100">
                            {{ $riwayat->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<script>
    const inputFormatted = document.getElementById('jumlah_formatted');
    const inputHidden = document.getElementById('jumlah');

    inputFormatted.addEventListener('keyup', function(e) {
        let angka = this.value.replace(/[^,\d]/g, '');
        let split = angka.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        this.value = rupiah;
        inputHidden.value = angka;
    });
</script>
