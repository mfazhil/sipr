<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <script src="./vendors/jquery/jquery.js"></script>
  <title>Tambah Prosedur | SIPR</title>
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
  $keterangan = filter_input(INPUT_POST, "keterangan", FILTER_SANITIZE_STRING);

  $sql = $db->prepare("INSERT INTO prosedur (NamaProsedur, Keterangan) VALUES (:nama, :keterangan)");
  $result = $sql->execute(["nama" => $nama, "keterangan" => $keterangan]);

  if ($result !== false) {
    header("Location: ./daftar-prosedur.php");
    exit();
  }

  $error = 1;
}
?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="modify-procedure">
    <header class="modify-procedure__header">
      <h1>Daftar Prosedur</h1>
      <h1>//</h1>
      <h1>Tambah</h1>
    </header>
    <form method="POST" class="modify-procedure__form">
      <?php if ($error === 1) { ?>
        <h3 class="modify-procedure__error">Gagal menyimpan data</h3>
      <?php } ?>
      <label for="name" class="modify-procedure__label">Nama</label>
      <input id="name" class="modify-procedure__input" type="text" name="nama" required>
      <label for="information" class="modify-procedure__label">Keterangan</label>
      <textarea name="keterangan" id="information" class="modify-procedure__textarea" cols="30" rows="3" required></textarea>

      <div class="modify-procedure__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./daftar-prosedur.php" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>