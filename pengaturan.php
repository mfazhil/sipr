<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <script src="./vendors/jquery/jquery.js"></script>
  <title>Edit Petugas | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";

session_start();

if (count($_SESSION) === 0) {
  header("Location: ./");
  exit();
}

$error = 0;
$success = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = filter_var($_SESSION["id"], FILTER_SANITIZE_NUMBER_INT);
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $password = $_POST["password"];

  $sql = $db->prepare("SELECT * FROM pengguna WHERE IdPengguna = :id");
  $sql->execute(["id" => $id]);
  $pengguna = $sql->fetch(PDO::FETCH_OBJ);

  if ($pengguna === false) $error = 2;

  $sql = $db->prepare("SELECT Username FROM pengguna WHERE Username = :username LIMIT 1");
  $sql->execute(["username" => $username]);
  $existingUsername = $sql->fetch(PDO::FETCH_OBJ);

  if ($existingUsername !== false && $existingUsername->Username !== $pengguna->Username) $error = 3;

  if ($_SESSION["role"] === "admin") {
    if ($error === 0) {
      $sql = $db->prepare("UPDATE pengguna SET username = :username, password = :password WHERE IdPengguna = :id");
      $result = false;
      $result2 = true;
      $result = $sql->execute(["username" => $username, "password" => $password, "id" => $id]);
    }
  } else if ($_SESSION["role"] === "user") {
    $nama = filter_input(INPUT_POST, "nama", FILTER_SANITIZE_STRING);
    $jk = filter_input(INPUT_POST, "jeniskelamin", FILTER_SANITIZE_STRING);
    $alamat = filter_input(INPUT_POST, "alamat", FILTER_SANITIZE_STRING);
    $nohp = filter_input(INPUT_POST, "nohp", FILTER_SANITIZE_NUMBER_INT);

    if (!in_array($jk, ["laki-laki", "perempuan"])) $error = 1;

    $sql = $db->prepare("UPDATE petugas SET NamaPetugas = :nama, Jk = :jk, Alamat = :alamat, NoHP = :nohp WHERE IdPetugas = :id");
    $result = false;
    $result2 = false;
    if ($error === 0) {
      $result = $sql->execute(["nama" => $nama, "jk" => $jk, "alamat" => $alamat, "nohp" => $nohp, "id" => $pengguna->IdPetugas]);

      if ($result !== false) {
        $sql2 = $db->prepare("UPDATE pengguna SET username = :username, password = :password WHERE IdPengguna = :id");
        $result2 = $sql2->execute(["username" => $username, "password" => $password, "id" => $pengguna->IdPengguna]);
      }
    }

    if ($result === false || $result2 === false) {
      $error = $error > 0 ? $error : 4;
    } else {
      $success = true;
    }
  }
}
$id = filter_var($_SESSION["id"], FILTER_SANITIZE_NUMBER_INT);

if (filter_var($id, FILTER_VALIDATE_INT) === false) {
  header("Location: ./");
  exit();
}

if ($_SESSION["role"] === "admin") {
  $sql = $db->prepare("SELECT * FROM pengguna WHERE IdPengguna = :id");
} else {
  $sql = $db->prepare("SELECT * FROM pengguna INNER JOIN petugas ON pengguna.IdPetugas = petugas.IdPetugas WHERE IdPengguna = :id");
}

$result = $sql->execute(["id" => $id]);
if ($result === false) {
  header("Location: ./");
  exit();
}
$pengguna = $sql->fetch(PDO::FETCH_OBJ);

if ($pengguna === false) {
  header("Location: ./");
  exit();
}
?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="modify-employee">
    <header class="modify-employee__header">
      <h1>Pengaturan</h1>
      <h1>//</h1>
      <h1>Akun</h1>
    </header>
    <?php if ($pengguna->jnspengguna === "ADMIN") { ?>
      <form method="POST" class="modify-employee__form">
        <?php if ($error === 2) { ?>
          <h3 class="modify-room__error">Id tidak valid</h3>
        <?php } ?>
        <?php if ($error === 3) { ?>
          <h3 class="modify-room__error">Username sudah terpakai</h3>
        <?php } ?>
        <?php if ($error === 4) { ?>
          <h3 class="modify-room__error">Gagal menyimpan data</h3>
        <?php } ?>
        <?php if ($success) { ?>
          <h3 class="modify-room__success">Berhasil menyimpan data</h3>
        <?php } ?>

        <label for="username" class="modify-employee__label">Username</label>
        <input id="username" class="modify-employee__input" type="text" name="username" value="<?= $pengguna->Username ?>" required>

        <label for="password" class="modify-employee__label">Password</label>
        <input id="password" class="modify-employee__input" type="password" name="password" value="<?= $pengguna->Password ?>" required>

        <div class="modify-employee__buttons">
          <button type="submit" class="button--blue small">Simpan</button>
          <button type="reset" class="button--red small">Reset</button>
          <a href="./petugas.php" class="button--gray small">Kembali</a>
        </div>
      </form>
    <?php } else { ?>
      <form method="POST" class="modify-employee__form">
        <?php if ($error === 1) { ?>
          <h3 class="modify-room__error">Jenis kelamin tidak valid</h3>
        <?php } ?>
        <?php if ($error === 2) { ?>
          <h3 class="modify-room__error">Id tidak valid</h3>
        <?php } ?>
        <?php if ($error === 3) { ?>
          <h3 class="modify-room__error">Username sudah terpakai</h3>
        <?php } ?>
        <?php if ($error === 4) { ?>
          <h3 class="modify-room__error">Gagal menyimpan data</h3>
        <?php } ?>
        <?php if ($success) { ?>
          <h3 class="modify-room__success">Berhasil menyimpan data</h3>
        <?php } ?>

        <label for="name" class="modify-employee__label">Nama</label>
        <input id="name" class="modify-employee__input" type="text" name="nama" value="<?= $pengguna->NamaPetugas ?>" required>

        <label for="username" class="modify-employee__label">Username</label>
        <input id="username" class="modify-employee__input" type="text" name="username" value="<?= $pengguna->Username ?>" required>

        <label for="password" class="modify-employee__label">Password</label>
        <input id="password" class="modify-employee__input" type="password" name="password" value="<?= $pengguna->Password ?>" required>

        <label for="jeniskelamin" class="modify-employee__label">Jenis Kelamin</label>
        <select class="modify-employee__select" name="jeniskelamin" id="jeniskelamin" required>
          <option value="">Pilih jenis kelamin</option>
          <option value="laki-laki" <?= $pengguna->Jk === "laki-laki" ? "selected" : null ?>>Laki - laki</option>
          <option value="perempuan" <?= $pengguna->Jk === "perempuan" ? "selected" : null ?>>Perempuan</option>
        </select>

        <label for="address" class="modify-employee__label">Alamat</label>
        <textarea name="alamat" id="address" class="modify-employee__textarea" cols="30" rows="3" required><?= $pengguna->Alamat ?></textarea>

        <label for="mobile" class="modify-employee__label">No Hp</label>
        <input id="mobile" class="modify-employee__input" type="text" name="nohp" value="<?= $pengguna->NoHP ?>" required>

        <input type="hidden" name="id" value="<?= $pengguna->IdPengguna ?>">

        <div class="modify-employee__buttons">
          <button type="submit" class="button--blue small">Simpan</button>
          <button type="reset" class="button--red small">Reset</button>
          <a href="./petugas.php" class="button--gray small">Kembali</a>
        </div>
      </form>
    <?php } ?>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>