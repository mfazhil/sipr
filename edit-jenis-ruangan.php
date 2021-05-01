<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <script src="./vendors/jquery/jquery.js"></script>
  <title>Edit Jenis Ruangan | SIPR</title>
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

  if (filter_var($id, FILTER_VALIDATE_INT) === false) $error = 1;

  if ($error === 0) {
    $sql = $db->prepare("UPDATE jnsruang SET NamaJnsRuang = :nama WHERE IdJnsRuang = :id");
    $result = $sql->execute(["id" => $id, "nama" => $nama]);

    if ($result !== false) {
      header("Location: ./jenis-ruangan.php");
      exit();
    }

    $error = 2;
  }
}
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (filter_var($id, FILTER_VALIDATE_INT) === false) {
  header("Location: ./jenis-ruangan.php");
  exit();
}

$sql = $db->prepare("SELECT * FROM jnsruang WHERE IdJnsRuang = :id");
$result = $sql->execute(["id" => $id]);
if ($result === false) {
  header("Location: ./jenis-ruangan.php");
  exit();
}
$jenis_ruangan = $sql->fetch(PDO::FETCH_OBJ);

if ($jenis_ruangan === false) {
  header("Location: ./jenis-ruangan.php");
  exit();
}

?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="modify-room-type">
    <header class="modify-room-type__header">
      <h1>Jenis Ruangan</h1>
      <h1>//</h1>
      <h1>Edit</h1>
    </header>
    <form method="POST" class="modify-room-type__form">
      <?php if ($error === 1) { ?>
        <h3 class="modify-room-type__error">Id tidak valid</h3>
      <?php } ?>
      <?php if ($error === 2) { ?>
        <h3 class="modify-room-type__error">Gagal menyimpan data</h3>
      <?php } ?>
      <input type="hidden" name="id" value="<?= $jenis_ruangan->IdJnsRuang ?>">
      <label for="name" class="modify-room-type__label">Nama</label>
      <input id="name" class="modify-room-type__input" type="text" name="nama" value="<?= $jenis_ruangan->NamaJnsRuang ?>" required>

      <div class="modify-room-type__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./jenis-ruangan.php" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>