<?php
    $bulan = date("F");
    $hariSekarang = date("d");
    $jumlahHari = date("t");

    $sisaHari = $jumlahHari - $hariSekarang;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Date</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="mx-5 my-5 d-flex justify-content-center">
    <div>
        <h2 class="mb-5">Informasi Bulan</h2>
        <hr>
        <h4>Sekarang Bulan: <?php echo $bulan; ?></h4>
        <p>Tersisa <?php echo $sisaHari; ?> Hari ke bulan selanjutnya</p>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>