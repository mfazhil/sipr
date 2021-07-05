<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Edit Prosedur | SIPR</title>
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
    Validate::check(["nama_prosedur", "keterangan"], $_POST);
    $id_prosedur = Validate::get_int("id");
    $nama_prosedur = Validate::post_string("nama_prosedur");
    $keterangan = Validate::post_string("keterangan");
    $update_prosedur = $db->prepare("UPDATE prosedur SET NamaProsedur = :nama_prosedur, Keterangan = :keterangan WHERE IdProsedur = :id_prosedur");
    $is_updated = $update_prosedur->execute(["id_prosedur" => $id_prosedur, "nama_prosedur" => $nama_prosedur, "keterangan" => $keterangan]);
    if ($is_updated === false) throw new Exception("Gagal menyimpan data.", 202);
    header("Location: ./daftar-prosedur.php");
    exit;
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

try {
  Validate::check(["id"], $_GET);
  $id_prosedur = Validate::get_int("id");
  $data_prosedur = $db->prepare("SELECT * FROM prosedur WHERE IdProsedur = :id_prosedur");
  $data_prosedur->execute(["id_prosedur" => $id_prosedur]);
  $prosedur = $data_prosedur->fetch();
  if ($prosedur === false) throw new Exception("Prosedur dengan id $id_prosedur tidak ditemukan.", 200);
} catch (Exception $e) {
  $error = $e->getMessage();
}
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header--no-button">
      <h1 class="main__title">Daftar Prosedur</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Edit</h1>
    </header>
    <form method="POST" class="form">
      <?php if (!empty($error)) { ?>
        <h3 class="form__error"><?= $error; ?></h3>
      <?php } ?>
      <label for="name" class="form__label">Nama</label>
      <input id="name" class="form__input" type="text" name="nama_prosedur" value="<?= $prosedur["NamaProsedur"]; ?>" required>
      <label for="information" class="form__label">Keterangan</label>
      <textarea name="keterangan" id="information" class="form__input" cols="30" rows="3" required><?= $prosedur["Keterangan"]; ?></textarea>

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