<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Petugas | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";
require_once __DIR__ . "/_includes/utils.php";

session_start();

if (count($_SESSION) === 0) {
  header("Location: ./");
  exit;
}

$is_admin = $_SESSION["role"] === Role::ADMIN;

if ($is_admin && $_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    if (isset($_POST["delete"])) {
      $id_pengguna = $_SESSION["id"];

      $delete_pengguna = $db->prepare("DELETE FROM pengguna WHERE IdPengguna = :id_pengguna");
      $delete_pengguna->execute(["id_pengguna" => $id_pengguna]);

      session_destroy();
      header("Location: ./");
      exit;
    } else {
      Validate::check(["id_pengguna"], $_POST);
      $id_pengguna = Validate::post_int("id_pengguna");

      $delete_pengguna = $db->prepare("DELETE pengguna, petugas FROM pengguna INNER JOIN petugas ON pengguna.IdPetugas = petugas.IdPetugas WHERE IdPengguna = :id_pengguna");
      $delete_pengguna->execute(["id_pengguna" => $id_pengguna]);
    }
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

$data_petugas = $db->query("SELECT * FROM pengguna INNER JOIN petugas ON pengguna.IdPetugas = petugas.IdPetugas");
$data_admin = $db->query("SELECT * FROM pengguna WHERE jnspengguna = 'ADMIN'");
$petugas_row_number = 0;
$admin_row_number = 0;
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <article>
      <header class="main__header">
        <h1>Petugas</h1>
        <div>
          <?php if ($is_admin) { ?>
            <a href="./tambah-petugas.php" class="button--primary small">Tambah</a>
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
            <th>Nama</th>
            <th>Username</th>
            <th>Jenis Kelamin</th>
            <th>Nomor Hp</th>
            <th>Alamat</th>
            <?php if ($is_admin) { ?>
              <th class="table__action">Aksi</th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php
          while ($pengguna = $data_petugas->fetch()) {
            $petugas_row_number++;
          ?>
            <tr>
              <th><?= $petugas_row_number; ?></th>
              <td><?= $pengguna["NamaPetugas"]; ?></td>
              <td><?= $pengguna["Username"]; ?></td>
              <td><?= $pengguna["Jk"]; ?></td>
              <td><?= $pengguna["NoHP"]; ?></td>
              <td><?= $pengguna["Alamat"]; ?></td>
              <?php if ($is_admin) { ?>
                <td>
                  <form class="table__cta" method="POST">
                    <a href="./edit-petugas.php?id=<?= $pengguna["IdPengguna"]; ?>" class="button--blue small">Edit</a>
                    <input type="hidden" name="id_pengguna" value="<?= $pengguna["IdPengguna"]; ?>">
                    <button type="submit" class="button--red small">Hapus</button>
                  </form>
                </td>
              <?php } ?>
            </tr>
          <?php
          }
          if ($data_petugas->rowCount() === 0) { ?>
            <tr>
              <td class="text-center" colspan="7">Tidak ada data.</td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </article>
    <article>
      <header class="main__header">
        <h1>Admin</h1>
        <div>
          <?php if ($is_admin) { ?>
            <a href="./tambah-admin.php" class="button--primary small">Tambah</a>
          <?php } ?>
        </div>
      </header>
      <table class="table">
        <thead>
          <tr>
            <th>No.</th>
            <th>Username</th>
            <?php if ($is_admin) { ?>
              <th class="table__action">Aksi</th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php
          while ($admin = $data_admin->fetch()) {
            $admin_row_number++;
          ?>
            <tr>
              <th><?= $admin_row_number; ?></th>
              <td <?= !($is_admin && $data_admin->rowCount() > 1 && $_SESSION["id"] === $admin["IdPengguna"]) ? "colspan=\"2\"" : null; ?>><?= $admin["Username"]; ?></td>
              <?php if ($is_admin && $data_admin->rowCount() > 1 && $_SESSION["id"] === $admin["IdPengguna"]) { ?>
                <td>
                  <form class="table__cta" method="POST">
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
    </article>
  </main>

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>