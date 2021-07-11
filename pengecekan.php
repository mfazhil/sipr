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

$is_user = count($_SESSION) > 0 && $_SESSION["role"] === Role::USER;
$is_admin = count($_SESSION) > 0 && $_SESSION["role"] === Role::ADMIN;

if ($is_user && $_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    Validate::check(["id_pengecekan"], $_POST);
    $list_id_pengecekan = $_POST["id_pengecekan"];
    if (!is_array($list_id_pengecekan)) throw new Exception("Id pengecekan harus berbentuk array.", 504);
    foreach ($list_id_pengecekan as $id_pengecekan) {
      Validate::int($id_pengecekan);
    }

    $delete_pengecekan = $db->prepare("DELETE FROM pengecekan WHERE idPengecekan = :id_pengecekan");
    foreach ($list_id_pengecekan as $id_pengecekan) {
      $result = $delete_pengecekan->execute(["id_pengecekan" => $id_pengecekan]);
    }
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

$data_ruangan = $db->query("SELECT IdRuang, NamaRuang FROM ruang");
$data = array();
while ($ruang = $data_ruangan->fetch()) {
  $id_ruang = $ruang["IdRuang"];
  $daftar_tanggal = $db->query("SELECT TglPengecekan FROM pengecekan LEFT JOIN pruang ON pengecekan.IdPRuang = pruang.Iddia LEFT JOIN ruang ON pruang.idruang = ruang.IdRuang WHERE ruang.IdRuang = $id_ruang GROUP BY pengecekan.TglPengecekan");
  if ($daftar_tanggal->rowCount() === 0) continue;
  $data_rule = $db->query("SELECT * FROM rule WHERE IdRuang = $id_ruang");
  $rules = $data_rule->fetchAll();
  while ($tanggal = $daftar_tanggal->fetch()) {
    $date = $tanggal["TglPengecekan"];
    $daftar_petugas = $db->query("SELECT pengecekan.idPetugas, NamaPetugas FROM pengecekan LEFT JOIN pruang ON pengecekan.IdPRuang = pruang.Iddia LEFT JOIN petugas ON pengecekan.idPetugas = petugas.IdPetugas WHERE pruang.idruang = $id_ruang AND TglPengecekan = '$date' GROUP BY NamaPetugas");
    if ($daftar_petugas->rowCount() === 0) continue;
    while ($petugas = $daftar_petugas->fetch()) {
      $list_prosedur = array();
      $list_nilai = array();
      $list_id = array();
      $list_fuzzifikasi = array();
      $list_status = array();
      $list_id_himpunan = array();
      $id_petugas = $petugas["idPetugas"];
      $data_pengecekan = $db->query("SELECT idPengecekan, Nilai, NamaProsedur, prosedur.IdProsedur FROM pengecekan LEFT JOIN pruang ON pengecekan.IdPRuang = pruang.Iddia LEFT JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur WHERE pruang.idruang = $id_ruang AND TglPengecekan = '$date' AND idPetugas = $id_petugas");
      if ($data_pengecekan->rowCount() === 0) continue;
      while ($pengecekan = $data_pengecekan->fetch()) {
        array_push($list_prosedur, $pengecekan["NamaProsedur"]);
        array_push($list_nilai, $pengecekan["Nilai"]);
        array_push($list_id, $pengecekan["idPengecekan"]);

        $keanggotaan = [];
        $fuzzifikasi = [];
        $id_prosedur = $pengecekan["IdProsedur"];
        $arr_id_himpunan = array();

        $data_himpunan = $db->query("SELECT * FROM himpunan WHERE IdProsedur = $id_prosedur");
        if ($data_himpunan->rowCount() === 0) continue;
        $jumlah_himpunan = $data_himpunan->rowCount();
        $urutan = 1;
        while ($himpunan = $data_himpunan->fetch()) {
          if ($himpunan["Bawah"] <= $pengecekan["Nilai"] && $pengecekan["Nilai"] <= $himpunan["Atas"]) {
            array_push($keanggotaan, array_merge($himpunan, ["urutan" => $urutan]));
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
            } else {
              $fuzzifikasi["$id_himpunan"] = 0;
            }
            continue;
          }
          if ($fungsi["Atas"] <= $pengecekan["Nilai"]) {
            if ($fungsi["urutan"] === $jumlah_himpunan) {
              $fuzzifikasi["$id_himpunan"] = 1;
            } else {
              $fuzzifikasi["$id_himpunan"] = 0;
            }
            continue;
          }
          if (!empty($fungsi["Tengah"])) {
            if ($fungsi["Bawah"] < $pengecekan["Nilai"] && $pengecekan["Nilai"] < $fungsi["Tengah"]) {
              $fuzzifikasi["$id_himpunan"] = ($pengecekan["Nilai"] - $fungsi["Bawah"]) / ($fungsi["Tengah"] - $fungsi["Bawah"]);
            } else if ($fungsi["Tengah"] < $pengecekan["Nilai"] && $pengecekan["Nilai"] < $fungsi["Atas"]) {
              $fuzzifikasi["$id_himpunan"] = ($fungsi["Atas"] - $pengecekan["Nilai"]) / ($fungsi["Atas"] - $fungsi["Tengah"]);
            }
            continue;
          } else {
            if ($fungsi["urutan"] === $jumlah_himpunan) {
              $fuzzifikasi["$id_himpunan"] = ($pengecekan["Nilai"] - $fungsi["Bawah"]) / ($fungsi["Atas"] - $fungsi["Bawah"]);
            } else if ($fungsi["urutan"] === 1) {
              $fuzzifikasi["$id_himpunan"] = ($fungsi["Atas"] - $pengecekan["Nilai"]) / ($fungsi["Atas"] - $fungsi["Bawah"]);
            }
            continue;
          }
        }
        $nilai_max =  max($fuzzifikasi);
        $list_fuzzifikasi[array_search($nilai_max, $fuzzifikasi)] = $nilai_max;
      }
      $id_rule = array_cartesian(...$list_id_himpunan);
      $key_fuzzifikasi = array();
      foreach ($list_fuzzifikasi as $key => $value) {
        array_push($key_fuzzifikasi, $key);
      }
      $index_rule = array_search($key_fuzzifikasi, $id_rule);
      if ($rules !== false) {
        if (count($rules) !== count($id_rule)) {
          $status = "Jumlah rule tidak cocok";
        } else {
          $status = !empty($rules[$index_rule]) ? $rules[$index_rule]["Rule"] : "Tidak ada rule yg cocok";
        }
      } else {
        $status = "Rule tidak ada";
      }
      array_push($data, ["id_ruang" => $ruang["IdRuang"], "tanggal" => $tanggal["TglPengecekan"], "nama_ruang" => $ruang["NamaRuang"], "petugas" => $petugas["NamaPetugas"], "prosedur" => $list_prosedur, "nilai" => $list_nilai, "ids" => $list_id, "status" => $status]);
    }
  }
}

function sort_date(mixed $a, mixed $b)
{
  $date_a = strtotime($a["tanggal"]);
  $date_b = strtotime($b["tanggal"]);
  if ($date_a == $date_b) return 0;
  return ($date_a < $date_b) ? 1 : -1;
}
usort($data, "sort_date");
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header">
      <h1>Pengecekan</h1>
      <div>
        <?php if ($is_user) { ?>
          <a href="./tambah-pengecekan.php" class="button--primary small">Tambah</a>
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
          <th>Tanggal</th>
          <th>Petugas</th>
          <th>Ruangan</th>
          <th>Prosedur</th>
          <th>Nilai</th>
          <th>Status</th>
          <?php if ($is_user || $is_admin) { ?>
            <th class="table__action">Aksi</th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($data as $index => $data_cek) { ?>
          <tr>
            <th><?= $index + 1; ?></th>
            <td><?= $data_cek["tanggal"]; ?></td>
            <td><?= $data_cek["petugas"]; ?></td>
            <td><?= $data_cek["nama_ruang"]; ?></td>
            <td>
              <?php foreach ($data_cek["prosedur"] as $prosedur) {
                echo $prosedur . '<br>';
              } ?>
            </td>
            <td>
              <?php foreach ($data_cek["nilai"] as $nilai) {
                echo $nilai . '<br>';
              } ?>
            </td>
            <td><?= $data_cek["status"]; ?></td>
            <?php if ($is_user) { ?>
              <td>
                <form class="table__cta" method="POST">
                  <a href="./edit-pengecekan.php?id[]=<?= join("&id[]=", $data_cek["ids"]); ?>" class="button--blue small">Edit</a>
                  <?php
                  foreach ($data_cek["ids"] as $id) { ?>
                    <input type="hidden" name="id_pengecekan[]" value="<?= $id; ?>">
                  <?php } ?>
                  <button type="submit" class="button--red small">Hapus</button>
                </form>
              </td>
            <?php } ?>
            <?php if ($is_admin) { ?>
              <td style="text-align: center;">
                <a href="./fuzzy.php?id[]=<?= join("&id[]=", $data_cek["ids"]); ?>" class="button--blue small">Lihat Perhitungan</a>
              </td>
            <?php } ?>

          </tr>
        <?php } ?>

        <?php if (count($data) === 0) { ?>
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