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
                <button type="button"
                    class="btn btn-outline-info w-100 mb-3 py-2 fw-bold d-flex align-items-center justify-content-center gap-2 rounded-3"
                    data-bs-toggle="modal" data-bs-target="#modalTransfer">
                    <i class="bi bi-arrow-left-right fs-5"></i> Transfer Saldo
                </button>
                <div class="card wallet-card p-4 mb-4">
                    <p class="mb-2 opacity-75 small fw-semibold">TOTAL SALDO</p>
                    <h2 class="fw-bold mb-4">Rp {{ number_format($dompet->saldo, 0, ',', '.') }}</h2>
                    <div class="d-flex">
                        <span class="badge bg-white bg-opacity-25 text-white px-3 py-2 rounded-pill">
                            <i class="bi bi-person-badge me-1"></i> ID User:
                            {{ Auth::user()->account_number ?? '------' }}
                        </span>
                    </div>
                </div>

                <div class="card card-custom p-4">
                    <h5 class="fw-bold mb-4">Buat Transaksi</h5>

                    <!-- ID form ditambahkan -->
                    <form action="/dompet/transaksi" method="POST" id="formTransaksi">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">JENIS TRANSAKSI</label>
                            <select class="form-select" name="jenis" required>
                                <option value="masuk">Top-Up Saldo</option>
                                <option value="keluar">Tarik Tunai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_formatted" class="form-label text-muted small fw-semibold">NOMINAL
                                (RP)</label>

                            <!-- Input tampilan (NAMA DIHAPUS BIAR GAK BENTROK) -->
                            <input type="text" id="jumlah_formatted" class="form-control" placeholder="Contoh 50.000"
                                required autocomplete="off">

                            <!-- Input hidden (HANYA INI YANG DIKIRIM KE LARAVEL) -->
                            <input type="hidden" name="jumlah" id="jumlah" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-semibold">KETERANGAN</label>
                            <input type="text" class="form-control" name="keterangan"
                                placeholder="Contoh: Jajan siomay">
                        </div>

                        <!-- ID tombol ditambahkan -->
                        <button type="submit" id="btnProses" class="btn btn-primary w-100">
                            <i class="bi bi-send-fill me-2"></i>Proses Sekarang
                        </button>
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
                                                <div class="icon-box {{ $item->jenis == 'masuk' ? 'bg-masuk' : 'bg-keluar' }} d-flex justify-content-center align-items-center rounded-2"
                                                    style="width: 42px; height: 42px; flex-shrink: 0;">
                                                    <i
                                                        class="bi {{ $item->jenis == 'masuk' ? 'bi-arrow-down-left' : 'bi-arrow-up-right' }} fs-5"></i>
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
                                <!-- Modal Transfer 6 Digit ID -->
                                <div class="modal fade" id="modalTransfer" tabindex="-1"
                                    aria-labelledby="modalTransferLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-white"
                                            style="background-color: #141724; border: 1px solid #202436; border-radius: 16px;">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold" id="modalTransferLabel">
                                                    <i class="bi bi-send-fill me-2 text-primary"></i>Transfer Saldo
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('transfer') }}" method="POST" id="formTransfer">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small fw-semibold">ID USER
                                                            PENERIMA (6 DIGIT)</label>
                                                        <input type="text" name="account_number"
                                                            class="form-control text-center fw-bold fs-4 letter-spacing-2"
                                                            maxlength="6" placeholder="Contoh: 345678" required
                                                            autocomplete="off">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small fw-semibold">NOMINAL
                                                            TRANSFER (RP)</label>
                                                        <input type="text" id="inputRupiah" class="form-control"
                                                            min="1000" placeholder="Minimal 1.000" required
                                                            autocomplete="off">

                                                        <input type="hidden" name="jumlah" id="inputJumlahAsli"
                                                            min="1000" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small fw-semibold">CATATAN
                                                            / KETERANGAN</label>
                                                        <input type="text" name="keterangan" class="form-control"
                                                            placeholder="Contoh: Bayar jajan siomay">
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-secondary rounded-3"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" id="btnProsesTransfer"
                                                        class="btn btn-primary rounded-3 px-4">Kirim Sekarang</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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

    <!-- SCRIPT FORMATTING RUPIAH & ANTI DOUBLE CLICK -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formTransaksi');
            const btn = document.getElementById('btnProses');
            const inputFormatted = document.getElementById('jumlah_formatted');
            const inputHidden = document.getElementById('jumlah');
            const alertBox = document.getElementById('alert-box');

            // Auto-hide alert notification
            if (alertBox) {
                setTimeout(function() {
                    alertBox.style.transition = "opacity 0.5s ease";
                    alertBox.style.opacity = "0";
                    setTimeout(function() {
                        alertBox.remove();
                    }, 500);
                }, 3000);
            }

            // Real-time format mata uang Rupiah
            function updateJumlah() {
                let angka = inputFormatted.value.replace(/[^,\d]/g, '');
                let split = angka.split(',');
                let sisa = split[0].length % 3;
                let rupiah = split[0].substr(0, sisa);
                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                inputFormatted.value = rupiah;

                // Isi nilai angka murni ke input hidden
                inputHidden.value = split[0];
            }

            inputFormatted.addEventListener('input', updateJumlah);
            inputFormatted.addEventListener('keyup', updateJumlah);

            // Handler submit form & Anti Double-Click
            form.addEventListener('submit', function(e) {
                updateJumlah();

                if (!inputHidden.value || parseInt(inputHidden.value) < 1000) {
                    e.preventDefault();
                    alert('Nominal minimal transaksi adalah Rp 1.000');
                    return false;
                }

                // Beri jeda 10ms agar browser sempat mengantrekan request POST
                setTimeout(function() {
                    btn.disabled = true;
                    btn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
                }, 10);
            });
        });

        document.getElementById('formTransfer').addEventListener('submit', function() {
            const btn = document.getElementById('btnProsesTransfer');
            setTimeout(function() {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
            }, 10);
        });
        const inputRupiah = document.getElementById('inputRupiah');
        const inputJumlahAsli = document.getElementById('inputJumlahAsli');

        inputRupiah.addEventListener('input', function(e) {
            // Hapus semua karakter selain angka
            let angkaMurni = this.value.replace(/[^0-9]/g, '');

            // Simpan angka murni ke input hidden untuk dikirim ke Controller
            inputJumlahAsli.value = angkaMurni;

            // Format angka murninya jadi format ribuan Indonesia 
            if (angkaMurni !== '') {
                this.value = new Intl.NumberFormat('id-ID').format(angkaMurni);
            } else {
                this.value = '';
            }
        });
    </script>
</body>

</html>
