<?php
session_start();
//require_once "connection.php";
require_once "bootstrap.php";
include "updateValidate.php";

if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
  }

if(isset($_SESSION['name']) ){

  $profile_id = $_GET['profile_id'];
  $valid = validate($profile_id);


    if(isset($valid['first_name'])){
    $fname = $valid['first_name'];
    $lname = $valid['last_name'];
    $email = $valid['email'];
    $headline = $valid['headline'];
    $summary = $valid['summary'];
  }


  if(isset($_POST['add'])){



      //if($row!==false){

      if(strlen($_POST['first_name'])<1 ||strlen($_POST['last_name'])<1 ||strlen($_POST['email'])<1 ||
      strlen($_POST['headline'])<1 ||strlen($_POST['summary'])<1 ){

        $_SESSION['error']="All fields are required";
        header("location: add.php");
        return;

      }elseif (!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error']="Email address must contain @";
        header("location: add.php");
        return;
      }else {
          delete($profile_id);
      }

      $_SESSION['insert'] = "Profile Deleted";
      header("location: index.php");
      return;

  }

  if ( isset($_POST['cancel']) ) {
      header("location: index.php");
      return;
    }
}
?>

<html>

<head>
  <title>Dhruv Gupta's Profile delete abf09319 </title>
  <!-- bootstrap.php - this is HTML -->

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>

<body>
  <div class="container">
    <h1>Confirm Deletion <?php if(isset($fname))echo $fname ?>:</h1>
    <form method="post">
      <p>First Name:
        <input type="text" name="first_name" size="60" <?php if(isset($fname))echo "value=".$fname ?>></p>
      <p>Last Name:
        <input type="text" name="last_name" size="60" <?php if(isset($lname)) echo "value=".$lname ?>></p>
      <p>Email:
        <input type="text" name="email" size="30" <?php if(isset($email)) echo "value=".$email ?>></p>
      <p>Headline:<br/>
        <input type="text" name="headline" size="80" <?php if(isset($headline)) echo "value=".$headline ?>></p>
      <p>Summary:<br/>
        <textarea name="summary" rows="8" cols="80" ><?php if(isset($summary)) echo $summary ?></textarea>
        <p>
          <input type="submit" name="add" value="Delete">
          <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>
  </div>
</body>

</html>
