<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Data Ruangan | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";
require_once __DIR__ . "/_includes/utils.php";

session_start();

$is_admin = count($_SESSION) > 0 && $_SESSION["role"] === Role::ADMIN;

if ($is_admin && $_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    Validate::check(["id_ruang"], $_POST);
    $id_ruang = Validate::post_int("id_ruang");
    $delete_ruang = $db->prepare("DELETE FROM ruang WHERE IdRuang = :id_ruang");
    $delete_ruang->execute(["id_ruang" => $id_ruang]);
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

$data_ruang = $db->query("SELECT * FROM ruang LEFT JOIN jnsruang ON ruang.IdJnsRuang = jnsruang.IdJnsRuang");
$data_pruang = $db->prepare("SELECT * FROM pruang INNER JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur WHERE idruang = :id_ruangan");
$row_number = 0;
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header">
      <h1>Data Ruangan</h1>
      <div>
        <?php if ($is_admin) { ?>
          <a href="./tambah-data-ruangan.php" class="button--primary small">Tambah</a>
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
          <th>Ruangan</th>
          <th>Jenis Ruangan</th>
          <th>Kapasitas</th>
          <th>Prosedur</th>
          <?php if ($is_admin) { ?>
            <th class="table__action">Aksi</th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($ruang = $data_ruang->fetch()) {
          $row_number++;
        ?>
          <tr>
            <th><?= $row_number; ?></th>
            <td><?= $ruang["NamaRuang"]; ?></td>
            <td><?= $ruang["NamaJnsRuang"]; ?></td>
            <td><?= $ruang["Kapasitas"]; ?></td>
            <td>
              <li>
                <?php
                $data_pruang->execute(["id_ruangan" => $ruang["IdRuang"]]);
                while ($pruang = $data_pruang->fetch()) { ?>
                  <ul>- <?= $pruang["NamaProsedur"]; ?></ul>
                <?php }
                if ($data_pruang->rowCount() === 0) { ?>
                  <ul>Tidak ada prosedur</ul>
                <?php } ?>
              </li>
            </td>
            <?php if ($is_admin) { ?>
              <td>
                <form class="table__cta" method="POST">
                  <a href="./edit-data-ruangan.php?id=<?= $ruang["IdRuang"]; ?>" class="button--blue small">Edit</a>
                  <input type="hidden" name="id_ruang" value="<?= $ruang["IdRuang"]; ?>">
                  <button type="submit" class="button--red small">Hapus</button>
                </form>
              </td>
            <?php } ?>
          </tr>
        <?php }

        if ($data_ruang->rowCount() === 0) { ?>
          <tr>
            <td class="text-center" colspan="6">Tidak ada data.</td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </main>

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>