<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Daftar Himpunan | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";
require_once __DIR__ . "/_includes/utils.php";

session_start();

if (count($_SESSION) === 0 ||  $_SESSION["role"] !== Role::ADMIN) {
  header("Location: ./");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    Validate::check(["id_himpunan"], $_POST);
    $id_himpunan = Validate::post_int("id_himpunan");
    $delete_himpunan = $db->prepare("DELETE FROM himpunan WHERE IdHimpunan = :id_himpunan");
    $delete_himpunan->execute(["id_himpunan" => $id_himpunan]);
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

try {
  Validate::check(["id"], $_GET);
  $id_prosedur = Validate::get_int("id");
  $data_himpunan = $db->query("SELECT * FROM himpunan WHERE IdProsedur = $id_prosedur");
} catch (Exception $e) {
  $error = $e->getMessage();
}
$row_number = 0;
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header">
      <h1>Daftar Himpunan</h1>
      <div>
        <a href="./daftar-prosedur.php" class="button--primary small" style="background-color: gray;">Kembali</a>
        <a href="./tambah-himpunan.php?id=<?= $id_prosedur; ?>" class="button--primary small">Tambah</a>
      </div>
    </header>
    <?php if (!empty($error)) { ?>
      <div class="alert-danger"><?= $error; ?></div>
    <?php } ?>
    <table class="table">
      <thead>
        <tr>
          <th>No.</th>
          <th>Nama Himpunan</th>
          <th>Bawah</th>
          <th>Tengah</th>
          <th>Atas</th>
          <th class="table__action">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($himpunan = $data_himpunan->fetch()) {
          $row_number++;
        ?>
          <tr>
            <th><?= $row_number; ?></th>
            <td><?= $himpunan["NamaHimpunan"]; ?></td>
            <td><?= $himpunan["Bawah"]; ?></td>
            <td><?= $himpunan["Tengah"]; ?></td>
            <td><?= $himpunan["Atas"]; ?></td>
            <td>
              <form class="table__cta" method="POST">
                <a href="./edit-himpunan.php?id=<?= $himpunan["IdHimpunan"]; ?>&from=<?= $id_prosedur; ?>" class="button--blue small">Edit</a>
                <input type="hidden" name="id_himpunan" value="<?= $himpunan["IdHimpunan"]; ?>">
                <button type="submit" class="button--red small">Hapus</button>
              </form>
            </td>
          </tr>
        <?php
        }
        ?>

        <?php if ($data_himpunan->rowCount() === 0) { ?>
          <tr>
            <td class="text-center" colspan="9">Tidak ada data.</td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </main>

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>