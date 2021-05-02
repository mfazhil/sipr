<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <title>Edit Prosedur | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";

session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
  header("Location: ./");
  exit();
}

$error = 0;
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
  $nama = filter_input(INPUT_POST, "nama", FILTER_SANITIZE_STRING);
  $keterangan = filter_input(INPUT_POST, "keterangan", FILTER_SANITIZE_STRING);

  if (filter_var($id, FILTER_VALIDATE_INT) === false) $error = 1;

  if ($error === 0) {
    $sql = $db->prepare("UPDATE prosedur SET NamaProsedur = :nama, Keterangan = :keterangan WHERE IdProsedur = :id");
    $result = $sql->execute(["id" => $id, "nama" => $nama, "keterangan" => $keterangan]);

    if ($result !== false) {
      header("Location: ./daftar-prosedur.php");
      exit();
    }

    $error = 2;
  }
}
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (filter_var($id, FILTER_VALIDATE_INT) === false) {
  header("Location: ./daftar-prosedur.php");
  exit();
}

$sql = $db->prepare("SELECT * FROM prosedur WHERE IdProsedur = :id");
$result = $sql->execute(["id" => $id]);
if ($result === false) {
  header("Location: ./daftar-prosedur.php");
  exit();
}
$prosedur = $sql->fetch(PDO::FETCH_OBJ);

if ($prosedur === false) {
  header("Location: ./daftar-prosedur.php");
  exit();
}

?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header--no-button">
      <h1 class="main__title">Daftar Prosedur</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Edit</h1>
    </header>
    <form method="POST" class="form">
      <?php if ($error === 1) { ?>
        <h3 class="form__error">Id tidak valid</h3>
      <?php } ?>
      <?php if ($error === 2) { ?>
        <h3 class="form__error">Gagal menyimpan data</h3>
      <?php } ?>
      <input type="hidden" name="id" value="<?= $prosedur->IdProsedur ?>">
      <label for="name" class="form__label">Nama</label>
      <input id="name" class="form__input" type="text" name="nama" value="<?= $prosedur->NamaProsedur ?>" required>
      <label for="information" class="form__label">Keterangan</label>
      <textarea name="keterangan" id="information" class="form__input" cols="30" rows="3" required><?= $prosedur->Keterangan ?></textarea>

      <div class="form__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./daftar-prosedur.php" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>