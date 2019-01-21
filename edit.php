<?php
session_start();
// require_once "connection.php";
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
    
    $table = preparePos($profile_id);
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
    $temp = validatePos();
    $temp2 = validateEdu();

    if($temp === true && $temp2 ===true){
      update($profile_id);
      updateProfiles($profile_id);
      updateEdu($profile_id);    
    } else{
      $_SESSION['error']=$temp;
      header("location: edit.php?profile_id=".$profile_id);
      return;
    }

 $rank=1;
      for($i=1; $i<=9; $i++){
        if(!isset($_POST['year'.$i]) )  continue;
        if(!isset($_POST['desc'.$i]) )  continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        InsertPos($profile_id,$rank,$year,$desc);
        $rank++;
      }


      insertEdu($profile_id);

  }

  $_SESSION['insert'] = "Profile updated";
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
  <title>Dhruv Gupta's Profile Edit abf09319</title>
  <!-- bootstrap.php - this is HTML -->

  <!-- Latest compiled and minified CSS -->

  <?php include 'jquery.php';?>
</head>

<body>

  <div class="container">
    <h1>Updating Profile</h1>
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
              </p>
              <p>
                Education: <input type="submit" id="addEdu" value="+">
                <div id="edu_fields">
                </div>
              </p>
              <p>
                Position: <input type="submit" id="addPos" value="+">
                <div id="position_fields">
                  <?php echo $table; ?>
                </div>
              </p>
              <p>
                <input type="submit" name="add" value="Save">
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
