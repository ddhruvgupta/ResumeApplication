<?php
include 'connection.php';
include 'bootstrap.php';

$profile_id = $_GET['profile_id'];

$sql = 'select * from profile where profile_id=:pid';
$statement = $pdo->prepare($sql);
$statement->execute(array(':pid'=>$profile_id));
$row = $statement->fetch(PDO::FETCH_ASSOC);



$sql = 'select * from position where profile_id=:pid';
$statement = $pdo->prepare($sql);
$statement->execute(array(':pid'=>$profile_id));

$table = '';
while($row2 = $statement->fetch(PDO::FETCH_ASSOC)){
  $table.="<li>".$row2['year'].":".$row2['description']."</li>";
}



$sql = 'select * from education edu 
 inner join institution ins on ins.institution_id = edu.institution_id
 where profile_id= :pid';

$statement = $pdo->prepare($sql);
$statement->execute(array(':pid'=>$profile_id));
$table2 = '';
while($row3 = $statement->fetch(PDO::FETCH_ASSOC)){
  $table2.="<li>".$row3['year'].":".$row3['name']."</li>";
}

?>


<html>
<body>
  <div class='container'>
     <div class="mt-auto" style="width:50px"></div>
     <div class="row">
    <p>First Name: <?php echo $row['first_name']?></p>
    <p>Last Name: <?php echo $row['last_name']?></p>
    <p>Email: <?php echo $row['email']?></p>
    <p>Headline: <?php echo $row['headline']?></p>
    <p>Summary: <?php echo $row['summary']?></p>
    <p>Education: </p>
    <p>
      <ul>
        <?php echo $table2; ?>
      </ul>
    </p>

    <p>Position: </p>
    <p>
      <ul>
        <?php echo $table; ?>
      </ul>
    </p>
    <a href="index.php">done</a>
    </div>
  </div>
</body>
</html>
