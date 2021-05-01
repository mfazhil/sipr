<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <script src="./vendors/jquery/jquery.js"></script>
  <title>Login | SIPR</title>
</head>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <?php
  if (count($_SESSION) > 0) return header("Location: ./");
  $error = 0;

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require __DIR__ . "/_includes/database.php";

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $password = $_POST["password"];

    $sql = $db->prepare("SELECT * FROM pengguna WHERE Username = :username");
    $sql->execute(["username" => $username]);
    $pengguna = $sql->fetch(PDO::FETCH_OBJ);

    if ($pengguna === false) $error = 2;

    if ($error === 0) {
      if ($password === $pengguna->Password) {
        $_SESSION["id"] = $pengguna->IdPengguna;
        $_SESSION["role"] = strtolower($pengguna->jnspengguna);
      } else {
        $error = 1;
      }
    }

    if ($error === 0) return header("Location: ./");
  }
  ?>

  <main class="login">
    <img class="login__image" src="./images/login-illustration.svg" alt="Login SIPR">

    <form class="login__form" method="POST">
      <h2 class="login__header"><span class="login__header--line-break">Masuk</span> ke akun Anda</h2>
      <label class="login__label" for="username">Username</label>
      <input class="login__input" type="text" name="username" id="username" placeholder="Masukkan username" autofocus />
      <label class="login__label" for="Password">Password</label>
      <input class="login__input" type="password" name="password" id="password" placeholder="Masukkan password" />
      <?php if ($error === 1) { ?>
        <div class="login__alert">Username dan password tidak cocok!</div>
      <?php } ?>
      <?php if ($error === 2) { ?>
        <div class="login__alert">Username tersebut belum terdaftar!</div>
      <?php } ?>
      <button class="login__submit" type="submit" name="submit">Login</button>
    </form>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>