<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Tambah Prosedur | SIPR</title>
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
    Validate::check(["nama_prosedur", "keterangan"], $_POST);
    $nama_prosedur = Validate::post_string("nama_prosedur");
    $keterangan = Validate::post_string("keterangan");
    $insert_prosedur = $db->prepare("INSERT INTO prosedur (NamaProsedur, Keterangan) VALUES (:nama_prosedur, :keterangan)");
    $is_inserted = $insert_prosedur->execute(["nama_prosedur" => $nama_prosedur, "keterangan" => $keterangan]);
    if ($is_inserted === false) throw new Exception("Gagal menyimpan data.", 201);
    header("Location: ./daftar-prosedur.php");
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
      <h1 class="main__title">Daftar Prosedur</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Tambah</h1>
    </header>
    <?php if (!empty($error)) { ?>
      <div class="alert-danger"><?= $error; ?></div>
    <?php } ?>
    <form method="POST" class="form">
      <label for="name" class="form__label">Nama</label>
      <input id="name" class="form__input" type="text" name="nama_prosedur" required>
      <label for="information" class="form__label">Keterangan</label>
      <textarea name="keterangan" id="information" class="form__input" cols="30" rows="3" required></textarea>

      <div class="form__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./daftar-prosedur.php" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>