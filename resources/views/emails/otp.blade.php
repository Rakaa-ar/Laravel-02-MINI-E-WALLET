<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="background-color: #0d0f17; color: #ffffff; font-family: 'Segoe UI', Arial, sans-serif; padding: 30px 10px; margin: 0;">
    <div style="max-width: 420px; margin: 0 auto; background-color: #141724; border: 1px solid #202436; border-radius: 12px; padding: 30px; text-align: center;">
        
        <h2 style="color: #ffffff; font-size: 22px; font-weight: bold; margin-top: 0; margin-bottom: 8px;">Verifikasi Akun</h2>
        <p style="color: #94a3b8; font-size: 14px; margin-bottom: 24px; line-height: 1.5;">
            Halo <strong style="color: #ffffff;">{{ $user->name }}</strong>,<br>
            Gunakan kode OTP di bawah ini untuk menyelesaikan pendaftaran akun Mini E-Wallet lu:
        </p>

        <!-- Box Kode OTP -->
        <div style="background-color: #1a1e2e; border: 2px dashed #7257e7; border-radius: 10px; padding: 16px; margin-bottom: 24px;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 6px; color: #7257e7; display: block;">{{ $otpCode }}</span>
        </div>

        <p style="color: #64748b; font-size: 12px; margin: 0; line-height: 1.4;">
            Kode ini berlaku selama <strong>5 menit</strong>.<br>
            Jangan bagikan kode rahasia ini kepada siapa pun.
        </p>
        
    </div>
</body>
</html>