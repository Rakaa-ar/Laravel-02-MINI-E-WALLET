<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="background-color: #0d0f17; color: #ffffff; font-family: 'Segoe UI', Arial, sans-serif; padding: 30px 10px;">
    <div style="max-width: 420px; margin: 0 auto; background-color: #141724; border: 1px solid #202436; border-radius: 12px; padding: 30px; text-align: center;">
        <h2 style="color: #ffffff; font-size: 22px; font-weight: bold; margin-bottom: 8px;">Reset Password</h2>
        <p style="color: #94a3b8; font-size: 14px; margin-bottom: 24px;">Ini OTP buat reset password lu:</p>
        <div style="background-color: #1a1e2e; border: 2px dashed #7257e7; border-radius: 10px; padding: 16px; margin-bottom: 24px;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 6px; color: #7257e7;">{{ $otpCode }}</span>
        </div>
        <p style="color: #64748b; font-size: 12px;">Berlaku cuma 5 menit ya.</p>
    </div>
</body>
</html>