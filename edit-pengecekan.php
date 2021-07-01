<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
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

  $tgl = filter_input(INPUT_POST, "tgl", FILTER_SANITIZE_STRING);
  unset($_POST["tgl"]);

  $sql = $db->prepare("UPDATE pengecekan SET Nilai= :nilai, TglPengecekan = :tgl WHERE idPengecekan = :id");
  foreach ($_POST as $id => $nilai) {
    $result = $sql->execute(["nilai" => $nilai, "tgl" => $tgl, "id" => $id]);
  }

  if ($result !== false) {
    header("Location: ./pengecekan.php");
    exit();
  }

  $error = 1;
}
$arr_id = $_GET["id"];

if (filter_var_array($arr_id, FILTER_VALIDATE_INT) === false) {
  header("Location: ./pengecekan.php");
  exit();
}

$sql = $db->prepare("SELECT * FROM pengecekan LEFT JOIN pruang ON pengecekan.idPRuang = pruang.Iddia WHERE idPengecekan = :id");
$sql2 = $db->prepare("SELECT * FROM pruang INNER JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur INNER JOIN ruang ON pruang.idruang = ruang.IdRuang WHERE Iddia = :id");
$result = $sql->execute(["id" => $arr_id[0]]);
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

  <main class="main">
    <header class="main__header--no-button">
      <h1 class="main__title">Pengecekan</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Edit</h1>
    </header>
    <form method="POST" class="form">
      <?php if ($error === 1) { ?>
        <h3 class="form__error">Gagal menyimpan data</h3>
      <?php } ?>

      <label for="tanggal" class="form__label">Tanggal</label>
      <input id="tanggal" class="form__input" type="date" min="2000-01-01" max="9999-12-31" value="<?= $pengecekan->TglPengecekan ?>" name="tgl" required>

      <label for="ruangan" class="form__label">Nama Ruangan</label>
      <input id="ruangan" class="form__input" type="text" value="<?= $pruang->NamaRuang ?>" disabled>

      <?php
      foreach ($arr_id as $id_pengecekan) {
        $sql = $db->prepare("SELECT idPengecekan, NamaProsedur, Nilai FROM pengecekan INNER JOIN pruang ON pengecekan.IdPRuang = pruang.Iddia INNER JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur WHERE idPengecekan = :id_pengecekan");
        $sql->execute(["id_pengecekan" => $id_pengecekan]);
        $data_pengecekan = $sql->fetch(PDO::FETCH_OBJ);
      ?>
        <label for="nilai<?= $data_pengecekan->idPengecekan; ?>" class="form__label">Nilai untuk prosedur <?= $data_pengecekan->NamaProsedur ?> </label>
        <input id="nilai<?= $data_pengecekan->idPengecekan; ?>" class="form__input" type="number" min="0" max="100" name="<?= $data_pengecekan->idPengecekan; ?>" value="<?= $data_pengecekan->Nilai; ?>" required>
      <?php } ?>

      <div class="form__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./pengecekan.php" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>