<?php
    $no = 1;
    $nama = "Muhammad Rasyid";
    $nim = "25/566545/SV/27093";
    $prodi = "Teknologi Rekayasa Perangkat Lunak";
    $kota = "Sleman";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="mx-5 my-5">
    <h1 class="d-flex justify-content-center mb-5">Data Profile</h1>
    <table class="table table-hover table-secondary">
        <thead>
            <tr>
            <th scope="col">No</th>
            <th scope="col">Nama</th>
            <th scope="col">NIM</th>
            <th scope="col">Program Studi</th>
            <th scope="col">Kota</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $nama; ?></td>
            <td><?php echo $nim; ?></td>
            <td><?php echo $prodi; ?></td>
            <td><?php echo $kota; ?></td>
            </tr>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>