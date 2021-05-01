<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <script src="./vendors/jquery/jquery.js"></script>
  <title>Edit Ruangan | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";

session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "user") {
  header("Location: ./");
  exit();
}

$error = 0;
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
  $tgl = filter_input(INPUT_POST, "tgl", FILTER_SANITIZE_STRING);
  $nilai = filter_input(INPUT_POST, "nilai", FILTER_SANITIZE_NUMBER_INT);

  $sql = $db->prepare("UPDATE pengecekan SET Nilai= :nilai, TglPengecekan = :tgl WHERE idPengecekan = :id");

  $result = $sql->execute(["nilai" => $nilai, "tgl" => $tgl, "id" => $id]);

  if ($result !== false) {
    header("Location: ./pengecekan.php");
    exit();
  }

  $error = 1;
}
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (filter_var($id, FILTER_VALIDATE_INT) === false) {
  header("Location: ./pengecekan.php");
  exit();
}

$sql = $db->prepare("SELECT * FROM pengecekan LEFT JOIN pruang ON pengecekan.idPRuang = pruang.Iddia WHERE idPengecekan = :id");
$sql2 = $db->prepare("SELECT * FROM pruang INNER JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur INNER JOIN ruang ON pruang.idruang = ruang.IdRuang WHERE Iddia = :id");
$result = $sql->execute(["id" => $id]);
if ($result === false) {
  header("Location: ./pengecekan.php");
  exit();
}
$pengecekan = $sql->fetch(PDO::FETCH_OBJ);

if ($pengecekan === false) {
  header("Location: ./pengecekan.php");
  exit();
}

$result2 = $sql2->execute(["id" => $pengecekan->Iddia]);
if ($result2 === false) {
  header("Location: ./pengecekan.php");
  exit();
}

$pruang = $sql2->fetch(PDO::FETCH_OBJ);
if ($pruang === false) {
  header("Location: ./pengecekan.php");
  exit();
}
?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="modify-room">
    <header class="modify-room__header">
      <h1>Pengecekan</h1>
      <h1>//</h1>
      <h1>Edit</h1>
    </header>
    <form method="POST" class="modify-employee__form">
      <?php if ($error === 1) { ?>
        <h3 class="modify-room__error">Gagal menyimpan data</h3>
      <?php } ?>

      <label for="tanggal" class="modify-employee__label">Tanggal</label>
      <input id="tanggal" class="modify-employee__input" type="date" min="2000-01-01" max="9999-12-31" value="<?= $pengecekan->TglPengecekan ?>" name="tgl" required>

      <label for="ruangan" class="modify-employee__label">Nama Ruangan</label>
      <input id="ruangan" class="modify-employee__input" type="text" value="<?= $pruang->NamaRuang ?>" disabled>

      <input type="hidden" name="id" value="<?= $pengecekan->idPengecekan ?>">

      <label for="nilai" class="modify-employee__label">Nilai untuk prosedur <?= $pruang->NamaProsedur ?> </label>
      <input id="nilai" class="modify-employee__input" type="number" min="0" max="100" name="nilai" value="<?= $pengecekan->Nilai ?>" required>

      <div class="modify-employee__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./pengecekan.php" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>