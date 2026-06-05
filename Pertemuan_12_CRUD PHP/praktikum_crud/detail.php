<?php
include_once __DIR__ . "/config.php";
requireLogin();

// Pastikan ada ID di URL
if (!isset($_GET['id'])) { 
    header('Location: index.php'); 
    exit(); 
}

$id = (int)$_GET["id"];

// Ambil data mahasiswa berdasarkan ID
$query = "SELECT * FROM mahasiswa WHERE id = $id";
$result = mysqli_query($conn, $query);

// Jika data tidak ditemukan, tendang kembali ke index.php
if (mysqli_num_rows($result) == 0) { 
    header('Location: index.php');
    exit(); 
}

$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mahasiswa - <?= htmlspecialchars($row['nama']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .profile-img {
            width: 220px;
            height: 220px;
            object-fit: cover;
            border-radius: 15px;
        }
        .no-profile-img {
            width: 220px;
            height: 220px;
            border-radius: 15px;
            background-color: #e9ecef;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="mb-3">
                    <a href="index.php" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>

                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="fas fa-id-card me-2 text-info"></i>Profil Lengkap Mahasiswa
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            
                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                <?php if (!empty($row['foto'])): ?>
                                    <img src="uploads/mahasiswa/<?= $row['foto'] ?>" alt="Foto <?= htmlspecialchars($row['nama']) ?>" class="profile-img img-thumbnail shadow-sm">
                                <?php else: ?>
                                    <div class="no-profile-img border shadow-sm mx-auto">
                                        <i class="fas fa-user-tie fa-4x mb-2 text-secondary"></i>
                                        <span class="small fw-semibold">Tidak Ada Foto</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-8">
                                <h3 class="fw-bold text-dark mb-1"><?= htmlspecialchars($row['nama']) ?></h3>
                                <p class="text-muted mb-4"><i class="fas fa-graduation-cap me-1"></i> <?= htmlspecialchars($row['jurusan']) ?></p>

                                <table class="table table-sm table-borderless my-2">
                                    <tr>
                                        <td class="fw-semibold text-secondary" style="width: 30%;">NIM</td>
                                        <td style="width: 5%;">:</td>
                                        <td class="text-dark fw-bold"><?= htmlspecialchars($row['nim']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-secondary">Email</td>
                                        <td>:</td>
                                        <td><a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="text-decoration-none"><?= htmlspecialchars($row['email']) ?></a></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-secondary">Alamat</td>
                                        <td>:</td>
                                        <td class="text-muted"><?= !empty($row['alamat']) ? nl2br(htmlspecialchars($row['alamat'])) : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-secondary">Tanggal Daftar</td>
                                        <td>:</td>
                                        <td class="small text-secondary">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            <?= isset($row['created_at']) ? date('d F Y, H:i', strtotime($row['created_at'])) : date('d F Y') ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer bg-light p-3 d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary px-4">
                            <i class="fas fa-chevron-left me-1"></i> Kembali
                        </a>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning px-4 fw-semibold">
                            <i class="fas fa-edit me-1"></i> Edit Data
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>