<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body
    style="background-color: #0d0f17; color: #ffffff; font-family: 'Segoe UI', Arial, sans-serif; padding: 30px 10px; margin: 0;">
    <div
        style="max-width: 420px; margin: 0 auto; background-color: #141724; border: 1px solid #202436; border-radius: 12px; padding: 30px; text-align: center;">

        <h2 style="color: #ffffff; font-size: 22px; font-weight: bold; margin-top: 0; margin-bottom: 8px;">Reset Password
        </h2>
        <p style="color: #94a3b8; font-size: 14px; margin-bottom: 24px; line-height: 1.5;">
            Lu baru aja minta buat reset password akun Mini E-Wallet.<br>
            Klik tombol ungu di bawah ini buat bikin password baru:
        </p>

        <!-- Tombol Link Reset -->
        <div style="margin-bottom: 30px;">
            <a href="{{ url('/reset-password/' . $token . '?email=' . urlencode($email)) }}"
                style="background-color: #7257e7; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">
                Reset Password Sekarang
            </a>
        </div>

        <p style="color: #64748b; font-size: 12px; margin: 0; line-height: 1.4;">
            Kalau lu nggak ngerasa minta reset password, abaikan aja email ini.<br>
            Link ini cuma berlaku selama 60 menit.
        </p>

    </div>
</body>

</html>
