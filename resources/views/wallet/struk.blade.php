<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk - {{ $transaksi->kode_transaksi }}</title>
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
    </style>
</head>
<body class="struk-body">
    <div class="struk-container">
        <div class="struk-header">
            <h2>E-WALLET KOPDES</h2>
            <p>Bukti Transaksi Digital</p>
        </div>

        <table class="struk-table">
            <tr>
                <td>Kode Transaksi</td>
                <td class="struk-text-right struk-fw-bold">{{ $transaksi->kode_transaksi }}</td>
            </tr>
            <tr>
                <td>Waktu Transaksi</td>
                <td class="struk-text-right">{{ $transaksi->created_at->format('d M Y, H:i:s') }}</td>
            </tr>
            <tr>
                <td>Jenis</td>
                <td class="struk-text-right struk-fw-bold">{{ strtoupper($transaksi->jenis) }}</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td class="struk-text-right">{{ $transaksi->keterangan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td class="struk-text-right struk-fw-bold struk-text-success">BERHASIL</td>
            </tr>
        </table>

        <div class="struk-total-section">
            <table class="struk-table">
                <tr>
                    <td class="struk-fw-bold">Nominal</td>
                    <td class="struk-text-right struk-fw-bold">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="struk-footer">
            <p>Terima kasih telah menggunakan layanan Mini E-Wallet.</p>
            <p><i>Struk ini dicetak otomatis oleh sistem dan sah sebagai bukti transaksi.</i></p>
        </div>
    </div>
</body>
</html>