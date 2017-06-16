<?php
  require_once(realpath(dirname(__FILE__) . "/../config.php"));

  class Form {
    private $method;
    private $action;
    private $validate;
    public $errors;
    public $scriptTag;

    public function __construct($method, $action) {
      $this->method = $method;
      $this->action = htmlspecialchars($action);
      $this->validate = '';
      $this->errors = [];
      $this->scriptTag = '<script type="text/javascript" src="./js/form-validation.js"></script>';
    }

    // Method to remove HTML form validation and js validation
    public function toggleJavascriptValidation($boolean) {
      // if FALSE is passed remove script tags from form and add novalidate to html form element
      if ($boolean == false) {
        $this->validate = 'novalidate';
        $this->scriptTag = '';
      } 
      // else validation is TRUE, remove novalidate from html form element and replace script tag
      else {
        $this->validate = '';
        $this->scriptTag = '<script type="text/javascript" src="./js/form-validation.js"></script>';
      }
    }

    // Method to display subcribe form
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

    // Method to handle form submission
    public function handleSubmission(){
      global $conn;
      // if post request received
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // check that the form was not submitted empty
        if (empty($_POST['name']) || empty($_POST['email'])) {
          array_push($this->errors, 'Name and Email fields are required!');
        }
        // else not empty, validate inputs are safe and validate email is valid 
        else {
          $name = $this->validateForm($_POST['name']);
          $email = $this->validateForm($_POST['email']);
          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errors, 'You entered an invalid email!');
          }
          // everything is good, write data to db table
          else {
            $query = "INSERT INTO subscribers (name, email) VALUES ('$name', '$email')";
            if(mysqli_query($conn, $query)) {
              header('Location: ../resources/templates/subscribers.php');
            } else {
              echo 'ERROR: ' . mysqli_error($conn);
            }    
          }            
        }        
      }        
    }

    // Method to validate inputs
    private function validateForm($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
  }
?>



  
  <?php $subscribeForm = new Form('post', $_SERVER["PHP_SELF"]); ?>
  <div class="container">
    <?php
      // UNCOMMENT LINE BELOW TO REMOVE JS VALIDATION
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
  

