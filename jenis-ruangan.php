<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Jenis Ruangan | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";
require_once __DIR__ . "/_includes/utils.php";

session_start();

$is_admin = count($_SESSION) > 0 && $_SESSION["role"] === Role::ADMIN;

if ($is_admin && $_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    Validate::check(["id_jenis_ruang"], $_POST);
    $id_jenis_ruang = Validate::post_int("id_jenis_ruang");
    $delete_jenis_ruang = $db->prepare("DELETE FROM jnsruang WHERE IdJnsRuang = :id_jenis_ruang");
    $delete_jenis_ruang->execute(["id_jenis_ruang" => $id_jenis_ruang]);
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

$data_jenis_ruang = $db->query("SELECT * FROM jnsruang");
$row_number = 0;
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header">
      <h1>Jenis Ruangan</h1>
      <div>
        <?php if ($is_admin) { ?>
          <a href="./tambah-jenis-ruangan.php" class="button--primary small">Tambah</a>
        <?php } ?>
      </div>
    </header>
    <?php if (!empty($error)) { ?>
      <div class="alert-danger"><?= $error; ?></div>
    <?php } ?>
    <table class="table">
      <thead>
        <tr>
          <th>No.</th>
          <th>Nama Jenis</th>
          <?php if ($is_admin) { ?>
            <th class="table__action">Aksi</th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($jenis_ruang = $data_jenis_ruang->fetch()) {
          $row_number++;
        ?>
          <tr>
            <th><?= $row_number; ?></th>
            <td><?= $jenis_ruang["NamaJnsRuang"]; ?></td>
            <?php if ($is_admin) { ?>
              <td>
                <form class="table__cta" method="POST">
                  <a href="./edit-jenis-ruangan.php?id=<?= $jenis_ruang["IdJnsRuang"]; ?>" class="button--blue small">Edit</a>
                  <input type="hidden" name="id_jenis_ruang" value="<?= $jenis_ruang["IdJnsRuang"]; ?>">
                  <button type="submit" class="button--red small">Hapus</button>
                </form>
              </td>
            <?php } ?>
          </tr>
        <?php
        }
        ?>

        <?php if ($data_jenis_ruang->rowCount() === 0) { ?>
          <tr>
            <td class="text-center" colspan="4">Tidak ada data.</td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </main>

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>