<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <script src="./vendors/jquery/jquery.js"></script>
  <title>Pengecekan | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";

session_start();

$isUser = false;
$error = 0;

if (isset($_SESSION["role"]) && $_SESSION["role"] === "user") $isUser = true;

if ($isUser && $_SERVER["REQUEST_METHOD"] === "POST") {
  $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);

  if (filter_var($id, FILTER_VALIDATE_INT) === false) $error = 1;

  if ($error === 0) {
    $sql = $db->prepare("DELETE FROM pengecekan WHERE idPengecekan = :id");
    $result = $sql->execute(["id" => $id]);

    $error = $result ? 0 : 2;
  }
}

$data_pengecekan = $db->query("SELECT * FROM pengecekan LEFT JOIN petugas ON pengecekan.IdPetugas = petugas.IdPetugas LEFT JOIN pruang ON pengecekan.IdPruang = pruang.Iddia");
$no = 0;
?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="check">
    <header class="check__header">
      <h1>Pengecekan</h1>
      <div>
        <?php if ($isUser) { ?>
          <a href="./tambah-pengecekan.php" class="button--primary small">Tambah</a>
        <?php } ?>
      </div>
    </header>
    <table class="table">
      <thead>
        <tr>
          <th>No.</th>
          <th>Tanggal</th>
          <th>Petugas</th>
          <th>Ruangan</th>
          <th>Prosedur</th>
          <th>Nilai</th>
          <?php if ($isUser) { ?>
            <th>Aksi</th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($pengecekan = $data_pengecekan->fetch(PDO::FETCH_OBJ)) {
          $no++;
        ?>
          <tr>
            <th><?= $no ?></th>
            <td><?= $pengecekan->TglPengecekan ?></td>
            <td><?= $pengecekan->NamaPetugas ?></td>
            <?php
            $sql = $db->prepare("SELECT * FROM pruang INNER JOIN ruang ON pruang.idruang = ruang.IdRuang INNER JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur WHERE pruang.iddia = :ruang");
            $sql->execute(["ruang" => filter_var($pengecekan->IdPRuang, FILTER_VALIDATE_INT)]);
            $pruang = $sql->fetch(PDO::FETCH_OBJ);
            ?>
            <td><?= $pruang->NamaRuang ?></td>
            <td><?= $pruang->NamaProsedur ?></td>
            <?php
            ?>
            <td><?= $pengecekan->Nilai ?></td>
            <?php if ($isUser) { ?>
              <td>
                <form class="room__cta" method="POST">
                  <a href="./edit-pengecekan.php?id=<?= $pengecekan->idPengecekan ?>" class="button--blue small">Edit</a>
                  <input type="hidden" name="id" value="<?= $pengecekan->idPengecekan ?>">
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