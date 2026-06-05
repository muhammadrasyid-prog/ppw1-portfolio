<?php
include_once __DIR__ . "/config.php";
// Jika sudah login, langsung ke index
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string(
        $conn,
        $_POST['username']
    );
    $password = $_POST['password'];
    // Cari user berdasarkan username ATAU email
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        // Verifikasi password dengan hash bcrypt
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            header('Location: index.php?login=success');
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="width: 400px;">
            <h3 class="text-center mb-4">Login CRUD Mahasiswa</h3>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?= $error; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>

                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Logout berhasil!</strong>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>
                </div>

            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username / Email</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100">
                    Login
                </button>
                <div class="d-flex justify-content-center align-items-center gap-1 mt-3">
                    <span>Belum punya akun?</span>
                    <a href="register.php" class="text-primary text-decoration-none fw-bold">Daftar sekarang</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>