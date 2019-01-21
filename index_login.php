<?php


function loginFail(){
    if(!isset($_SESSION['name'])){
        echo"
        <a href='login.php'>Please log in</a>
        <p>
        Attempt to go to <a href='add.php'>add.php</a> without logging in - it should fail with an error message.
        </p>
        <p>
        <a href='https://www.wa4e.com/assn/autosess/'>Specification for this Application</a>
        </p>";
    }
    return;
}

function loginPass($table){
    if(isset($_SESSION['name'])){
      if(isset($_SESSION['insert']) ){
          echo "<div class='col-md-4 col-md-offset-5'><p class='text-success'>".$_SESSION['insert']."</p></div>";
          unset($_SESSION['insert']);
        }

      if(isset($_SESSION['error']) ){
            echo "<div class='col-md-4 col-md-offset-5'><p class='text-danger'>".$_SESSION['error']."</p></div>";
            unset($_SESSION['error']);
          }

          if(isset($table) ){
            echo $table;
          }
          echo "
          <div class='form-group'>
          <a href='add.php'>Add New Entry</a> </br>
          <a href='logout.php'>Logout</a>
          </div>   ";
        }
return;
}
?>
