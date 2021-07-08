<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Edit Himpunan | SIPR</title>
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
    Validate::check(["nama_prosedur", "keterangan"], $_POST);
    $id_prosedur = Validate::get_int("id");
    $nama_prosedur = Validate::post_string("nama_prosedur");
    $keterangan = Validate::post_string("keterangan");
    $update_prosedur = $db->prepare("UPDATE prosedur SET NamaProsedur = :nama_prosedur, Keterangan = :keterangan WHERE IdProsedur = :id_prosedur");
    $is_updated = $update_prosedur->execute(["id_prosedur" => $id_prosedur, "nama_prosedur" => $nama_prosedur, "keterangan" => $keterangan]);
    if ($is_updated === false) throw new Exception("Gagal menyimpan data.", 202);
    header("Location: ./daftar-prosedur.php");
    exit;
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

try {
  Validate::check(["id", "from"], $_GET);
  $id_himpunan = Validate::get_int("id");
  $id_prosedur = Validate::get_string("from");
  $data_himpunan = $db->query("SELECT * FROM himpunan WHERE IdHimpunan = $id_himpunan");
  $himpunan = $data_himpunan->fetch();
  if ($himpunan === false) throw new Exception("Himpunan dengan id $id_himpunan tidak ditemukan.", 200);
} catch (Exception $e) {
  $error = $e->getMessage();
}
$row_number = 0;
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header--no-button">
      <h1 class="main__title">Daftar Himpunan</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Edit</h1>
    </header>
    <form method="POST" class="form">
      <?php if (!empty($error)) { ?>
        <h3 class="form__error"><?= $error; ?></h3>
      <?php } ?>
      <label for="name" class="form__label">Nama</label>
      <input id="name" class="form__input" type="text" name="nama_himpunan" value="<?= $himpunan["NamaHimpunan"]; ?>" required>

      <label for="bawah" class="form__label">Nilai Bawah</label>
      <input id="bawah" class="form__input" type="number" min="0" name="bawah" value="<?= $himpunan["Bawah"]; ?>" required>

      <label for="tengah" class="form__label">Nilai Tengah</label>
      <input id="tengah" class="form__input" type="number" min="0" name="tengah" value="<?= $himpunan["Tengah"]; ?>" required>

      <label for="atas" class="form__label">Nilai Atas</label>
      <input id="atas" class="form__input" type="number" min="0" name="atas" value="<?= $himpunan["Atas"]; ?>">

      <div class="form__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./himpunan.php?id=<?= $id_prosedur; ?>" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>