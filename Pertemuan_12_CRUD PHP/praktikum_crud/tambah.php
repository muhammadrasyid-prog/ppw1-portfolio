<?php
include_once __DIR__ . "/config.php";
requireLogin();

if (isset($_POST['submit'])) {
    // Ambil dan escape data
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $errors = [];
    $foto_filename = null;

    // Validasi field wajib
    // Validasi panjang NIM
    if (strlen($nim) < 8 || strlen($nim) > 12) {
        $errors[] = "Panjang NIM harus di antara 8 hingga 12 karakter.";
    }

    // Validasi format angka murni (jika mengandung huruf atau simbol)
    if (!preg_match('/^[0-9]+$/', $nim)) {
        $errors[] = "NIM hanya boleh berisi angka murni, tidak boleh mengandung huruf atau simbol.";
    }
    if (empty($nama)) {
        $errors[] = 'Nama tidak boleh kosong';
    }
    if (empty($jurusan)) {
        $errors[] = 'Jurusan tidak boleh kosong';
    }
    if (empty($email)) {
        $errors[] = 'Email tidak boleh kosong';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid';
    }

    // Cek NIM sudah terdaftar
    $chk = mysqli_query($conn, "SELECT nim FROM mahasiswa WHERE nim='$nim'");
    if (mysqli_num_rows($chk) > 0) {
        $errors[] = 'NIM sudah terdaftar';
    }

    // Proses upload foto (opsional)
    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto']);
        if ($upload['success']) {
            $foto_filename = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    // Jika valid, simpan ke database
    if (empty($errors)) {
        $foto_sql = $foto_filename ? "'$foto_filename'" : 'NULL';
        $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, email, alamat, foto)
                VALUES ('$nim', '$nama', '$jurusan', '$email', '$alamat', $foto_sql)";

        if (mysqli_query($conn, $sql)) {
            header("Location: index.php?tambah=success");
            exit();
        } else {
            $errors[] = 'Error: ' . mysqli_error($conn);
            if ($foto_filename) {
                deleteFile($foto_filename);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-light">

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                <div class="mb-3">
                    <a href="index.php" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title mb-0 text-center fw-bold">
                            <i class="fas fa-user-plus me-2"></i>Tambah Data Mahasiswa
                        </h5>
                    </div>

                    <div class="card-body p-4">

                        <?php if (isset($errors) && !empty($errors)): ?>
                            <div class="alert alert-danger p-2 small">
                                <ul class="mb-0 ps-3">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="tambah.php" method="POST" enctype="multipart/form-data">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Foto Profil</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                <div class="form-text text-muted small">Format: JPG, PNG, GIF | Maks: 5MB</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                                    <input type="text" name="nim" class="form-control" required placeholder="Contoh: 21010123"
                                        value="<?= isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : '' ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Jurusan <span class="text-danger">*</span></label>
                                    <input type="text" name="jurusan" class="form-control" required placeholder="Contoh: Teknik Informatika"
                                        value="<?= isset($_POST['jurusan']) ? htmlspecialchars($_POST['jurusan']) : '' ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama lengkap"
                                    value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required placeholder="nama@email.com"
                                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="3" placeholder="Tuliskan alamat lengkap..."><?= isset($_POST["alamat"]) ? htmlspecialchars($_POST['alamat']) : '' ?></textarea>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-3">
                                <a href="index.php" class="btn btn-light px-4 border">Batal</a>
                                <button type="submit" name="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-1"></i> Simpan Data
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>