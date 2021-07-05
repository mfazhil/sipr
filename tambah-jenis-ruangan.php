<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Tambah Jenis Ruangan | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";
require_once __DIR__ . "/_includes/utils.php";

session_start();

if (count($_SESSION) === 0 || $_SESSION["role"] !== Role::ADMIN) {
  header("Location: ./");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    Validate::check(["nama_jenis_ruang"], $_POST);
    $nama_jenis_ruang = Validate::post_string("nama_jenis_ruang");
    $insert_jenis_ruang = $db->prepare("INSERT INTO jnsruang (NamaJnsRuang) VALUES (:nama_jenis_ruang)");
    $is_inserted = $insert_jenis_ruang->execute(["nama_jenis_ruang" => $nama_jenis_ruang]);
    if ($is_inserted === false) throw new Exception("Gagal menyimpan data jenis ruang", 202);
    header("Location: ./jenis-ruangan.php");
    exit;
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header--no-button">
      <h1 class="main__title">Jenis Ruangan</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Tambah</h1>
    </header>
    <form method="POST" class="form">
      <?php if (!empty($error)) { ?>
        <h3 class="form__error"><?= $error; ?></h3>
      <?php } ?>
      <label for="name" class="form__label">Nama</label>
      <input id="name" class="form__input" type="text" name="nama_jenis_ruang" required>

      <div class="form__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./jenis-ruangan.php" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>