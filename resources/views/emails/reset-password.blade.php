<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eeeeee;
        }

        .content {
            padding: 20px 0;
            line-height: 1.6;
        }

        .button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 15px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777777;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Permintaan Reset Password</h2>
        </div>
        <div class="content">
            <p>Halo,</p>
            <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda. Klik tombol di
                bawah ini untuk mereset password Anda:</p>

            @php
                // Membuat URL lengkap untuk reset password
                $resetUrl = route('password.reset', ['token' => $token, 'email' => $email]);
            @endphp

            <a href="{{ $resetUrl }}" class="button">Reset Password</a>

            <p>Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini.</p>
            <p>Link reset password ini akan kedaluwarsa dalam 60 menit.</p>
            <br>
            <p>Terima kasih</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Centrepark</p>
        </div>
    </div>
</body>

</html>
