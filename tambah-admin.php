<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Tambah Admin | SIPR</title>
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
    Validate::check(["username", "password"], $_POST);
    $username = Validate::post_string("username");
    $password = $_POST["password"];

    $is_username_exists = check_username_exists($username, $db);
    if ($is_username_exists) throw new Exception("Username $username sudah digunakan, silahkan coba yang lain.", 501);

    $insert_pengguna = $db->prepare("INSERT INTO pengguna (Username, Password, jnspengguna) VALUES (:username, :password, 'ADMIN')");
    $is_inserted = $insert_pengguna->execute(["username" => $username, "password" => $password]);
    if ($is_inserted === false) throw new Exception("Gagal meyimpan data pengguna", 202);

    header("Location: ./petugas.php");
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
      <h1 class="main__title">Admin</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Tambah</h1>
    </header>

    <form method="POST" class="form">
      <?php if (!empty($error)) { ?>
        <h3 class="form__error"><?= $error; ?></h3>
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

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>