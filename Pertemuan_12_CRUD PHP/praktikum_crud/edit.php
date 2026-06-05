<?php
include_once __DIR__ . "/config.php";
requireLogin();

// Pastikan ada ID di URL
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = (int)$_GET["id"];

// Ambil data mahasiswa yang akan diedit
$result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id");
if (mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit();
}

$row = mysqli_fetch_assoc($result);
$current_foto = $row["foto"];

if (isset($_POST['update'])) {
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $errors = [];
    $foto_filename = $current_foto; // default: pertahankan foto lama

    // ... (validasi sama seperti tambah.php) ...
    if (strlen($nim) < 8 || strlen($nim) > 12) {
        $errors[] = "Panjang NIM tidak sesuai! Harus berjumlah 8 sampai 12 karakter.";
    }
    if (!preg_match('/^[0-9]+$/', $nim)) {
        $errors[] = "Format NIM salah! NIM hanya boleh berisi angka murni tanpa huruf atau simbol.";
    }

    // Proses upload foto BARU jika ada
    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto']);
        if ($upload['success']) {
            if ($current_foto) {
                deleteFile($current_foto); // hapus foto lama
            }
            $foto_filename = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    // Hapus foto jika user centang opsi hapus foto
    if (
        isset($_POST['hapus_foto']) &&
        $_POST['hapus_foto'] == '1' &&
        empty($_FILES['foto']['name'])
    ) {

        if ($current_foto) {
            deleteFile($current_foto);
        }

        $foto_filename = null;
    }

    // Eksekusi update jika tidak ada error
    if (empty($errors)) {
        $foto_sql = $foto_filename ? "'$foto_filename'" : 'NULL';
        $sql = "UPDATE mahasiswa SET 
                nim='$nim', 
                nama='$nama', 
                jurusan='$jurusan',
                email='$email', 
                alamat='$alamat', 
                foto=$foto_sql
                WHERE id=$id";

        if (mysqli_query($conn, $sql)) {
            header('Location: index.php?edit=success');
            exit();
        } else {
            $errors[] = 'Error: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Mahasiswa</title>
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
                    <div class="card-header bg-warning text-dark py-3">
                        <h5 class="card-title mb-0 text-center fw-bold">
                            <i class="fas fa-user-edit me-2"></i>Edit Data Mahasiswa
                        </h5>
                    </div>

                    <div class="card-body p-4">

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger p-2 small">
                                <ul class="mb-0 ps-3">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST" enctype="multipart/form-data">

                            <div class="mb-4 text-center bg-light p-3 rounded border">
                                <label class="form-label d-block fw-semibold text-start">Foto Profil Saat Ini</label>

                                <?php if ($current_foto): ?>
                                    <img src="uploads/mahasiswa/<?= $current_foto; ?>" alt="Foto Profil" class="img-thumbnail mb-2 shadow-sm" style="max-height: 120px;">
                                    <div class="form-check d-flex justify-content-center mt-1">
                                        <input class="form-check-input me-2" type="checkbox" name="hapus_foto" value="1" id="hapusFotoCheck">
                                        <label class="form-check-label text-danger small fw-semibold" for="hapusFotoCheck">
                                            Centang untuk hapus foto ini
                                        </label>
                                    </div>
                                <?php else: ?>
                                    <div class="text-muted small my-3"><i class="fas fa-image fa-2x d-block mb-1 text-secondary"></i> Tidak ada foto profil</div>
                                <?php endif; ?>

                                <div class="text-start mt-3">
                                    <label class="form-label small fw-semibold">Ganti Foto Baru (Opsional)</label>
                                    <input type="file" name="foto" class="form-control form-control-sm" accept="image/*">
                                    <div class="form-text text-muted" style="font-size: 11px;">Format: JPG, PNG, GIF | Maks: 5MB</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                                    <input type="text" name="nim" class="form-control" required
                                        value="<?= isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : htmlspecialchars($row['nim']) ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Jurusan <span class="text-danger">*</span></label>
                                    <input type="text" name="jurusan" class="form-control" required
                                        value="<?= isset($_POST['jurusan']) ? htmlspecialchars($_POST['jurusan']) : htmlspecialchars($row['jurusan']) ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" required
                                    value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : htmlspecialchars($row['nama']) ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required
                                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($row['email']) ?>">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="3"><?= isset($_POST["alamat"]) ? htmlspecialchars($_POST['alamat']) : htmlspecialchars($row['alamat']) ?></textarea>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-3">
                                <a href="index.php" class="btn btn-light px-4 border">Batal</a>
                                <button type="submit" name="update" class="btn btn-warning px-4 fw-semibold">
                                    <i class="fas fa-save me-1"></i> Perbarui Data
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