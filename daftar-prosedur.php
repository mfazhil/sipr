<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <script src="./vendors/jquery/jquery.js"></script>
  <title>Daftar Prosedur | SIPR</title>
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
    $sql = $db->prepare("DELETE FROM prosedur WHERE IdProsedur = :id");
    $result = $sql->execute(["id" => $id]);

    $error = $result ? 0 : 2;
  }
}

$data_prosedur = $db->query("SELECT * FROM prosedur");
$no = 0;
?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="procedure">
    <header class="procedure__header">
      <h1>Daftar Prosedur</h1>
      <div>
        <?php if ($isAdmin) { ?>
          <a href="./tambah-prosedur.php" class="button--primary small">Tambah</a>
        <?php } ?>
      </div>
    </header>
    <table class="table">
      <thead>
        <tr>
          <th>No.</th>
          <th>Nama Prosedur</th>
          <th>Keterangan</th>
          <?php if ($isAdmin) { ?>
            <th class="procedure__action">Aksi</th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($prosedur = $data_prosedur->fetch(PDO::FETCH_OBJ)) {
          $no++;
        ?>
          <tr>
            <th><?= $no ?></th>
            <td><?= $prosedur->NamaProsedur ?></td>
            <td><?= $prosedur->Keterangan ?></td>
            <?php if ($isAdmin) { ?>
              <td>
                <form class="procedure__cta" method="POST">
                  <a href="./edit-prosedur.php?id=<?= $prosedur->IdProsedur ?>" class="button--blue small">Edit</a>
                  <input type="hidden" name="id" value="<?= $prosedur->IdProsedur ?>">
                  <button type="submit" class="button--red small">Hapus</button>
                </form>
              </td>
            <?php } ?>
          </tr>
        <?php
        }
        ?>

      </tbody>
    </table>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>