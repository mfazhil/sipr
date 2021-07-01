<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <title>Pengecekan | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";

session_start();

$isUser = false;
$error = 0;

if (isset($_SESSION["role"]) && $_SESSION["role"] === "user") $isUser = true;

if ($isUser && $_SERVER["REQUEST_METHOD"] === "POST") {
  $arr_id = $_POST["id"];

  if (filter_var_array($arr_id, FILTER_VALIDATE_INT) === false) $error = 1;

  if ($error === 0) {
    $sql = $db->prepare("DELETE FROM pengecekan WHERE idPengecekan = :id");
    foreach ($arr_id as $id) {
      $result = $sql->execute(["id" => $id]);
    }

    $error = $result ? 0 : 2;
  }
}
$no = 0;
?>

<body>
  <?php require __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header">
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
            <th class="table__action">Aksi</th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php
        $data_tanggal = $db->query("SELECT TglPengecekan FROM pengecekan");
        $data_ruangan = $db->query("SELECT IdRuang FROM ruang");
        $list_tanggal = array();
        $list_ruangan = array();
        while ($data = $data_tanggal->fetch(PDO::FETCH_ASSOC)) {
          array_push($list_tanggal, $data["TglPengecekan"]);
        }
        while ($data = $data_ruangan->fetch(PDO::FETCH_ASSOC)) {
          array_push($list_ruangan, $data["IdRuang"]);
        }
        $list_tanggal = array_unique($list_tanggal);
        $list_ruangan = array_unique($list_ruangan);
        foreach ($list_tanggal as $tanggal) {
          $no++;
        ?>
          <tr>
            <th><?= $no ?></th>
            <td><?= $tanggal ?></td>
            <?php
            foreach ($list_ruangan as $ruangan) {
              $data_pengecekan = $db->query("SELECT idPengecekan, NamaPetugas, NamaRuang, NamaProsedur, Nilai FROM pengecekan LEFT JOIN petugas ON pengecekan.IdPetugas = petugas.IdPetugas LEFT JOIN pruang ON pengecekan.IdPruang = pruang.Iddia INNER JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur INNER JOIN ruang ON pruang.idruang = ruang.IdRuang WHERE TglPengecekan = '$tanggal' AND ruang.IdRuang = '$ruangan'");
              $list_NamaProsedur = array();
              $list_Nilai = array();
              $list_id = array();
              while ($pengecekan = $data_pengecekan->fetch(PDO::FETCH_OBJ)) {
                array_push($list_NamaProsedur, $pengecekan->NamaProsedur);
                array_push($list_Nilai, $pengecekan->Nilai);
                array_push($list_id, $pengecekan->idPengecekan);
              }
              $pertama = true;
              foreach ($list_id as $id) {
                if ($pertama) {
                  $query = "id[]=$id";
                  $pertama = false;
                } else {
                  $query = $query . "&id[]=$id";
                }
              }
              $data_pengecekan = $db->query("SELECT idPengecekan, NamaPetugas, NamaRuang, NamaProsedur, Nilai FROM pengecekan LEFT JOIN petugas ON pengecekan.IdPetugas = petugas.IdPetugas LEFT JOIN pruang ON pengecekan.IdPruang = pruang.Iddia INNER JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur INNER JOIN ruang ON pruang.idruang = ruang.IdRuang WHERE TglPengecekan = '$tanggal' AND ruang.IdRuang = '$ruangan'");
              $pengecekan = $data_pengecekan->fetch(PDO::FETCH_OBJ);
              if ($pengecekan !== false) {
            ?>
                <td><?= $pengecekan->NamaPetugas ?></td>
                <td><?= $pengecekan->NamaRuang ?></td>
                <td>
                  <?php
                  foreach ($list_NamaProsedur as $nama_prosedur) {
                    echo $nama_prosedur . '<br>';
                  }
                  ?>
                </td>
                <td>
                  <?php
                  foreach ($list_Nilai as $nilai) {
                    echo $nilai . '<br>';
                  }
                  ?>
                </td>
                <?php if ($isUser) { ?>
                  <td>
                    <form class="table__cta" method="POST">
                      <a href="./edit-pengecekan.php?<?= $query; ?>" class="button--blue small">Edit</a>
                      <?php
                      foreach ($list_id as $id) { ?>
                        <input type="hidden" name="id[]" value="<?= $id; ?>">
                      <?php } ?>
                      <button type="submit" class="button--red small">Hapus</button>
                    </form>
                  </td>
                <?php } ?>
          </tr>
      <?php
              }
            }
      ?>
      </tr>
    <?php
        } ?>
      </tbody>
    </table>
  </main>

  <?php require __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>