<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Tambah Petugas | SIPR</title>
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
    Validate::check(["username", "password", "nama_petugas", "jenis_kelamin", "alamat", "no_hp"], $_POST);
    $username = Validate::post_string("username");
    $password = $_POST["password"];
    $nama_petugas = Validate::post_string("nama_petugas");
    $jenis_kelamin = Validate::post_string("jenis_kelamin");
    $alamat = Validate::post_string("alamat");
    $no_hp = Validate::post_string("no_hp");

    if (!in_array($jenis_kelamin, ["laki-laki", "perempuan"])) throw new Exception("Jenis kelamin tidak valid", 502);

    $is_username_exists = check_username_exists($username, $db);
    if ($is_username_exists) throw new Exception("Username $username sudah digunakan, silahkan coba yang lain.", 501);

    $insert_petugas = $db->prepare("INSERT INTO petugas (NamaPetugas, Jk, Alamat, NoHP) VALUES (:nama_petugas, :jenis_kelamin, :alamat, :no_hp)");
    $is_inserted = $insert_petugas->execute(["nama_petugas" => $nama_petugas, "jenis_kelamin" => $jenis_kelamin, "alamat" => $alamat, "no_hp" => $no_hp]);
    if ($is_inserted === false) throw new Exception("Gagal meyimpan data petugas", 202);
    $data_petugas = $db->query("SELECT IdPetugas FROM petugas ORDER BY IdPetugas DESC LIMIT 1");
    $petugas = $data_petugas->fetch();
    $insert_pegguna = $db->prepare("INSERT INTO pengguna (username, password, IdPetugas, jnspengguna) VALUES (:username, :password, :id_petugas, 'USER')");
    $is_inserted = $insert_pegguna->execute(["username" => $username, "password" => $password, "id_petugas" => $petugas["IdPetugas"]]);
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
      <h1 class="main__title">Petugas</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Tambah</h1>
    </header>
    <form method="POST" class="form">
      <?php if (!empty($error)) { ?>
        <h3 class="form__error"><?= $error; ?></h3>
      <?php } ?>

      <label for="name" class="form__label">Nama</label>
      <input id="name" class="form__input" type="text" name="nama_petugas" required>

      <label for="username" class="form__label">Username</label>
      <input id="username" class="form__input" type="text" name="username" required>

      <label for="password" class="form__label">Password</label>
      <input id="password" class="form__input" type="password" name="password" required>

      <label for="jeniskelamin" class="form__label">Jenis Kelamin</label>
      <select class="form__input" name="jenis_kelamin" id="jeniskelamin" required>
        <option value="">Pilih jenis kelamin</option>
        <option value="laki-laki">Laki - laki</option>
        <option value="perempuan">Perempuan</option>
      </select>

      <label for="address" class="form__label">Alamat</label>
      <textarea name="alamat" id="address" class="form__input" cols="30" rows="3" required></textarea>

      <label for="mobile" class="form__label">No Hp</label>
      <input id="mobile" class="form__input" type="text" name="no_hp" required>

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