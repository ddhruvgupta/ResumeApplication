<?php
session_start();

require_once "connection.php";
require_once "bootstrap.php";
include "index_login.php";


if(isset($_SESSION['name'])){




        $sql="select * from profile";
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $table = "<table class='table'>";
        $table.= "<tr><b>
        <td>Name	</td>
        <td>Headline	</td>
        <td>Action	</td>
        </b></tr>";

        while($row = $statement->fetch(PDO::FETCH_ASSOC)){
          $table.= "<tr>";
          $table.= "<td><a href=\"view.php?profile_id=".$row['profile_id']."\">".$row['first_name']."</a></td>";
          $table.= "<td>".$row['headline']."</td>";

          $table.= "<td>
          <a href=\"edit.php?profile_id=".$row['profile_id']."\">Edit</a> |
          <a href='delete.php?profile_id=".$row['profile_id']."'>Delete</a>
          </td>";
          $table.= "</tr>";
        }
        $table.= "</table>";


  if(isset($_POST['add']) ){
    header("location:add.php");
    return;
  }

  if(isset($_POST['logout']) ){
    header("location:logout.php");
    return;
  }

}

?>
<html>
<head><title> Dhruv Gupta abf0931</title></head>
<body>
<p><h1>Welcome to the Resume Registry Database </br></h1></p></br>
<?php


if(isset($table))
  loginPass($table);//if table is set it will print the table
loginFail();//if user is not logged in, it will display a link to go to login page

 ?>


</body>
</html>
