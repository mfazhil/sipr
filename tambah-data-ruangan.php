<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Tambah Ruangan | SIPR</title>
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
    Validate::check(["nama_ruang", "kapasitas", "id_jenis_ruang"], $_POST);
    $nama_ruang = Validate::post_string("nama_ruang");
    $kapasitas = Validate::post_int("kapasitas");
    $id_jenis_ruang = Validate::post_int("id_jenis_ruang");

    $list_id_prosedur = empty($_POST["prosedur"]) ? array() : $_POST["prosedur"];

    $insert_ruang = $db->prepare("INSERT INTO ruang (NamaRuang, Kapasitas, IdJnsRuang) VALUES (:nama_ruang, :kapasitas, :id_jenis_ruang)");

    $is_inserted = $insert_ruang->execute(["nama_ruang" => $nama_ruang, "kapasitas" => $kapasitas, "id_jenis_ruang" => $id_jenis_ruang]);
    if ($is_inserted === false) throw new Exception("Gagal meyimpan data ruang", 202);

    $data_ruang = $db->query("SELECT IdRuang FROM ruang ORDER BY IdRuang DESC LIMIT 1");
    $ruang = $data_ruang->fetch();

    $insert_pruang = $db->prepare("INSERT INTO pruang (idruang, idprosedur) VALUES (:id_ruang, :id_prosedur)");
    foreach ($list_id_prosedur as $id_prosedur) {
      $is_inserted = $insert_pruang->execute(["id_ruang" => $ruang["IdRuang"], "id_prosedur" => $id_prosedur]);
      if ($is_inserted === false) throw new Exception("Gagal menyimpan data pruang", 202);
    }

    header("Location: ./data-ruangan.php");
    exit;
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

$data_jenis_ruang = $db->query("SELECT IdJnsRuang, NamaJnsRuang FROM jnsruang");
$data_prosedur = $db->query("SELECT IdProsedur, NamaProsedur FROM prosedur");
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header--no-button">
      <h1 class="main__title">Data Ruangan</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Tambah</h1>
    </header>
    <form method="POST" class="form">
      <?php if (!empty($error)) { ?>
        <h3 class="form__error"><?= $error; ?></h3>
      <?php } ?>
      <label for="name" class="form__label">Nama</label>
      <input id="name" class="form__input" type="text" name="nama_ruang" required>
      <label for="kapasitas" class="form__label">Kapasitas</label>
      <input id="kapasitas" class="form__input" type="number" name="kapasitas" min="1" required>

      <label for="jenisruang" class="form__label">Jenis Ruangan</label>
      <select class="form__input" name="id_jenis_ruang" id="jenisruang" required>
        <option value="">Pilih jenis ruangan</option>
        <?php while ($jenis_ruang = $data_jenis_ruang->fetch()) { ?>
          <option value="<?= $jenis_ruang["IdJnsRuang"]; ?>"><?= $jenis_ruang["NamaJnsRuang"]; ?></option>
        <?php } ?>
      </select>

      <div class="form__label--alt">Prosedur Ruangan</div>
      <?php while ($prosedur = $data_prosedur->fetch()) { ?>
        <input type="checkbox" id="prosedur-<?= $prosedur["IdProsedur"]; ?>" name="prosedur[]" value="<?= $prosedur["IdProsedur"]; ?>">
        <label for="prosedur-<?= $prosedur["IdProsedur"]; ?>"><?= $prosedur["NamaProsedur"]; ?></label><br>
      <?php
      }

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