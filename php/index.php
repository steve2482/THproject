<?php
  require('./db.php');
  class Form {
    public $method;
    public $action;
    public $validate;

    public function __construct($method, $action, $validate='') {
      $this->method = $method;
      $this->action = htmlspecialchars($action);
      $this->validate = $validate;
    }
    public function toggleJavascriptValidation($boolean) {
      if ($boolean == false) {
        $this->validate = 'novalidate';
      } else {
        $this->validate = '';
      }
    }

    public function displayForm() {      
      return '<form id="sub-form" method="' . $this->method . '" action="' . $this->action . '"' . $this->validate . '>
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
      global $conn;
      $error = $emailError = '';

      if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // Get Form Values and Validate
        if (empty($_POST['name']) || empty($_POST['email'])) {
          $error = "Name & Email fields are required!";
        } else {
          $name = $this->validateForm($_POST['name']);
          $email = $this->validateForm($_POST['email']);
          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = 'You have entered an invalid email address!';
          } else {
            // Write to DB table
            $query = "INSERT INTO subscribers (name, email) VALUES ('$name', '$email')";
            if(mysqli_query($conn, $query)) {
              header('Location: subscribers.php');
            } else {
              echo 'ERROR: ' . mysqli_error($conn);
            }    
          }            
        }        
      }        
    }

    private function validateForm($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
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
  <?php $subscribeForm = new Form('post', $_SERVER["PHP_SELF"]); ?>

  <div class="container">
    <?php
      global $error;
      global $emailError;
      $subscribeForm->toggleJavascriptValidation(false);
      echo $subscribeForm->displayForm();
      if (isset($_POST['name'])) {
        echo $_POST['name'] . ' ' . $_POST['email'] . '<br>';
        echo empty($_POST['name'] . '<br>');
        $subscribeForm->handleSubmission();
        echo $error;
      }         
    ?>
    <p id="js-errors"></p>
    <p id="php-errors"><?php echo $error ?></p>
    <p id="php-errors"><?php echo $emailError ?></p>        
  </div>

  <script type="text/javascript" src="../js/index.js"></script>
  

</body>
</html>