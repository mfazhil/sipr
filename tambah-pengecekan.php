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

date_default_timezone_set("Asia/Jakarta");
session_start();

if (count($_SESSION) === 0 || $_SESSION["role"] !== Role::USER) {
  header("Location: ./");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    Validate::check(["id"], $_GET);
    Validate::check(["date", "list_id_pruang", "list_nilai_pengecekan"], $_POST);
    $date = Validate::post_string("date");

    $list_id_pruang = $_POST["list_id_pruang"];
    $list_nilai_pengecekan = $_POST["list_nilai_pengecekan"];
    if (!is_array($list_id_pruang)) throw new Exception("Pengecekan ruang harus berbentuk array.", 504);
    if (!is_array($list_nilai_pengecekan)) throw new Exception("Nilai harus berbentuk array.", 504);

    foreach ($list_id_pruang as $id_pruang) {
      if (filter_var($id_pruang, FILTER_VALIDATE_INT) === false) throw new Exception("Id pruang hanya boleh berisi angka.", 505);
    }

    foreach ($list_nilai_pengecekan as $nilai_pengecekan) {
      if (filter_var($nilai_pengecekan, FILTER_VALIDATE_INT) === false) throw new Exception("Nilai pengecekan hanya boleh berisi angka.", 505);
      if ($nilai_pengecekan < 0 || $nilai_pengecekan > 100) throw new Exception("Nilai tidak boleh kurang dari 0 dan tidak boleh lebih dari 100.", 506);
    }

    if (count($list_id_pruang) !== count($list_nilai_pengecekan)) throw new Exception("Jumlah nilai dan jumlah pengecekan tidak sesuai.", 503);

    $id_pengguna = $_SESSION["id"];
    $data_pengguna = $db->prepare("SELECT * FROM pengguna WHERE IdPengguna = :id_pengguna");
    $data_pengguna->execute(["id_pengguna" => $id_pengguna]);
    $pengguna = $data_pengguna->fetch();

    $insert_pengecekan = $db->prepare("INSERT INTO pengecekan (IdPRuang, idPetugas, Nilai, TglPengecekan) VALUES (:id_pruang, :id_petugas, :nilai_pengecekan, :date)");

    for ($i = 0; $i < count($list_id_pruang); $i++) {
      $id_pruang = $list_id_pruang[$i];
      $nilai_pengecekan = $list_nilai_pengecekan[$i];

      $is_inserted = $insert_pengecekan->execute(["id_pruang" => $id_pruang, "id_petugas" => $pengguna["IdPetugas"], "nilai_pengecekan" => $nilai_pengecekan, "date" => $date]);
      if ($is_inserted === false) throw new Exception("Gagal menyimpan data pengecekan", 507);
    }

    header("Location: ./pengecekan.php");
    exit;
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

if (!empty($_GET["id"])) {
  try {
    $id_ruang = Validate::get_int("id");
    $data_pruang = $db->prepare("SELECT * FROM pruang INNER JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur INNER JOIN ruang ON pruang.idruang = ruang.IdRuang WHERE pruang.idruang = :id_ruang");
    $data_pruang->execute(["id_ruang" => $id_ruang]);
    $pruang = $data_pruang->fetch();
    if ($pruang === false) throw new Exception("Ruang dengan id $id_ruang tidak ditemukan.", 200);
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
} else {
  $data_ruang = $db->query("SELECT * FROM ruang INNER JOIN pruang ON ruang.IdRuang = pruang.idruang GROUP BY ruang.IdRuang");
}
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header--no-button">
      <h1 class="main__title">Pengecekan</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Tambah</h1>
    </header>
    <?php if (!empty($pruang)) { ?>
      <form method="POST" class="form">
        <?php if (!empty($error)) { ?>
          <h3 class="form__error"><?= $error; ?></h3>
        <?php } ?>

        <label for="tanggal" class="form__label">Tanggal</label>
        <input id="tanggal" class="form__input" type="date" min="2000-01-01" max="9999-12-31" value="<?= date("Y-m-d"); ?>" name="date" required>

        <label for="ruangan" class="form__label">Nama Ruangan</label>
        <input id="ruangan" class="form__input" type="text" value="<?= $pruang["NamaRuang"]; ?>" disabled>

        <input type="hidden" name="ruang" value="<?= $pruang["idruang"]; ?>">

        <label for="nilai" class="form__label">Nilai untuk prosedur <?= $pruang["NamaProsedur"]; ?> </label>
        <input id="nilai" class="form__input" type="number" min="0" max="100" name="list_nilai_pengecekan[]" required>
        <input type="hidden" name="list_id_pruang[]" value="<?= $pruang["Iddia"]; ?>">

        <?php
        while ($pruang = $data_pruang->fetch()) { ?>
          <label for="nilai-<?= $pruang["Iddia"]; ?>" class="form__label">Nilai untuk prosedur <?= $pruang["NamaProsedur"]; ?> </label>
          <input id="nilai-<?= $pruang["Iddia"]; ?>" class="form__input" type="number" min="0" max="100" name="list_nilai_pengecekan[]" required>
          <input type="hidden" name="list_id_pruang[]" value="<?= $pruang["Iddia"]; ?>">
        <?php } ?>

        <div class="form__buttons">
          <button type="submit" class="button--blue small">Simpan</button>
          <button type="reset" class="button--red small">Reset</button>
          <a href="./tambah-pengecekan.php" class="button--gray small">Kembali</a>
        </div>
      </form>
    <?php } else { ?>
      <form method="GET" class="form">
        <?php if (!empty($error)) { ?>
          <h3 class="form__error"><?= $error; ?></h3>
        <?php } ?>
        <label for="ruangan" class="form__label">Ruangan :</label>
        <select class="form__input" name="id" id="ruangan" required>
          <option value="">Pilih ruangan</option>
          <?php while ($ruang = $data_ruang->fetch()) { ?>
            <option value="<?= $ruang["IdRuang"]; ?>"><?= $ruang["NamaRuang"]; ?></option>
          <?php } ?>
        </select>

        <div class="form__buttons">
          <button type="submit" class="button--blue small">Lanjut</button>
          <button type="reset" class="button--red small">Reset</button>
          <a href="./pengecekan.php" class="button--gray small">Kembali</a>
        </div>
      </form>
    <?php } ?>
  </main>

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>