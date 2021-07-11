<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Pengecekan | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";
require_once __DIR__ . "/_includes/utils.php";

session_start();

function array_cartesian()
{
  $_ = func_get_args();
  if (count($_) == 0)
    return array();
  $a = array_shift($_);
  if (count($_) == 0)
    $c = array(array());
  else
    $c = call_user_func_array(__FUNCTION__, $_);
  $r = array();
  foreach ($a as $v)
    foreach ($c as $p)
      $r[] = array_merge(array($v), $p);
  return $r;
}

if (count($_SESSION) === 0 || $_SESSION["role"] !== Role::ADMIN) {
  header("Location: ./");
  exit;
}

$list_id = $_GET["id"];

$data_perhitungan = array();
$list_id_himpunan = array();
foreach ($list_id as $id_pengecekan) {
  $list_prosedur = array();
  $list_nilai = array();
  $list_id = array();
  $data_pengecekan = $db->query("SELECT * FROM pengecekan LEFT JOIN pruang ON pengecekan.IdPRuang = pruang.Iddia LEFT JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur WHERE pengecekan.idPengecekan = $id_pengecekan");
  if ($data_pengecekan->rowCount() === 0) continue;
  $pengecekan = $data_pengecekan->fetch();
  $id_prosedur = $pengecekan["IdProsedur"];

  $data_himpunan = $db->query("SELECT * FROM himpunan WHERE IdProsedur = $id_prosedur");
  if ($data_himpunan->rowCount() === 0) continue;
  $jumlah_himpunan = $data_himpunan->rowCount();

  $arr_id_himpunan = array();

  $list_fuzzifikasi = array();
  $keanggotaan = array();
  $fuzzifikasi = array();

  $himpun = array();

  for ($i = 0; $i < $jumlah_himpunan; $i++) {
    array_push($himpun, null);
  }

  $urutan = 1;
  while ($himpunan = $data_himpunan->fetch()) {
    if ($himpunan["Bawah"] <= $pengecekan["Nilai"] && $pengecekan["Nilai"] <= $himpunan["Atas"]) {
      array_push($keanggotaan, array_merge($himpunan, ["urutan" => $urutan]));
    } else {
      $himpun[$urutan - 1] = array_merge($himpunan, ["Nilai" => 0]);
    }
    array_push($arr_id_himpunan, $himpunan["IdHimpunan"]);
    $urutan++;
  }
  array_push($list_id_himpunan, $arr_id_himpunan);

  foreach ($keanggotaan as $fungsi) {
    $id_himpunan = $fungsi["IdHimpunan"];
    if ($pengecekan["Nilai"] <= $fungsi["Bawah"]) {
      if ($fungsi["urutan"] === 1) {
        $fuzzifikasi["$id_himpunan"] = 1;
        $himpun[$fungsi["urutan"] - 1] = array_merge($fungsi, ["Nilai" => 1]);
      } else {
        $fuzzifikasi["$id_himpunan"] = 0;
        $himpun[$fungsi["urutan"] - 1] = array_merge($fungsi, ["Nilai" => 0]);
      }
      continue;
    }
    if ($fungsi["Atas"] <= $pengecekan["Nilai"]) {
      if ($fungsi["urutan"] === $jumlah_himpunan) {
        $fuzzifikasi["$id_himpunan"] = 1;
        $himpun[$fungsi["urutan"] - 1] = array_merge($fungsi, ["Nilai" => 1]);
      } else {
        $fuzzifikasi["$id_himpunan"] = 0;
        $himpun[$fungsi["urutan"] - 1] = array_merge($fungsi, ["Nilai" => 0]);
      }
      continue;
    }
    if (!empty($fungsi["Tengah"])) {
      if ($fungsi["Bawah"] < $pengecekan["Nilai"] && $pengecekan["Nilai"] < $fungsi["Tengah"]) {
        $fuzzifikasi["$id_himpunan"] = ($pengecekan["Nilai"] - $fungsi["Bawah"]) / ($fungsi["Tengah"] - $fungsi["Bawah"]);
        $himpun[$fungsi["urutan"] - 1] = array_merge($fungsi, ["Nilai" => ($pengecekan["Nilai"] - $fungsi["Bawah"]) / ($fungsi["Tengah"] - $fungsi["Bawah"])]);
      } else if ($fungsi["Tengah"] < $pengecekan["Nilai"] && $pengecekan["Nilai"] < $fungsi["Atas"]) {
        $fuzzifikasi["$id_himpunan"] = ($fungsi["Atas"] - $pengecekan["Nilai"]) / ($fungsi["Atas"] - $fungsi["Tengah"]);
        $himpun[$fungsi["urutan"] - 1] = array_merge($fungsi, ["Nilai" => ($fungsi["Atas"] - $pengecekan["Nilai"]) / ($fungsi["Atas"] - $fungsi["Tengah"])]);
      }
      continue;
    } else {
      if ($fungsi["urutan"] === $jumlah_himpunan) {
        $fuzzifikasi["$id_himpunan"] = ($pengecekan["Nilai"] - $fungsi["Bawah"]) / ($fungsi["Atas"] - $fungsi["Bawah"]);
        $himpun[$fungsi["urutan"] - 1] = array_merge($fungsi, ["Nilai" => ($pengecekan["Nilai"] - $fungsi["Bawah"]) / ($fungsi["Atas"] - $fungsi["Bawah"])]);
      } else if ($fungsi["urutan"] === 1) {
        $fuzzifikasi["$id_himpunan"] = ($fungsi["Atas"] - $pengecekan["Nilai"]) / ($fungsi["Atas"] - $fungsi["Bawah"]);
        $himpun[$fungsi["urutan"] - 1] = array_merge($fungsi, ["Nilai" => ($fungsi["Atas"] - $pengecekan["Nilai"]) / ($fungsi["Atas"] - $fungsi["Bawah"])]);
      }
      continue;
    }
  }

  array_push($data_perhitungan, ["NamaProsedur" => $pengecekan["NamaProsedur"], "Nilai" => $pengecekan["Nilai"], "fuzzy" => $himpun]);
}

?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header">
      <h1>Hasil Perhitungan Fuzzy</h1>
      <div>
        <a href="./pengecekan.php" class="button--primary small" style="background-color: gray;">Kembali</a>
      </div>
    </header>
    <?php if (!empty($error)) { ?>
      <div class="alert-danger"><?= $error; ?></div>
    <?php } ?>
    <?php foreach ($data_perhitungan as $data) {
      $first = true;
      $row_count = count($data["fuzzy"]);
    ?>
      <table class="table" style="margin-bottom: 2rem;">
        <thead>
          <tr>
            <th colspan="5"><?= $data["NamaProsedur"] ?></th>
          </tr>
          <tr>
            <th>Nilai</th>
            <th style="padding-left: 1rem;">Nama Himpunan</th>
            <th>Fuzzifikasi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data["fuzzy"] as $fuzzy) {
          ?>
            <tr>
              <?php if ($first) {
                $first = false;
              ?>
                <td rowspan="<?= $row_count; ?>" style="border-right-width: 1px; text-align: center;"><?= $data["Nilai"]; ?></td>
              <?php
              }
              ?>
              <td style="padding-left: 1rem;"><?= $fuzzy["NamaHimpunan"] ?></td>
              <td><?= $fuzzy["Nilai"] ?></td>
            </tr>
          <?php } ?>
        </tbody>


      </table>
    <?php } ?>
  </main>

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>