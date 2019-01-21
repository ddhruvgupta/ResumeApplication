<?php
session_start();
require_once "connection.php";
include 'updateValidate.php';

if ( isset($_SESSION['error']) ) {
  echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
  unset($_SESSION['error']);
}

if(isset($_SESSION['name']) ){
  if(isset($_POST['add'])){
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $headline = $_POST['headline'];
    $summary = $_POST['summary'];

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
    $stmt = $pdo->prepare('INSERT INTO Profile
      (user_id, first_name, last_name, email, headline, summary)
      VALUES ( :uid, :fn, :last, :em, :he, :su)');

    $stmt->execute(array(
      ':uid' => $_SESSION['user_id'],
      ':fn' => $_POST['first_name'],
      ':last' => $_POST['last_name'],
      ':em' => $_POST['email'],
      ':he' => $_POST['headline'],
      ':su' => $_POST['summary']));
  }

  $profile_id = $pdo->lastInsertId(); // Get profile id of newly created user

  $temp = validatePos(); // Sanitize Position input
  $temp_2 = validateEdu();

  if($temp_2 === true){
    $rank=1;
    for($i=1; $i<=9; $i++){
      if(!isset($_POST['edu_year'.$i]) )  continue;
      if(!isset($_POST['edu_school'.$i]) )  continue;

      // if(is_numeric($_POST['year'.$i]) ){
      //   continue;
      // }else{
      //   $_SESSION['error']='Year must be numeric';
      //   header('location: add.php');
      //   return;
      // }



      $year = $_POST['edu_year'.$i];
      $name = $_POST['edu_school'.$i];

      $school_id = insertSchool($pdo,$name);

      $stmt = $pdo->prepare('INSERT into education (profile_id,institution_id,rank,year)
        VALUES (:pid, :school_id ,:rank, :year)');
      $stmt->execute(array(':pid' => $profile_id,
        ':school_id' => $school_id,
        ':rank' => $rank,
        ':year' =>$year
      ));
      $rank++;
    }
  } else{
    $_SESSION['error']=$temp_2;
    header("location: add.php");
    return;
  }

  if($temp === true){
    $rank=1;
    for($i=1; $i<=9; $i++){
      if(!isset($_POST['year'.$i]) )  continue;
      if(!isset($_POST['desc'.$i]) )  continue;
      
      // if(is_numeric($_POST['year'.$i]) ){
      //   continue;
      // }else{
      //   $_SESSION['error']='Year must be numeric';
      //   header('location: add.php');
      //   return;
      // }

      $year = $_POST['year'.$i];
      $desc = $_POST['desc'.$i];

      $stmt = $pdo->prepare('INSERT into position (profile_id,rank,year,description)
        VALUES (:pid, :rank, :year, :des)');
      $stmt->execute(array(':pid' => $profile_id,
       ':rank' => $rank,
       ':year' =>$year,
       ':des' => $desc));
      $rank++;
    }
  } else{
    $_SESSION['error']=$temp;
    header("location: add.php");
    return;
  }


  $_SESSION['insert'] = "Profile added";
  header("location: index.php");
  return;

}

if ( isset($_POST['cancel']) ) {
  header("location: index.php");
  return;
}
}


?>

<!DOCTYPE html>
<html>

<head>
  <title>Dhruv Gupta's Profile Add abf09319</title>
  <!-- bootstrap.php - this is HTML -->

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet"
  href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
  integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
  crossorigin="anonymous">

  <link rel="stylesheet"
  href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
  integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
  crossorigin="anonymous">

  <link rel="stylesheet" 
  href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" 
  integrity="sha384-xewr6kSkq3dBbEtB6Z/3oFZmknWn7nHqhLVLrYgzEFRbU/DHSxW7K3B44yWUN60D" 
  crossorigin="anonymous">

  <script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>

  <script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>

</head>
<body>

  <div class="container">
    <h1>Adding Profile</h1>
    <form method="POST">
      <p>First Name:
        <input type="text" id='first_name' name="first_name" size="60" <?php if(isset($fname))echo "value=".$fname ?> /></p>
        <p>Last Name:
          <input type="text" name="last_name" size="60" <?php if(isset($lname)) echo "value=".$lname ?>/></p>
          <p>Email:
            <input type="text" name="email" size="30" <?php if(isset($email)) echo "value=".$email ?>/></p>
            <p>Headline:<br/>
              <input type="text" name="headline" size="80" <?php if(isset($headline)) echo "value=".$headline ?>/></p>
              <p>Summary:<br/>
                <textarea name="summary" rows="8" cols="80"><?php if(isset($summary)) echo $summary ?></textarea>
              </p>
              <p>
                Education: <input type="submit" id="addEdu" value="+">
                <div id="edu_fields">
                </div>
              </p>
              <p>
                Position: <input type="submit" id="addPos" value="+">
                <div id="position_fields">
                </div>
              </p>
              <p>
                <input type="submit" name="add" value="Add">
                <input type="submit" name="cancel" value="Cancel">
              </p>
            </form>
            <script>
              countPos = 0;
              countEdu = 0;

// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
$(document).ready(function(){
  window.console && console.log('Document ready called');

  $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
        event.preventDefault();
        if ( countPos >= 9 ) {
          alert("Maximum of nine position entries exceeded");
          return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append(
          '<div id="position'+countPos+'"> \
          <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
          <input type="button" value="-" onclick="$(\'#position'+countPos+'\').remove();return false;"><br>\
          <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
          </div>');
      });

  $('#addEdu').click(function(event){
    event.preventDefault();
    if ( countEdu >= 9 ) {
      alert("Maximum of nine education entries exceeded");
      return;
    }
    countEdu++;
    window.console && console.log("Adding education "+countEdu);

    $('#edu_fields').append(
      '<div id="edu'+countEdu+'"> \
      <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" /> \
      <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
      <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value="" />\
      </p></div>'
      );

    $('.school').autocomplete({
      source: "school.php"
    });

  });

});

</script>
</div>
</body>

</html>