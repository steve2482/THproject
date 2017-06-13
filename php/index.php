<?php

  require('./db.php');

  class Form {
    public $method;
    public $action;

    public function __contruct($method, $action) {
      $this->method = $method;
      $this->action = $action;
    }

    public function toggleJavascriptValidation() {
      //Not sure what to do here
    }

    public function displayForm() {
      return '<form id="sub-form" method="' . $this->method . '" action="' . $this->action . '">
                <div class="form-group">
                  <label>Name</label>
                  <input type="text" name="name" class="form-control">
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="text" name="email" class="form-control">
                </div>
                <br>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
              </form>';
    }

    public function handleSubmission(){
      // Get form values
      $name = mysqli($_POST['name']);
      $email = mysqli($_POST['email']);
      // Validate values
      // Write to DB table
      $query = "INSERT INTO subscribers (name, email) VALUES ('$name', '$email')";

      if(mysqli_query($conn, $query)) {
        header('Location: index.php');
      } else {
        echo 'ERROR: ' . mysqli_error($conn);
      }
    }

  }    
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
    <?php
      $subscribeForm = new Form('post', 'index.php');

      echo $subscribeForm->displayForm();

    ?>
  </div>

  <script type="text/javascript" src="js/index.js"></script>
</body>
</html>