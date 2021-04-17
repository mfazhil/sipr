<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./styles/main.css" />
  <script src="./vendors/jquery/jquery.js"></script>
  <title>Data Ruangan | SIPR</title>
</head>

<body>
  <?php require "includes/navbar.php"; ?>

  <main class="room">
    <h1>Data Ruangan</h1>
    <table class="table">
      <thead>
        <tr>
          <th>No.</th>
          <th>Ruangan</th>
          <th>Jenis Ruangan</th>
          <th>Prosedur</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th>1</th>
          <td>ruangan 1</td>
          <td>jenis b</td>
          <td>Prosedur A</td>
        </tr>
      </tbody>
    </table>
  </main>

  <?php require "includes/footer.php"; ?>
</body>

</html>