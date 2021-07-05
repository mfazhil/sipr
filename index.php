<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>SIPR</title>
</head>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="home">
    <img class="home__image" src="./assets/svgs/home-illustration.svg" alt="Ilustrasi diagram batang" title="Diagram Batang">
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

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>