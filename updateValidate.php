<?php
//;session_start();

function validate($profile_id){
  require_once "connection.php";
  $stmt = $pdo->prepare('Select * from profile where profile_id=:pid limit 1');
  $stmt->execute(array(':pid'=>$profile_id));
  $row=$stmt->fetch(PDO::FETCH_ASSOC);


  if($row){

    error_log("returning :".$row['first_name']);
    return $row;
  }else{
    $_SESSION['error']='Could not load profile';
    header("location: index.php");
    return;
  }

}

function update($profile_id){
  include "connection.php";

  $stmt = $pdo->prepare('update profile set
        user_id = :uid,
        first_name = :fn,
        last_name = :last,
        email = :em,
        headline = :he,
        summary= :su
        where profile_id=:pid'
      );

      // error_log("User ID=".$_SESSION['user_id']);
      // error_log("firstname = ".$_POST['first_name']);
      // error_log("last = ".$_POST['last_name']);
      // error_log("email = ".$_POST['email']);
      // error_log("summary = ".$_POST['summary']);


  $stmt->execute(array(
    ':uid' => $_SESSION['user_id'],
    ':fn' => $_POST['first_name'],
    ':last' => $_POST['last_name'],
    ':em' => $_POST['email'],
    ':he' => $_POST['headline'],
    ':su' => $_POST['summary'],
    ':pid'=> $profile_id )
  );

    return;
}

function delete($profile_id){
  include "connection.php";

  $stmt = $pdo->prepare('Delete from profile where profile_id=:pid');
  $stmt->execute(array(':pid'=> $profile_id ) );
  return;
}

function validatePos(){
  for($i=1; $i<=9;$i++){
    if(!isset($_POST['year'.$i]) )  continue;
    if(!isset($_POST['desc'.$i]) )  continue;
    if(strlen($_POST['year'.$i])==0 ||strlen($_POST['desc'.$i])==0){
      return "All fields are required";
    }
    if(! is_numeric($_POST['year'.$i])){
      return "Position year must be numberic";
    }
  }
  return true;
}

function validateEdu(){
  for($i=1; $i<=9;$i++){
    if(!isset($_POST['edu_year'.$i]) )  continue;
    if(!isset($_POST['edu_school'.$i]) )  continue;
    if(strlen($_POST['edu_year'.$i])==0 ||strlen($_POST['edu_school'.$i])==0){
      return "All fields are required";
    }
    if(! is_numeric($_POST['edu_year'.$i])){
      return "Education year must be numberic";
    }
  }
  return true;
}

function preparePos($profile_id){
  include "connection.php";
  $table = "";

  $sql = "SELECT * from position where profile_id=:pid";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':pid'=> $profile_id ) );
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $table.=  "<div id='position";
      $table.= $row['rank']."'>";
      $table.=  "<p>Year: <input type='text' name='year" . $row['rank'] . "' value='" . $row['year'] ."'>";
      $table.=  "<input type='button' value='-' ";
      $table.=  "onclick=\"$('#position".$row['rank']."').remove();return false;\"></p>";
      $table.=  "<textarea name='desc".$row['rank']."' rows='8' cols='80'>".$row['description']."</textarea>";
      $table.=  "</div>";
    }


    error_log($table);
    return $table;
}

function InsertPos($profile_id,$rank,$year,$desc){
  include "connection.php";
  $stmt = $pdo->prepare('insert into position (profile_id,rank,year,description)
          VALUES (:pid, :r, :year, :desc)');
  $stmt->execute(array(':pid' => $profile_id,
   ':r' => $rank,
   ':year' =>$year,
   ':desc' => $desc));
}

function updateProfiles($profile_id){
  include 'connection.php';

  $sql = "DELETE from position where profile_id=:pid";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':pid'=> $profile_id ) );
  return;
}

function updateEdu($profile_id){
  include 'connection.php';

  $sql = "DELETE from Education where profile_id=:pid";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':pid'=> $profile_id ) );
  return;
}

function countProfiles($profile_id){
  include 'connection.php';

  $sql = "SELECT count(*) as count from position where profile_id=:pid";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':pid'=> $profile_id ) );
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  return $row['count'];
}

function insertSchool($pdo,$name){
  $sql = 'SELECT institution_id FROM Institution WHERE name in (:prefix)';
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':prefix'=> $name ) );
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    return $row['institution_id'];
  }

  $sql = "INSERT INTO Institution (name) VALUES (:prefix)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':prefix'=> $name ) );
  $school_id = $pdo->lastInsertId();

  return $school_id;
}

function insertEdu($profile_id){
  include 'connection.php';
  $rank=1;
    for($i=1; $i<=9; $i++){
      if(!isset($_POST['edu_year'.$i]) )  continue;
      if(!isset($_POST['edu_school'.$i]) )  continue;


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
return;
}

?>
