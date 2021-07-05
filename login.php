<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Login | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";
require_once __DIR__ . "/_includes/utils.php";

session_start();

if (count($_SESSION) > 0) {
  header("Location: ./");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    Validate::check(["username", "password"], $_POST);
    $username = Validate::post_string("username");
    $password = $_POST["password"];

    $data_pengguna = $db->prepare("SELECT IdPengguna, Password, jnspengguna FROM pengguna WHERE Username = :username LIMIT 1");
    $data_pengguna->execute(["username" => $username]);
    $pengguna = $data_pengguna->fetch();

    if ($pengguna === false) throw new Exception("Username tersebut belum terdaftar.", 50);
    if ($password !== $pengguna["Password"]) throw new Exception("Username dan password tidak cocok.", 51);

    $_SESSION["id"] = $pengguna["IdPengguna"];
    $_SESSION["role"] = $pengguna["jnspengguna"];

    header("Location: ./");
    exit;
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="login">
    <img class="login__image" src="./assets/svgs/login-illustration.svg" alt="Ilustrasi login" title="Masuk ke akun anda">

    <form class="login__form" method="POST">
      <h2 class="login__header"><span class="login__header--line-break">Masuk</span> ke akun Anda</h2>
      <label class="login__label" for="username">Username</label>
      <input class="login__input" type="text" name="username" id="username" placeholder="Masukkan username" autofocus />
      <label class="login__label" for="Password">Password</label>
      <input class="login__input" type="password" name="password" id="password" placeholder="Masukkan password" />
      <?php if (!empty($error)) { ?>
        <div class="login__alert"><?= $error; ?></div>
      <?php } ?>
      <button class="login__submit" type="submit" name="submit">Login</button>
    </form>
  </main>

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>