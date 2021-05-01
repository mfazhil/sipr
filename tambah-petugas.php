<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <script src="./vendors/jquery/jquery.js"></script>
  <title>Tambah Petugas | SIPR</title>
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

  $nama = filter_input(INPUT_POST, "nama", FILTER_SANITIZE_STRING);
  $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
  $password = $_POST["password"];
  $jk = filter_input(INPUT_POST, "jeniskelamin", FILTER_SANITIZE_STRING);
  $alamat = filter_input(INPUT_POST, "alamat", FILTER_SANITIZE_STRING);
  $nohp = filter_input(INPUT_POST, "nohp", FILTER_SANITIZE_NUMBER_INT);

  if (!in_array($jk, ["laki-laki", "perempuan"])) $error = 1;

  $sql = $db->prepare("SELECT Username FROM pengguna WHERE Username = :username LIMIT 1");
  $sql->execute(["username" => $username]);
  $error = $sql->fetch(PDO::FETCH_OBJ) ? 2 : $error;

  $sql = $db->prepare("INSERT INTO petugas (NamaPetugas, Jk, Alamat, NoHP) VALUES (:nama, :jk, :alamat, :nohp)");
  $result = false;
  $result2 = false;
  if ($error === 0) {
    $result = $sql->execute(["nama" => $nama, "jk" => $jk, "alamat" => $alamat, "nohp" => $nohp]);

    if ($result !== false) {
      $data_petugas = $db->query("SELECT * FROM petugas ORDER BY IdPetugas DESC LIMIT 1")->fetch(PDO::FETCH_OBJ);
      $id = $data_petugas->IdPetugas;
      $sql2 = $db->prepare("INSERT INTO pengguna (username, password, IdPetugas, jnspengguna) VALUES (:username, :password, :id, :jnspengguna)");
      $result2 = $sql2->execute(["username" => $username, "password" => $password, "id" => $id, "jnspengguna" => "USER"]);
    }
  }

  if ($result !== false && $result2 !== false) {
    header("Location: ./petugas.php");
    exit();
  }

  $error = $error > 0 ? $error : 3;
}
?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="modify-employee">
    <header class="modify-employee__header">
      <h1>Petugas</h1>
      <h1>//</h1>
      <h1>Tambah</h1>
    </header>
    <form method="POST" class="modify-employee__form">
      <?php if ($error === 1) { ?>
        <h3 class="modify-room__error">Jenis kelamin tidak valid</h3>
      <?php } ?>
      <?php if ($error === 2) { ?>
        <h3 class="modify-room__error">Username sudah terpakai</h3>
      <?php } ?>
      <?php if ($error === 3) { ?>
        <h3 class="modify-room__error">Gagal menyimpan data</h3>
      <?php } ?>

      <label for="name" class="modify-employee__label">Nama</label>
      <input id="name" class="modify-employee__input" type="text" name="nama" required>

      <label for="username" class="modify-employee__label">Username</label>
      <input id="username" class="modify-employee__input" type="text" name="username" required>

      <label for="password" class="modify-employee__label">Password</label>
      <input id="password" class="modify-employee__input" type="password" name="password" required>

      <label for="jeniskelamin" class="modify-employee__label">Jenis Kelamin</label>
      <select class="modify-employee__select" name="jeniskelamin" id="jeniskelamin" required>
        <option value="">Pilih jenis kelamin</option>
        <option value="laki-laki">Laki - laki</option>
        <option value="perempuan">Perempuan</option>
      </select>

      <label for="address" class="modify-employee__label">Alamat</label>
      <textarea name="alamat" id="address" class="modify-employee__textarea" cols="30" rows="3" required></textarea>

      <label for="mobile" class="modify-employee__label">No Hp</label>
      <input id="mobile" class="modify-employee__input" type="text" name="nohp" required>

      <div class="modify-employee__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./petugas.php" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>