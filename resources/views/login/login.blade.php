<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100"
      style="background-image: url('https://koala.sh/api/image/v2-8dmxi-8cmn6.jpg?width=1344&height=768&dream');
             background-size: cover;
             background-repeat: no-repeat;
             background-position: center;">

    <div class="card p-4 shadow" style="width: 400px;">
        <h3 class="mb-3 text-center">
            <img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png" alt="Kasir Logo" width="40" class="mb-2">
            <br>
            JOMART
        </h3>

        {{-- Tampilkan pesan error jika ada --}}
        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email" autofocus required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>

            <button class="btn btn-primary w-100" type="submit">Login</button>
        </form>
    </div>

</body>
</html>
