 <?php // Do not put any HTML above this line
SESSION_START();

error_log("");
error_log("");
require_once "connection.php";
require_once "bootstrap.php";
include "checkPassword.php";


if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$user = 'umsi@umich.edu';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is meow123

$failure = false;  // If we have no POST data

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
          $failure = "Username and password are required";
		      error_log("Login fail ".$failure);
          $_SESSION['error']=$failure;
          header("Location: login.php");
          return;

    } else {


      $salt = 'XyZzy12*_';
      $check = hash('md5', $salt.$_POST['pass']);
      error_log("Username= ".$_POST['email']);
      error_log("Password= ".$_POST['pass']);
      error_log("hash1= ".$check);
      error_log("hash2= ".$stored_hash);

      $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :em AND password = :pw');
      $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));

      //$stmt = $pdo->prepare('SELECT * FROM users');
      //$stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
          error_log("Row= ".$row);
        if($row){
          error_log("Password Accepted");
          $_SESSION['name'] = $row['name'];
          $_SESSION['user_id'] = $row['user_id'];
          header("Location: index.php");
          return;
        }else {
                error_log("Row not found");
                $failure = "Incorrect password";
                error_log("Login fail ".$row['name'].$check);
                $_SESSION['error']=$failure;
                header("Location: login.php");
                return;
              }
				}
    }

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Dhruv Gupta Login Page</title>
</head>
<body>
  <script src="login.js"></script>
<div class="container">
<h1>Please Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
  }

?>
  <form method="POST">
    <label for="email">User Name</label>
    <input type="text" name="email" id="email"><br/>
    <label for="pass">Password</label>
    <input type="password" name="pass" id="pass"><br/>

    <input type="submit" value="Log In" onclick="return doValidate();">
    <input type="submit" name="cancel" value="Cancel">
  </form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the four character sound a cat
makes (all lower case) followed by 123. -->
</p>
</div>

</body>
</html>
