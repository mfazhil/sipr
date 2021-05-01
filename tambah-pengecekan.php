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
date_default_timezone_set("Asia/Jakarta");
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "user") {
  header("Location: ./");
  exit();
}

$error = 0;
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $tgl = filter_input(INPUT_POST, "tgl", FILTER_SANITIZE_STRING);
  $id = filter_var(filter_var($_SESSION["id"], FILTER_SANITIZE_NUMBER_INT), FILTER_VALIDATE_INT);
  $pruang_arr = $_POST["pruang"];
  $nilai_arr = $_POST["nilai"];
  $jumlah =  count($_POST["pruang"]);

  $success = false;
  $sql = $db->prepare("INSERT INTO pengecekan (IdPRuang, IdPetugas, Nilai, TglPengecekan) VALUES (:pruang, :id, :nilai, :tgl)");

  for ($x = 0; $x < $jumlah; $x++) {
    $nilai = filter_var($nilai_arr[$x], FILTER_SANITIZE_NUMBER_INT);
    $pruang = filter_var($pruang_arr[$x], FILTER_SANITIZE_NUMBER_INT);

    $success = $sql->execute(["pruang" => $pruang, "id" => $id, "nilai" => $nilai, "tgl" => $tgl]);
  }

  if ($success) {
    header("Location: ./pengecekan.php");
    exit();
  }

  $error = 1;
}
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
if ($id) {
  $sql = $db->prepare("SELECT * FROM pruang INNER JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur INNER JOIN ruang ON pruang.idruang = ruang.IdRuang WHERE pruang.idruang = :id");
  $sql->execute(["id" => filter_var($id, FILTER_VALIDATE_INT)]);
  $data_pruang = $sql->fetch(PDO::FETCH_OBJ);
  if ($data_pruang === false) {
    header("Location: ./tambah-pengecekan.php");
    exit();
  }
  $no = 0;
} else {
  $previous = null;
  $data_ruangan = $db->query("SELECT * FROM ruang INNER JOIN pruang ON ruang.IdRuang = pruang.idruang");
}
?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="modify-employee">
    <header class="modify-employee__header">
      <h1>Pengecekan</h1>
      <h1>//</h1>
      <h1>Tambah</h1>
    </header>
    <?php if (isset($data_pruang)) { ?>
      <form method="POST" class="modify-employee__form">
        <?php if ($error === 1) { ?>
          <h3 class="modify-room__error">Gagal menyimpan data</h3>
        <?php } ?>

        <label for="tanggal" class="modify-employee__label">Tanggal</label>
        <input id="tanggal" class="modify-employee__input" type="date" min="2000-01-01" max="9999-12-31" value="<?= date("Y-m-d") ?>" name="tgl" required>

        <label for="ruangan" class="modify-employee__label">Nama Ruangan</label>
        <input id="ruangan" class="modify-employee__input" type="text" value="<?= $data_pruang->NamaRuang ?>" disabled>

        <input type="hidden" name="ruang" value="<?= $data_pruang->idruang ?>">

        <label for="nilai" class="modify-employee__label">Nilai untuk prosedur <?= $data_pruang->NamaProsedur ?> </label>
        <input id="nilai" class="modify-employee__input" type="number" min="0" max="100" name="nilai[]" required>
        <input type="hidden" name="pruang[]" value="<?= $data_pruang->Iddia ?>">

        <?php
        while ($pruang = $sql->fetch(PDO::FETCH_OBJ)) {
          $no++;
        ?>
          <label for="nilai<?= $no ?>" class="modify-employee__label">Nilai untuk prosedur <?= $pruang->NamaProsedur ?> </label>
          <input id="nilai<?= $no ?>" class="modify-employee__input" type="number" min="0" max="100" name="nilai[]" required>
          <input type="hidden" name="pruang[]" value="<?= $pruang->Iddia ?>">
        <?php
        }
        ?>

        <div class="modify-employee__buttons">
          <button type="submit" class="button--blue small">Simpan</button>
          <button type="reset" class="button--red small">Reset</button>
          <a href="./tambah-pengecekan.php" class="button--gray small">Kembali</a>
        </div>
      </form>
    <?php } else { ?>
      <form method="GET" class="modify-employee__form">
        <label for="ruangan" class="modify-employee__label">Ruangan :</label>
        <select class="modify-employee__select" name="id" id="ruangan" required>
          <option value="">Pilih ruangan</option>
          <?php while ($ruangan = $data_ruangan->fetch(PDO::FETCH_OBJ)) {
            if ($previous === $ruangan->IdRuang) continue;
            else $previous = $ruangan->IdRuang;
          ?>
            <option value="<?= $ruangan->IdRuang ?>"><?= $ruangan->NamaRuang ?></option>
          <?php } ?>
        </select>

        <div class="modify-employee__buttons">
          <button type="submit" class="button--blue small">Lanjut</button>
          <button type="reset" class="button--red small">Reset</button>
          <a href="./pengecekan.php" class="button--gray small">Kembali</a>
        </div>
      </form>
    <?php } ?>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>