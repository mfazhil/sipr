<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/styles/style.css" />
  <title>Edit Rule Ruangan | SIPR</title>
</head>

<?php
require_once __DIR__ . "/_includes/database.php";
require_once __DIR__ . "/_includes/utils.php";

session_start();

if (count($_SESSION) === 0 || $_SESSION["role"] !== Role::ADMIN) {
  header("Location: ./");
  exit;
}

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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    Validate::check(["id"], $_GET);
    $id_ruang = Validate::get_int("id");
    $db->query("DELETE FROM rule WHERE IdRuang = $id_ruang");
    $insert_rule = $db->prepare("INSERT INTO rule (IdRuang, Rule) VALUES (:id_ruang, :rule)");
    foreach ($_POST as $rule) {
      $insert_rule->execute(["id_ruang" => $id_ruang, "rule" => $rule]);
    }
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}

try {
  Validate::check(["id"], $_GET);
  $id_ruang = Validate::get_int("id");
  $data_rule = $db->query("SELECT * FROM rule WHERE IdRuang = $id_ruang");
  $rules = $data_rule->fetchAll(PDO::FETCH_ASSOC);
  $data_pruang = $db->query("SELECT * FROM pruang INNER JOIN prosedur ON pruang.idprosedur = prosedur.IdProsedur WHERE idruang = $id_ruang");
  $list_prosedur = [];
  $list_himpunan = [];
  while ($pruang = $data_pruang->fetch()) {
    array_push($list_prosedur, $pruang["NamaProsedur"]);

    $id_prosedur = $pruang["IdProsedur"];
    $data_himpunan = $db->query("SELECT * FROM himpunan WHERE IdProsedur = $id_prosedur");
    $himpunans = [];
    while ($himpunan = $data_himpunan->fetch()) {
      array_push($himpunans, $himpunan["NamaHimpunan"]);
    }
    array_push($list_himpunan, $himpunans);
  }

  $rule = array_cartesian(...$list_himpunan);
} catch (Exception $e) {
  $error = $e->getMessage();
}
?>

<body>
  <?php include __DIR__ . "/_includes/navbar.php"; ?>

  <main class="main">
    <header class="main__header--no-button">
      <h1 class="main__title">Rule Ruangan</h1>
      <h1 class="main__title">//</h1>
      <h1 class="main__title">Edit</h1>
    </header>
    <form method="POST" class="form">
      <?php if (!empty($error)) { ?>
        <h3 class="form__error"><?= $error; ?></h3>
      <?php } ?>

      <?php foreach ($rule as $key1 => $value) {
        $text = ($key1 + 1) . ". Jika ";
      ?>
        <label for="<?= $key1; ?>" class="form__label">
          <?php
          foreach ($value as $key2 => $value2) {
            $prosedur = $list_prosedur[$key2];
            $text = $text . "$prosedur $value2";
            if ($key2 !== count($value) - 1) {
              $text = $text . " dan ";
            } else {
              $text = $text . " maka ruang:";
            }
          }
          echo $text;
          ?>
        </label>
        <input id="<?= $key1; ?>" class="form__input" type="text" name="<?= $key1; ?>" <?= !empty($rules[$key1]) ? "value=\"" . $rules[$key1]["Rule"] . "\"" : null; ?> required>
      <?php } ?>

      <div class="form__buttons">
        <button type="submit" class="button--blue small">Simpan</button>
        <button type="reset" class="button--red small">Reset</button>
        <a href="./data-ruangan.php" class="button--gray small">Kembali</a>
      </div>
    </form>
  </main>

  <?php include __DIR__ . "/_includes/footer.php"; ?>
</body>

</html>