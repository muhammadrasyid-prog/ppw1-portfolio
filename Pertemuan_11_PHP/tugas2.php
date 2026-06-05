<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEK IMT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="mx-5 my-5">
    <h1 class="d-flex justify-content-center">CEK IMT</h1>
    <div class>
    <h4>Hasil Cek IMT Anda:</h4>
    <p>Termasuk dalam rentang:
        <?php echo hitungIMT(65, 170); ?></p>
    </div>

    <?php
    
        function hitungIMT($berat, $tinggi) {
        $tb = $tinggi / 100;

        $imt = $berat / ($tb * $tb);
        if ($imt < 18.5) {
            return "Kurus";
        } elseif ($imt >= 18.5 && $imt <= 22.9) {
            return "Ideal";
        } elseif ($imt >= 23 && $imt <=24.9 ) {
            return "Gemuk";
        } else {
            return "Obesitas";
        }
    }

    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>