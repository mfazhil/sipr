<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <title>Tambah Admin | SIPR</title>
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
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $password = $_POST["password"];

  $sql = $db->prepare("SELECT Username FROM pengguna WHERE Username = :username LIMIT 1");
  $sql->execute(["username" => $username]);
  $error = $sql->fetch(PDO::FETCH_OBJ) ? 1 : $error;

  $sql = $db->prepare("INSERT INTO pengguna (Username, Password, jnspengguna) VALUES (:username, :password, :jnspengguna)");
  $result = false;
  if ($error === 0) {
    $result = $sql->execute(["username" => $username, "password" => $password, "jnspengguna" => "ADMIN"]);
  }

  if ($result !== false) {
    header("Location: ./petugas.php");
    exit();
  }

  $error = $error > 0 ? $error : 2;
}
?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header--no-button">
      <h1 class="main__title">Admin</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Tambah</h1>
    </header>

    <form method="POST" class="form">
      <?php if ($error === 1) { ?>
        <h3 class="form__error">Username sudah terpakai</h3>
      <?php } ?>
      <?php if ($error === 2) { ?>
        <h3 class="form__error">Gagal menyimpan data</h3>
      <?php } ?>

      <label for="username" class="form__label">Username</label>
      <input id="username" class="form__input" type="text" name="username" required>

      <label for="password" class="form__label">Password</label>
      <input id="password" class="form__input" type="password" name="password" required>

      <div class="form__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./petugas.php" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>