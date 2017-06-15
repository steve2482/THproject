<?php

  require('./db.php');

  $query = 'SELECT * FROM subscribers ORDER BY date DESC';

  $result = mysqli_query($conn, $query);

  $subs = mysqli_fetch_all($result, MYSQLI_ASSOC);

  mysqli_free_result($result);

  mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Treehouse Newsletter</title>
  <link rel="stylesheet" href="https://bootswatch.com/cosmo/bootstrap.min.css">
</head>
<body>

  <?php include('inc/navbar.php'); ?>
  
  <div class="container">
    <table class="table table-hover">
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Date Subscribed</th>
      </tr>
      <?php foreach($subs as $sub) : ?>
        <tr>
          <td><?php echo $sub['name']; ?></td>
          <td><?php echo $sub['email']; ?></td>
          <td><?php echo $sub['date']; ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>

  <script type="text/javascript" src="../js/index.js"></script>
</body>
</html>
