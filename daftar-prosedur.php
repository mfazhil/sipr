<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Daftar Prosedur | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";
require_once __DIR__ . "/_includes/utils.php";

session_start();

$is_admin = count($_SESSION) > 0 && $_SESSION["role"] === Role::ADMIN;

if ($is_admin && $_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    Validate::check(["id_prosedur"], $_POST);
    $id_prosedur = Validate::post_int("id_prosedur");
    $delete_prosedur = $db->prepare("DELETE FROM prosedur WHERE IdProsedur = :id_prosedur");
    $delete_prosedur->execute(["id_prosedur" => $id_prosedur]);
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

$data_prosedur = $db->query("SELECT * FROM prosedur");
$row_number = 0;
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header">
      <h1>Daftar Prosedur</h1>
      <div>
        <?php if ($is_admin) { ?>
          <a href="./tambah-prosedur.php" class="button--primary small">Tambah</a>
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
          <th>Nama Prosedur</th>
          <th>Keterangan</th>
          <?php if ($is_admin) { ?>
            <th class="table__action">Aksi</th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($prosedur = $data_prosedur->fetch()) {
          $row_number++;
        ?>
          <tr>
            <th><?= $row_number; ?></th>
            <td><?= $prosedur["NamaProsedur"]; ?></td>
            <td><?= $prosedur["Keterangan"]; ?></td>
            <?php if ($is_admin) { ?>
              <td>
                <form class="table__cta" method="POST">
                  <a href="./himpunan.php?id=<?= $prosedur["IdProsedur"]; ?>" class="button--blue small" style="background-color: gray; width: 10rem;">Lihat Himpunan</a>
                  <a href="./edit-prosedur.php?id=<?= $prosedur["IdProsedur"]; ?>" class="button--blue small">Edit</a>
                  <input type="hidden" name="id_prosedur" value="<?= $prosedur["IdProsedur"]; ?>">
                  <button type="submit" class="button--red small">Hapus</button>
                </form>
              </td>
            <?php } ?>
          </tr>
        <?php
        }
        ?>

        <?php if ($data_prosedur->rowCount() === 0) { ?>
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