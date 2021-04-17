<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <script src="./vendors/jquery/jquery.js"></script>
  <title>Penilaian | SIPR</title>
</head>

<body>
  <?php require "includes/navbar.php"; ?>

  <main class="valuation">
    <h1>Penilaian</h1>
    <table class="table">
      <thead>
        <tr>
          <th>No.</th>
          <th>Tanggal</th>
          <th>Petugas</th>
          <th>Ruangan</th>
          <th>Nilai</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th>1</th>
          <td>23-01-01</td>
          <td>petgugasas</td>
          <td>ruangan 1</td>
          <td>100</td>
        </tr>
      </tbody>
    </table>
  </main>

  <?php require "includes/footer.php"; ?>
</body>

</html>