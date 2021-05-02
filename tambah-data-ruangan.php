<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <title>Tambah Ruangan | SIPR</title>
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
  $kapasitas = filter_input(INPUT_POST, "kapasitas", FILTER_SANITIZE_NUMBER_INT);
  $jenisruang = filter_input(INPUT_POST, "jenisruang", FILTER_SANITIZE_NUMBER_INT);

  if (!isset($_POST["prosedur"])) $_POST["prosedur"] = array();

  if (filter_var($kapasitas, FILTER_VALIDATE_INT) === false) $error = 1;
  if (filter_var($jenisruang, FILTER_VALIDATE_INT) === false) $error = 2;


  $sql = $db->prepare("INSERT INTO ruang (NamaRuang, Kapasitas, IdJnsRuang) VALUES (:nama, :kapasitas, :jnsruang)");
  $result = false;
  $result2 = false;
  if ($error === 0) {
    $result = $sql->execute(["nama" => $nama, "kapasitas" => $kapasitas, "jnsruang" => $jenisruang]);

    if ($result !== false) {
      $data_ruang = $db->query("SELECT * FROM ruang ORDER BY IdRuang DESC LIMIT 1")->fetch(PDO::FETCH_OBJ);
      $ruang = $data_ruang->IdRuang;
      $sql2 = $db->prepare("INSERT INTO pruang (idruang, idprosedur) VALUES (:ruang, :prosedur)");
      foreach ($_POST["prosedur"] as $prosedur) {
        if (filter_var($prosedur, FILTER_VALIDATE_INT) === false) {
          $result2 = false;
          break;
        }
        $result2 = $sql2->execute(["ruang" => $ruang, "prosedur" => $prosedur]);
      }
      if (count($_POST["prosedur"]) === 0) $result2 = true;
    }
  }

  if ($result !== false && $result2 !== false) {
    header("Location: ./data-ruangan.php");
    exit();
  }

  $error = $error > 0 ? $error : 3;
}
?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header--no-button">
      <h1 class="main__title">Data Ruangan</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Tambah</h1>
    </header>
    <form method="POST" class="form">
      <?php if ($error > 0) { ?>
        <h3 class="form__error">Gagal menyimpan data</h3>
      <?php } ?>
      <label for="name" class="form__label">Nama</label>
      <input id="name" class="form__input" type="text" name="nama" required>
      <label for="kapasitas" class="form__label">Kapasitas</label>
      <input id="kapasitas" class="form__input" type="number" name="kapasitas" min="1" required>

      <label for="jenisruang" class="form__label">Jenis Ruangan</label>
      <select class="form__input" name="jenisruang" id="jenisruang" required>
        <option value="">Pilih jenis ruangan</option>
        <?php
        $data_jenis_ruang = $db->query("SELECT * FROM jnsruang");
        while ($jenis_ruang = $data_jenis_ruang->fetch(PDO::FETCH_OBJ)) {
        ?>
          <option value="<?= $jenis_ruang->IdJnsRuang ?>"><?= $jenis_ruang->NamaJnsRuang ?></option>
        <?php
        }
        ?>
      </select>

      <?php
      $data_prosedur = $db->query("SELECT * FROM prosedur");
      if ($data_prosedur !== false) {
      ?>
        <div class="form__label--alt">Prosedur Ruangan</div>
      <?php }
      $no = 0;
      while ($prosedur = $data_prosedur->fetch(PDO::FETCH_OBJ)) {
      ?>
        <input type="checkbox" id="prosedur<?= $no ?>" name="prosedur[]" value="<?= $prosedur->IdProsedur ?>">
        <label for="prosedur<?= $no ?>"><?= $prosedur->NamaProsedur ?></label><br>
      <?php
      }
      if ($data_prosedur->rowCount() === 0) {
      ?>
        <small>Tidak ada prosedur</small>
      <?php
      }
      ?>

      <div class="form__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./data-ruangan.php" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>