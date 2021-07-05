<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Edit Petugas | SIPR</title>
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
    Validate::check(["id"], $_GET);
    Validate::check(["nama_petugas", "username", "password", "jenis_kelamin", "alamat", "no_hp"]);
    $id_pengguna = Validate::get_int("id");
    $username = Validate::post_string("username");
    $password = $_POST["password"];
    $nama_petugas = Validate::post_string("nama_petugas");
    $jenis_kelamin = Validate::post_string("jenis_kelamin");
    $alamat = Validate::post_string("alamat");
    $no_hp = Validate::post_string("no_hp");

    if (!in_array($jenis_kelamin, ["laki-laki", "perempuan"])) throw new Exception("Jenis kelamin tidak valid", 502);

    $data_pengguna = $db->prepare("SELECT Username, IdPetugas FROM pengguna WHERE IdPengguna = :id_pengguna");
    $data_pengguna->execute(["id_pengguna" => $id_pengguna]);
    $pengguna = $data_pengguna->fetch();
    if ($pengguna === false) throw new Exception("Pengguna dengan id $id_pengguna tidak ditemukan.", 200);

    $is_username_exists = check_username_exists($username, $db);
    if ($is_username_exists && $username !== $pengguna["Username"]) throw new Exception("Username $username sudah digunakan, silahkan coba yang lain.", 501);

    $update_petugas = $db->prepare("UPDATE petugas SET NamaPetugas = :nama_petugas, Jk = :jenis_kelamin, Alamat = :alamat, NoHP = :no_hp WHERE IdPetugas = :id_petugas");
    $is_updated = $update_petugas->execute(["nama_petugas" => $nama_petugas, "jenis_kelamin" => $jenis_kelamin, "alamat" => $alamat, "no_hp" => $no_hp, "id_petugas" => $pengguna["IdPetugas"]]);
    if ($is_updated === false) throw new Exception("Gagal menyimpan data petugas.", 202);

    $update_pengguna = $db->prepare("UPDATE pengguna SET username = :username, password = :password WHERE IdPengguna = :id_pengguna");
    $is_updated = $update_pengguna->execute(["username" => $username, "password" => $password, "id_pengguna" => $id_pengguna]);
    if ($is_updated === false) throw new Exception("Gagal menyimpan data pengguna.", 202);

    header("Location: ./petugas.php");
    exit;
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

try {
  Validate::check(["id"], $_GET);
  $id_pengguna = Validate::get_int("id");
  $data_pengguna = $db->prepare("SELECT * FROM pengguna INNER JOIN petugas ON pengguna.IdPetugas = petugas.IdPetugas WHERE IdPengguna = :id_pengguna");
  $data_pengguna->execute(["id_pengguna" => $id_pengguna]);
  $pengguna = $data_pengguna->fetch();
  if ($pengguna === false) throw new Exception("Pengguna dengan id $id_pengguna tidak ditemukan.", 200);
} catch (Exception $e) {
  $error = $e->getMessage();
}
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header--no-button">
      <h1 class="main__title">Petugas</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Edit</h1>
    </header>
    <form method="POST" class="form">
      <?php if (!empty($error)) { ?>
        <h3 class="form__error"><?= $error; ?></h3>
      <?php } ?>

      <label for="name" class="form__label">Nama</label>
      <input id="name" class="form__input" type="text" name="nama_petugas" value="<?= $pengguna["NamaPetugas"]; ?>" required>

      <label for="username" class="form__label">Username</label>
      <input id="username" class="form__input" type="text" name="username" value="<?= $pengguna["Username"]; ?>" required>

      <label for="password" class="form__label">Password</label>
      <input id="password" class="form__input" type="password" name="password" value="<?= $pengguna["Password"]; ?>" required>

      <label for="jeniskelamin" class="form__label">Jenis Kelamin</label>
      <select class="form__input" name="jenis_kelamin" id="jeniskelamin" required>
        <option value="">Pilih jenis kelamin</option>
        <option value="laki-laki" <?= $pengguna["Jk"] === "laki-laki" ? "selected" : null; ?>>Laki - laki</option>
        <option value="perempuan" <?= $pengguna["Jk"] === "perempuan" ? "selected" : null; ?>>Perempuan</option>
      </select>

      <label for="address" class="form__label">Alamat</label>
      <textarea name="alamat" id="address" class="form__input" cols="30" rows="3" required><?= $pengguna["Alamat"]; ?></textarea>

      <label for="mobile" class="form__label">No Hp</label>
      <input id="mobile" class="form__input" type="text" name="no_hp" value="<?= $pengguna["NoHP"] ?>" required>

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