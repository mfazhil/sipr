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

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
  header("Location: ./");
  exit();
}

$error = 0;
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
  $nama = filter_input(INPUT_POST, "nama", FILTER_SANITIZE_STRING);
  $kapasitas = filter_input(INPUT_POST, "kapasitas", FILTER_SANITIZE_NUMBER_INT);
  $jenisruang = filter_input(INPUT_POST, "jenisruang", FILTER_SANITIZE_NUMBER_INT);

  if (!isset($_POST["prosedur"])) $_POST["prosedur"] = array();

  if (filter_var($id, FILTER_VALIDATE_INT) === false) $error = 1;
  if (filter_var($kapasitas, FILTER_VALIDATE_INT) === false) $error = 2;
  if (filter_var($jenisruang, FILTER_VALIDATE_INT) === false) $error = 3;


  $sql = $db->prepare("UPDATE ruang SET NamaRuang= :nama, Kapasitas = :kapasitas, IdJnsRuang = :jnsruang WHERE IdRuang = :id");

  $result = false;
  $result2 = false;
  if ($error === 0) {
    $result = $sql->execute(["id" => $id, "nama" => $nama, "kapasitas" => $kapasitas, "jnsruang" => $jenisruang]);

    if ($result !== false) {
      $sql2 = $db->prepare("INSERT INTO pruang (idruang, idprosedur) VALUES (:ruang, :prosedur)");
      $db->prepare("DELETE FROM pruang WHERE idruang = :ruang")->execute(["ruang" => $id]);
      foreach ($_POST["prosedur"] as $prosedur) {
        if (filter_var($prosedur, FILTER_VALIDATE_INT) === false) {
          $result2 = false;
          break;
        }
        $result2 = $sql2->execute(["ruang" => $id, "prosedur" => $prosedur]);
      }
      if (count($_POST["prosedur"]) === 0) $result2 = true;
    }
  }

  if ($result !== false && $result2 !== false) {
    header("Location: ./data-ruangan.php");
    exit();
  }

  $error = $error > 0 ? $error : 4;
}
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (filter_var($id, FILTER_VALIDATE_INT) === false) {
  header("Location: ./data-ruangan.php");
  exit();
}

$sql = $db->prepare("SELECT * FROM ruang LEFT JOIN jnsruang ON ruang.IdJnsRuang = jnsruang.IdJnsRuang WHERE IdRuang = :id");
$result = $sql->execute(["id" => $id]);
if ($result === false) {
  header("Location: ./data-ruangan.php");
  exit();
}
$ruang = $sql->fetch(PDO::FETCH_OBJ);

if ($ruang === false) {
  header("Location: ./data-ruangan.php");
  exit();
}

?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="modify-room">
    <header class="modify-room__header">
      <h1>Data Ruangan</h1>
      <h1>//</h1>
      <h1>Edit</h1>
    </header>
    <form method="POST" class="modify-room__form">
      <?php if ($error > 0) { ?>
        <h3 class="modify-room__error">Gagal menyimpan data <?= $error ?></h3>
      <?php } ?>
      <label for="name" class="modify-room__label">Nama</label>
      <input id="name" class="modify-room__input" type="text" name="nama" value="<?= $ruang->NamaRuang ?>" required>
      <label for="kapasitas" class="modify-room__label">Kapasitas</label>
      <input id="kapasitas" class="modify-room__input" type="number" value="<?= $ruang->Kapasitas ?>" name="kapasitas" min="1" required>

      <label for="jenisruang" class="modify-room__label">Jenis Ruangan</label>
      <select class="modify-room__select" name="jenisruang" id="jenisruang" required>
        <option value="">Pilih jenis ruangan</option>
        <?php
        $data_jenis_ruang = $db->query("SELECT * FROM jnsruang");
        while ($jenis_ruang = $data_jenis_ruang->fetch(PDO::FETCH_OBJ)) {
        ?>
          <option value="<?= $jenis_ruang->IdJnsRuang ?>" <?= $ruang->IdJnsRuang === $jenis_ruang->IdJnsRuang ? "selected" : null ?>><?= $jenis_ruang->NamaJnsRuang ?></option>
        <?php
        }
        ?>
      </select>

      <?php
      $data_prosedur = $db->query("SELECT * FROM prosedur");
      $data_pruang = $db->query("SELECT * FROM pruang WHERE idruang = $ruang->IdRuang");
      $array_pruang = array();
      while ($pruang = $data_pruang->fetch(PDO::FETCH_OBJ)) {
        array_push($array_pruang, $pruang->idprosedur);
      }
      if ($data_prosedur !== false) {
      ?>
        <div class="modify-room__checkbox-label">Prosedur Ruangan</div>
      <?php }
      $no = 0;
      while ($prosedur = $data_prosedur->fetch(PDO::FETCH_OBJ)) {
        $no++;
      ?>
        <input type="checkbox" id="prosedur<?= $no ?>" name="prosedur[]" class="modify-room__checkbox" value="<?= $prosedur->IdProsedur ?>" <?= in_array($prosedur->IdProsedur, $array_pruang) ? "checked" : null ?>>
        <label for="prosedur<?= $no ?>"><?= $prosedur->NamaProsedur ?></label><br>
      <?php
      }
      ?>
      <input type="hidden" name="id" value="<?= $ruang->IdRuang ?>">


      <div class="modify-room__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./data-ruangan.php" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>