<?php
    include_once __DIR__ . "/config.php";
    if (isLoggedIn()) {
        header('Location: index.php');
        exit();
    }
    $errors = [];
    if (isset($_POST['register'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];

        if (empty($username)) $errors[] = 'Username tidak boleh kosong';
        if (empty($email)) $errors[] = 'Email tidak boleh kosong';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = 'Format email tidak valid';
        if (empty($full_name)) $errors[] = 'Nama lengkap tidak boleh kosong';
        if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter';
        if ($password !== $confirm) $errors[] = 'Konfirmasi password tidak cocok';

        $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' OR email='$email'");
        if (mysqli_num_rows($check) > 0)
            $errors[] = 'Username atau email sudah terdaftar';

        if (empty($errors)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, full_name, password)
                    VALUES ('$username','$email','$full_name','$hashed')";
            if (mysqli_query($conn, $sql))
                $success = 'Registrasi berhasil! Silakan login.';
            else
                $errors[] = 'Error: ' . mysqli_error($conn);
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .register-card {
            width: 100%;
            max-width: 420px;
        }

        /* VALIDASI PASSWORD TETAP */
        .strength-bar {
            height: 5px;
            border-radius: 20px;
            background: #dee2e6;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: .3s;
        }

        .toggle-pw {
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4 register-card">

        <h3 class="text-center mb-4">Register CRUD Mahasiswa</h3>

        <!-- Error -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $e): ?>
                    <div><?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Success -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
                <a href="login.php" class="fw-bold text-success">
                    Login →
                </a>
            </div>
        <?php endif; ?>

        <form method="POST">

            <!-- Username -->
            <div class="mb-3">
                <label class="form-label">Username</label>

                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>

                    <input type="text"
                           name="username"
                           class="form-control"
                           placeholder="Masukkan username"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                           required>
                </div>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>

                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>

                    <input type="email"
                           name="email"
                           class="form-control"
                           placeholder="Masukkan email"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           required>
                </div>
            </div>

            <!-- Full Name -->
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>

                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-card-text"></i>
                    </span>

                    <input type="text"
                           name="full_name"
                           class="form-control"
                           placeholder="Masukkan nama lengkap"
                           value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"
                           required>
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>

                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>

                    <input type="password"
                           name="password"
                           id="pw"
                           class="form-control"
                           placeholder="Masukkan password"
                           oninput="updateStrength(this.value)"
                           required>

                    <span class="input-group-text toggle-pw"
                          onclick="togglePw('pw','icon-pw')">
                        <i class="bi bi-eye" id="icon-pw"></i>
                    </span>
                </div>

                <!-- Strength -->
                <div class="strength-bar mt-2">
                    <div class="strength-fill" id="strength-fill"></div>
                </div>

                <small id="strength-label"></small>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label class="form-label">Konfirmasi Password</label>

                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock-fill"></i>
                    </span>

                    <input type="password"
                           name="confirm_password"
                           id="pw2"
                           class="form-control"
                           placeholder="Ulangi password"
                           required>

                    <span class="input-group-text toggle-pw"
                          onclick="togglePw('pw2','icon-pw2')">
                        <i class="bi bi-eye" id="icon-pw2"></i>
                    </span>
                </div>
            </div>

            <!-- Button -->
            <button type="submit"
                    name="register"
                    class="btn btn-primary w-100">
                Register
            </button>

            <div class="text-center mt-3">
                <span>Sudah punya akun?</span>
                <a href="login.php"
                   class="text-decoration-none fw-bold">
                    Masuk sekarang
                </a>
            </div>

        </form>
    </div>
</div>

<script>
    function togglePw(id, iconId) {
        const input = document.getElementById(id);
        const icon  = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    function updateStrength(val) {
        const fill  = document.getElementById('strength-fill');
        const label = document.getElementById('strength-label');

        let score = 0;

        if (val.length >= 6) score++;
        if (val.length >= 10) score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
        if (/\d/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { pct: '0%', bg: 'transparent', text: '' },
            { pct: '25%', bg: '#dc3545', text: 'Lemah' },
            { pct: '50%', bg: '#fd7e14', text: 'Cukup' },
            { pct: '75%', bg: '#ffc107', text: 'Baik' },
            { pct: '100%', bg: '#198754', text: 'Kuat' },
        ];

        const lvl = val.length === 0
            ? levels[0]
            : levels[Math.min(score, 4)];

        fill.style.width = lvl.pct;
        fill.style.background = lvl.bg;

        label.textContent = lvl.text;
        label.style.color = lvl.bg;
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>