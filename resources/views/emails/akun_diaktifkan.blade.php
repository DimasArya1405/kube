<!DOCTYPE html>
<html>
<head>
    <title>Akun Aktif</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="color: #2d3748;">Halo, {{ $user->nama }}!</h2>
        <p>Kami ingin menginformasikan bahwa pendaftaran akun Anda telah ditinjau dan berhasil diaktifkan oleh Admin.</p>
        
        <table style="width: 100%; margin: 20px 0; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px; font-weight: bold; width: 120px;">Email Anda:</td>
                <td style="padding: 8px;">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Role Akses:</td>
                <td style="padding: 8px;">{{ ucfirst($user->role) }}</td>
            </tr>
        </table>

        <p>Sekarang Anda sudah dapat masuk ke dalam sistem dan menggunakan seluruh fitur yang tersedia sesuai dengan hak akses Anda.</p>
        <div style="margin-top: 30px;">
            <a href="{{ url('/login') }}" style="background-color: #3182ce; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Login ke Sistem</a>
        </div>
        <hr style="border: none; border-top: 1px solid #eee; margin-top: 40px;">
    </div>
</body>
</html>