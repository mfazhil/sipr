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

session_start();

if (count($_SESSION) === 0 || $_SESSION["role"] !== Role::ADMIN) {
  header("Location: ./");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    Validate::check(["id"], $_GET);
    Validate::check(["nama_ruang", "kapasitas", "id_jenis_ruang"], $_POST);
    $id_ruang = Validate::get_int("id");
    $nama_ruang = Validate::post_string("nama_ruang");
    $kapasitas = Validate::post_int("kapasitas");
    $id_jenis_ruang = Validate::post_int("id_jenis_ruang");

    $data_pruang = $db->prepare("SELECT * FROM pruang WHERE idruang = :id_ruang");
    $data_pruang->execute(["id_ruang" => $id_ruang]);
    $old_pruang = array();
    while ($pruang = $data_pruang->fetch()) {
      array_push($old_pruang, $pruang["idprosedur"]);
    }

    $new_pruang = empty($_POST["prosedur"]) ? array() : $_POST["prosedur"];

    $update_ruang = $db->prepare("UPDATE ruang SET NamaRuang= :nama_ruang, Kapasitas = :kapasitas, IdJnsRuang = :id_jenis_ruang WHERE IdRuang = :id_ruang");
    $is_updated = $update_ruang->execute(["id_ruang" => $id_ruang, "nama_ruang" => $nama_ruang, "kapasitas" => $kapasitas, "id_jenis_ruang" => $id_jenis_ruang]);
    if ($is_updated === false) throw new Exception("Gagal menyimpan data ruangan", 202);

    $insert_pruang = $db->prepare("INSERT INTO pruang (idruang, idprosedur) VALUES (:id_ruang, :id_prosedur)");
    $delete_pruang = $db->prepare("DELETE FROM pruang WHERE idruang = :id_ruang AND idprosedur = :id_prosedur");

    $new_diff = array_diff($new_pruang, $old_pruang);
    if (count($new_diff) > 0) {
      foreach ($new_diff as $id_prosedur) {
        $is_inserted = $insert_pruang->execute(["id_ruang" => $id_ruang, "id_prosedur" => $id_prosedur]);
        if ($is_inserted === false) throw new Exception("Gagal menyimpan data pruang", 202);
      }
    }

    $old_diff = array_diff($old_pruang, $new_pruang);
    if (count($old_diff) > 0) {
      foreach ($old_diff as $id_prosedur) {
        $delete_pruang->execute(["id_ruang" => $id_ruang, "id_prosedur" => $id_prosedur]);
      }
    }

    header("Location: ./data-ruangan.php");
    exit;
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

try {
  Validate::check(["id"], $_GET);
  $id_ruang = Validate::get_int("id");
  $data_ruang = $db->prepare("SELECT * FROM ruang LEFT JOIN jnsruang ON ruang.IdJnsRuang = jnsruang.IdJnsRuang WHERE IdRuang = :id_ruang");
  $data_ruang->execute(["id_ruang" => $id_ruang]);
  $ruang = $data_ruang->fetch();
  if ($ruang === false) throw new Exception("Ruang dengan id $id_ruang tidak ditemukan.", 200);
  $data_jenis_ruang = $db->query("SELECT * FROM jnsruang");
  $data_prosedur = $db->query("SELECT * FROM prosedur");
  $data_pruang = $db->prepare("SELECT * FROM pruang WHERE idruang = :id_ruang");
  $data_pruang->execute(["id_ruang" => $id_ruang]);
  $array_pruang = array();
  while ($pruang = $data_pruang->fetch()) {
    array_push($array_pruang, $pruang["idprosedur"]);
  }
} catch (Exception $e) {
  $error = $e->getMessage();
}
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header--no-button">
      <h1 class="main__title">Data Ruangan</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Edit</h1>
    </header>
    <form method="POST" class="form">
      <?php if (!empty($error)) { ?>
        <h3 class="form__error"><?= $error; ?></h3>
      <?php } ?>
      <label for="name" class="form__label">Nama</label>
      <input id="name" class="form__input" type="text" name="nama_ruang" value="<?= $ruang["NamaRuang"]; ?>" required>
      <label for="kapasitas" class="form__label">Kapasitas</label>
      <input id="kapasitas" class="form__input" type="number" value="<?= $ruang["Kapasitas"]; ?>" name="kapasitas" min="1" required>

      <label for="jenisruang" class="form__label">Jenis Ruangan</label>
      <select class="form__input" name="id_jenis_ruang" id="jenisruang" required>
        <option value="">Pilih jenis ruangan</option>
        <?php while ($jenis_ruang = $data_jenis_ruang->fetch()) { ?>
          <option value="<?= $jenis_ruang["IdJnsRuang"]; ?>" <?= $ruang["IdJnsRuang"] === $jenis_ruang["IdJnsRuang"] ? "selected" : null; ?>><?= $jenis_ruang["NamaJnsRuang"] ?></option>
        <?php } ?>
      </select>
      <div class="form__label--alt">Prosedur Ruangan</div>
      <?php while ($prosedur = $data_prosedur->fetch()) { ?>
        <input type="checkbox" id="prosedur-<?= $prosedur["IdProsedur"]; ?>" name="prosedur[]" value="<?= $prosedur["IdProsedur"]; ?>" <?= in_array($prosedur["IdProsedur"], $array_pruang) ? "checked" : null; ?>>
        <label for="prosedur-<?= $prosedur["IdProsedur"]; ?>"><?= $prosedur["NamaProsedur"]; ?></label><br>
      <?php }

      if ($data_prosedur->rowCount() === 0) { ?>
        <small>Tidak ada prosedur</small>
      <?php } ?>

      <div class="form__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./data-ruangan.php" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>