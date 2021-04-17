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
  <?php require "includes/navbar.php"; ?>

  <?php
  if (count($_SESSION) > 0) return header("Location: ./");
  $error = 0;

  if (count($_POST) > 0) {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $password = $_POST["password"];

    if ($username === 'admin' && $password === 'admin') {
      $_SESSION["role"] = 'admin';
    } elseif ($username === 'user' && $password === 'user') {
      $_SESSION["role"] = 'user';
    } else {
      $error = 1;
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
      <button class="login__submit" type="submit" name="submit">Login</button>
    </form>
  </main>

  <?php require "includes/footer.php"; ?>
</body>

</html>