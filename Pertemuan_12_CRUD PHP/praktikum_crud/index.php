<?php
include_once __DIR__ . "/config.php";
requireLogin(); // Cek login — redirect otomatis jika belum

// === KONFIGURASI PAGINATION ===
$limit = 5; // jumlah data per halaman
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$offset = ($page - 1) * $limit;

// === KONFIGURASI SEARCH ===
$search = isset($_GET["search"]) ? mysqli_real_escape_string($conn, $_GET["search"]) : "";
$where = "";

if (!empty($search)) {
    $where = "WHERE nim LIKE '%$search%'
              OR nama LIKE '%$search%'
              OR jurusan LIKE '%$search%'
              OR email LIKE '%$search%'";
}

// Hitung total data (untuk pagination)
$count_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM mahasiswa $where");
$total_data = mysqli_fetch_assoc($count_result)["total"];
$total_pages = ceil($total_data / $limit);

// Ambil data sesuai halaman aktif
$query = "SELECT * FROM mahasiswa $where ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="container py-4">

        <?php if (isset($_GET['login']) && $_GET['login'] == 'success'): ?>

            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Login berhasil!</strong>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>
            </div>

        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Data Mahasiswa</h2>
                <p class="text-muted">CRUD Mahasiswa Sederhana</p>
            </div>

            <div>
                <a href="tambah.php" class="btn btn-success">+ Tambah</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>

        <?php if (isset($_GET['tambah']) && $_GET['tambah'] == 'success'): ?>

            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> Data mahasiswa berhasil ditambahkan.

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>
            </div>

        <?php endif; ?>

        <?php if (isset($_GET['edit']) && $_GET['edit'] == 'success'): ?>

            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> Data mahasiswa berhasil diperbarui.

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>
            </div>

        <?php endif; ?>

        <?php if (isset($_GET['hapus']) && $_GET['hapus'] == 'success'): ?>

            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> Data mahasiswa berhasil dihapus.

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>
            </div>

        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">

                <form method="GET" class="mb-3 d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari mahasiswa...">
                    <button class="btn btn-primary">Cari</button>
                </form>

                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Jurusan</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $no = $offset + 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <?php if ($row['foto']): ?>
                                        <img src="uploads/mahasiswa/<?= $row['foto']; ?>" width="70" class="rounded">
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $row['nim']; ?></td>
                                <td><?= $row['nama']; ?></td>
                                <td><?= $row['jurusan']; ?></td>
                                <td><?= $row['email']; ?></td>
                                <td>
                                    <a href="detail.php?id=<?= $row['id']; ?>" class="btn btn-info btn-sm text_white">Detail</a>
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <!-- Pagination -->
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">

                        <!-- Tombol Previous -->
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="?page=<?= $page - 1; ?>&search=<?= $search; ?>">
                                Previous
                            </a>
                        </li>

                        <!-- Nomor Halaman -->
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>

                            <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link"
                                    href="?page=<?= $i; ?>&search=<?= $search; ?>">
                                    <?= $i; ?>
                                </a>
                            </li>

                        <?php endfor; ?>

                        <!-- Tombol Next -->
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="?page=<?= $page + 1; ?>&search=<?= $search; ?>">
                                Next
                            </a>
                        </li>

                    </ul>
                </nav>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>