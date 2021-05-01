<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <script src="./vendors/jquery/jquery.js"></script>
  <title>Petugas | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";

session_start();

$isAdmin = false;
$error = 0;

if (count($_SESSION) === 0) {
  header("Location: ./");
  exit();
}

if ($_SESSION["role"] === "admin") $isAdmin = true;

if ($isAdmin && $_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["delete"])) {
    $id = filter_var($_SESSION["id"], FILTER_SANITIZE_NUMBER_INT);

    if (filter_var($id, FILTER_VALIDATE_INT) === false) $error = 1;

    if ($error === 0) {
      $sql = $db->prepare("DELETE FROM pengguna WHERE IdPengguna = :id");
      $result = $sql->execute(["id" => $id]);

      if ($result !== false) {
        session_destroy();
        header("Location: ./");
        exit();
      }

      $error = $result ? 0 : 2;
    }
  } else {
    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);

    if (filter_var($id, FILTER_VALIDATE_INT) === false) $error = 1;

    if ($error === 0) {
      $sql = $db->prepare("DELETE pengguna, petugas FROM pengguna INNER JOIN petugas ON pengguna.IdPetugas = petugas.IdPetugas WHERE IdPengguna = :id");
      $result = $sql->execute(["id" => $id]);

      $error = $result ? 0 : 2;
    }
  }
}

$data_petugas = $db->query("SELECT * FROM pengguna INNER JOIN petugas ON pengguna.IdPetugas = petugas.IdPetugas");
$data_admin = $db->query("SELECT * FROM pengguna WHERE jnspengguna = 'ADMIN'");
$no = 0;
$no2 = 0;
?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="employee">
    <section>
      <header class="employee__header">
        <h1>Petugas</h1>
        <div>
          <?php if ($isAdmin) { ?>
            <a href="./tambah-petugas.php" class="button--primary small">Tambah</a>
          <?php } ?>
        </div>
      </header>
      <table class="table">
        <thead>
          <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Jenis Kelamin</th>
            <th>Nomor Hp</th>
            <th>Alamat</th>
            <?php if ($isAdmin) { ?>
              <th class="employee__action">Aksi</th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php
          while ($pengguna = $data_petugas->fetch(PDO::FETCH_OBJ)) {
            $no++;
          ?>
            <tr>
              <th><?= $no ?></th>
              <td><?= $pengguna->NamaPetugas ?></td>
              <td><?= $pengguna->Username ?></td>
              <td><?= $pengguna->Jk ?></td>
              <td><?= $pengguna->NoHP ?></td>
              <td><?= $pengguna->Alamat ?></td>
              <?php if ($isAdmin) { ?>
                <td>
                  <form class="employee__cta" method="POST">
                    <a href="./edit-petugas.php?id=<?= $pengguna->IdPengguna ?>" class="button--blue small">Edit</a>
                    <input type="hidden" name="id" value="<?= $pengguna->IdPengguna ?>">
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
    </section>
    <section>
      <header class="employee__header">
        <h1>Admin</h1>
        <div>
          <?php if ($isAdmin) { ?>
            <a href="./tambah-admin.php" class="button--primary small">Tambah</a>
          <?php } ?>
        </div>
      </header>
      <table class="table">
        <thead>
          <tr>
            <th>No.</th>
            <th>Username</th>
            <?php if ($isAdmin) { ?>
              <th class="employee__action">Aksi</th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php
          while ($admin = $data_admin->fetch(PDO::FETCH_OBJ)) {
            $no2++;
          ?>
            <tr>
              <th><?= $no2 ?></th>
              <td><?= $admin->Username ?></td>
              <?php if ($isAdmin && $data_admin->rowCount() > 1 && $_SESSION["id"] === $admin->IdPengguna) { ?>
                <td>
                  <form class="employee__cta" method="POST">
                    <button type="submit" name="delete" class="button--red small">Hapus</button>
                  </form>
                </td>
              <?php } ?>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </section>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>