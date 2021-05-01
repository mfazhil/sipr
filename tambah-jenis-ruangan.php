<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <script src="./vendors/jquery/jquery.js"></script>
  <title>Tambah Jenis Ruangan | SIPR</title>
</head>

<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
  header("Location: ./");
  exit();
}

$error = 0;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  require_once __DIR__ . "/_includes/database.php";

  $nama = filter_input(INPUT_POST, "nama", FILTER_SANITIZE_STRING);

  $sql = $db->prepare("INSERT INTO jnsruang (NamaJnsRuang) VALUES (:nama)");
  $result = $sql->execute(["nama" => $nama]);

  if ($result !== false) {
    header("Location: ./jenis-ruangan.php");
    exit();
  }

  $error = 1;
}
?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="modify-room-type">
    <header class="modify-room-type__header">
      <h1>Jenis Ruangan</h1>
      <h1>//</h1>
      <h1>Tambah</h1>
    </header>
    <form method="POST" class="modify-room-type__form">
      <?php if ($error === 1) { ?>
        <h3 class="modify-room-type__error">Gagal menyimpan data</h3>
      <?php } ?>
      <label for="name" class="modify-room-type__label">Nama</label>
      <input id="name" class="modify-room-type__input" type="text" name="nama" required>

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