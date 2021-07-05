<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Edit Ruangan | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";
require_once __DIR__ . "/_includes/utils.php";

date_default_timezone_set("Asia/Jakarta");
session_start();

if (count($_SESSION) === 0 || $_SESSION["role"] !== Role::USER) {
  header("Location: ./");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    Validate::check(["date"], $_POST);
    $date = Validate::post_string("date");
    if ($date !== date("Y-m-d", strtotime($date))) throw new Exception("Tanggal harus berformat yyyy-mm-dd", 505);
    unset($_POST["date"]);

    $update_pengecekan = $db->prepare("UPDATE pengecekan SET Nilai= :nilai, TglPengecekan = :date WHERE idPengecekan = :id_pengecekan");
    foreach ($_POST as $id_pengecekan => $nilai) {
      $is_updated = $update_pengecekan->execute(["nilai" => $nilai, "date" => $date, "id_pengecekan" => $id_pengecekan]);
      if ($is_updated === false) throw new Exception("Gagal menyimpan data pengecekan id $id");
    }

    header("Location: ./pengecekan.php");
    exit;
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

try {
  Validate::check(["id"], $_GET);
  $list_id_pengecekan = $_GET["id"];

  if (!is_array($list_id_pengecekan)) throw new Exception("Pengecekan harus berbentuk array.", 504);

  foreach ($list_id_pengecekan as $id_pengecekan) {
    if (filter_var($id_pengecekan, FILTER_VALIDATE_INT) === false) throw new Exception("Id pengecekan hanya boleh berisi angka.", 505);
  }

  $data_pengecekan = $db->prepare("SELECT * FROM pengecekan LEFT JOIN pruang ON pengecekan.idPRuang = pruang.Iddia WHERE idPengecekan = :id_pengecekan");
  $data_pengecekan->execute(["id_pengecekan" => $list_id_pengecekan[0]]);
  $pengecekan = $data_pengecekan->fetch();
  if ($pengecekan === false) throw new Exception("Pengecekan dengan id $pengecekan tidak ditemukan.", 200);

  $data_pruang = $db->prepare("SELECT * FROM pruang INNER JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur INNER JOIN ruang ON pruang.idruang = ruang.IdRuang WHERE Iddia = :id");
  $data_pruang->execute(["id" => $pengecekan["Iddia"]]);

  $pruang = $data_pruang->fetch();
  if ($pruang === false) throw new Exception("Pengecekan ruang dengan id " . $pengecekan["Iddia"] . " tidak ditemukan.", 200);
} catch (Exception $e) {
  $error = $e->getMessage();
}

$data_pengecekan = $db->prepare("SELECT idPengecekan, NamaProsedur, Nilai FROM pengecekan INNER JOIN pruang ON pengecekan.IdPRuang = pruang.Iddia INNER JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur WHERE idPengecekan = :id_pengecekan");
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header--no-button">
      <h1 class="main__title">Pengecekan</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Edit</h1>
    </header>
    <form method="POST" class="form">
      <?php if (!empty($error)) { ?>
        <h3 class="form__error"><?= $error; ?></h3>
      <?php } ?>

      <label for="tanggal" class="form__label">Tanggal</label>
      <input id="tanggal" class="form__input" type="date" min="2000-01-01" max="9999-12-31" value="<?= $pengecekan["TglPengecekan"]; ?>" name="date" required>

      <label for="ruangan" class="form__label">Nama Ruangan</label>
      <input id="ruangan" class="form__input" type="text" value="<?= $pruang["NamaRuang"]; ?>" disabled>

      <?php
      foreach ($list_id_pengecekan as $id_pengecekan) {
        $data_pengecekan->execute(["id_pengecekan" => $id_pengecekan]);
        $pengecekan = $data_pengecekan->fetch();
      ?>
        <label for="nilai-<?= $pengecekan["idPengecekan"]; ?>" class="form__label">Nilai untuk prosedur <?= $pengecekan["NamaProsedur"]; ?> </label>
        <input id="nilai-<?= $pengecekan["idPengecekan"]; ?>" class="form__input" type="number" min="0" max="100" name="<?= $pengecekan["idPengecekan"]; ?>" value="<?= $pengecekan["Nilai"]; ?>" required>
      <?php } ?>

      <div class="form__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./pengecekan.php" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>