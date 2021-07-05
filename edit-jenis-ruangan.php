<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Edit Jenis Ruangan | SIPR</title>
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
    Validate::check(["id"], $_GET);
    Validate::check(["nama_jenis_ruang"], $_POST);
    $id_jenis_ruang = Validate::get_int("id");
    $nama_jenis_ruang = Validate::post_string("nama_jenis_ruang");

    $update_jenis_ruang = $db->prepare("UPDATE jnsruang SET NamaJnsRuang = :nama_jenis_ruang WHERE IdJnsRuang = :id_jenis_ruang");
    $is_updated = $update_jenis_ruang->execute(["id_jenis_ruang" => $id_jenis_ruang, "nama_jenis_ruang" => $nama_jenis_ruang]);
    if ($is_updated === false) throw new Exception("Gagal menyimpan data jenis ruang", 202);

    header("Location: ./jenis-ruangan.php");
    exit;
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

try {
  Validate::check(["id"], $_GET);
  $id_jenis_ruang = Validate::get_int("id");
  $data_jenis_ruang = $db->prepare("SELECT NamaJnsRuang FROM jnsruang WHERE IdJnsRuang = :id_jenis_ruang");
  $data_jenis_ruang->execute(["id_jenis_ruang" => $id_jenis_ruang]);
  $jenis_ruang = $data_jenis_ruang->fetch();
  if ($jenis_ruang === false) throw new Exception("Jenis ruang dengan id $id_jenis_ruang tidak ditemukan.", 200);
} catch (Exception $e) {
  $error = $e->getMessage();
}
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header--no-button">
      <h1 class="main__title">Jenis Ruangan</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Edit</h1>
    </header>
    <form method="POST" class="form">
      <?php if (!empty($error)) { ?>
        <h3 class="form__error"><?= $error; ?></h3>
      <?php } ?>
      <label for="name" class="form__label">Nama</label>
      <input id="name" class="form__input" type="text" name="nama_jenis_ruang" value="<?= $jenis_ruang["NamaJnsRuang"]; ?>" required>

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