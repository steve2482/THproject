<?php
  require('./db.php');
  class Form {
    public $method;
    public $action;
    public $validate;
    public $errors;
    public $scriptTag;

    public function __construct($method, $action) {
      $this->method = $method;
      $this->action = htmlspecialchars($action);
      $this->validate = '';
      $this->errors = [];
      $this->scriptTag = '<script type="text/javascript" src="../js/form-validation.js"></script>';
    }

    public function toggleJavascriptValidation($boolean) {
      if ($boolean == false) {
        $this->validate = 'novalidate';
        $this->scriptTag = '';
      } else {
        $this->validate = '';
        $this->scriptTag = '<script type="text/javascript" src="../js/form-validation.js"></script>';
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

      if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // Get Form Values and Validate
        if (empty($_POST['name']) || empty($_POST['email'])) {
          array_push($this->errors, 'Name and Email fields are required!');
        } else {
          $name = $this->validateForm($_POST['name']);
          $email = $this->validateForm($_POST['email']);
          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errors, 'You entered an invalid email!');
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
      // $subscribeForm->toggleJavascriptValidation(false);
      echo $subscribeForm->displayForm();
      if (isset($_POST['name'])) {
        $subscribeForm->handleSubmission();
        foreach ($subscribeForm->errors as $error ) {
          $errorHTML = '<p class-"error">' . $error . '</p>';
          echo $errorHTML;
        }  
      }
    ?>
    <p id="js-errors"></p>        
  </div>

  <?php
    echo $subscribeForm->scriptTag;
  ?>
  

</body>
</html>