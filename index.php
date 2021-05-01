<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <script src="./vendors/jquery/jquery.js"></script>
  <title>SIPR</title>
</head>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="home">
    <img class="home__image" src="./images/home-illustration.svg" alt="SIPR">
    <div class="home__content">
      <h1 class="home__title">SIPR</h1>
      <h3 class="home__subtitle">Sistem Informasi Pemeliharaan Ruangan</h3>
      <p class="home__text">Sebuah sistem untuk menjaga kebersihan dan kesterilan ruangan di rumah sakit pada masa pandemi.</p>

      <div class="home__cta">
        <a href="./pengecekan.php" class="button--primary">Pengecekan</a>
        <a href="./data-ruangan.php" class="button--secondary">Lihat Data Ruangan</a>
      </div>
    </div>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>