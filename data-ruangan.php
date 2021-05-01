<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <script src="./vendors/jquery/jquery.js"></script>
  <title>Data Ruangan | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";

session_start();

$isAdmin = false;
$error = 0;

if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") $isAdmin = true;

if ($isAdmin && $_SERVER["REQUEST_METHOD"] === "POST") {
  $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);

  if (filter_var($id, FILTER_VALIDATE_INT) === false) $error = 1;

  if ($error === 0) {
    $sql = $db->prepare("DELETE FROM ruang WHERE IdRuang = :id");
    $result = $sql->execute(["id" => $id]);

    $error = $result ? 0 : 2;
  }
}

$data_ruangan = $db->query("SELECT * FROM ruang LEFT JOIN jnsruang ON ruang.IdJnsRuang = jnsruang.IdJnsRuang");
$no = 0;
?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="room">
    <header class="room__header">
      <h1>Data Ruangan</h1>
      <div>
        <?php if ($isAdmin) { ?>
          <a href="./tambah-data-ruangan.php" class="button--primary small">Tambah</a>
        <?php } ?>
      </div>
    </header>
    <table class="table">
      <thead>
        <tr>
          <th>No.</th>
          <th>Ruangan</th>
          <th>Jenis Ruangan</th>
          <th>Kapasitas</th>
          <th>Prosedur</th>
          <?php if ($isAdmin) { ?>
            <th class="room__action">Aksi</th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($ruangan = $data_ruangan->fetch(PDO::FETCH_OBJ)) {
          $no++;
        ?>
          <tr>
            <th><?= $no ?></th>
            <td><?= $ruangan->NamaRuang ?></td>
            <td><?= $ruangan->NamaJnsRuang ?></td>
            <td><?= $ruangan->Kapasitas ?></td>
            <td>
              <li>
                <?php
                $sql = $db->prepare("SELECT * FROM pruang INNER JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur WHERE idruang = :ruang");
                $sql->execute(["ruang" => filter_var($ruangan->IdRuang, FILTER_VALIDATE_INT)]);
                $count = 0;
                while ($prosedur = $sql->fetch(PDO::FETCH_OBJ)) {
                  $count++;
                ?>
                  <ul>- <?= $prosedur->NamaProsedur ?></ul>
                <?php
                }
                if ($count === 0) {
                ?>
                  <ul>Tidak ada prosedur</ul>
                <?php
                }
                ?>
              </li>
            </td>
            <?php if ($isAdmin) { ?>
              <td>
                <form class="room__cta" method="POST">
                  <a href="./edit-data-ruangan.php?id=<?= $ruangan->IdRuang ?>" class="button--blue small">Edit</a>
                  <input type="hidden" name="id" value="<?= $ruangan->IdRuang ?>">
                  <button type="submit" class="button--red small">Hapus</button>
                </form>
              </td>
            <?php } ?>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>