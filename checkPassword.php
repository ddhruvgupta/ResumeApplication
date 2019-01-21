<?php
require_once "connection.php";

  function check($email, $pass){
    $salt = 'XyZzy12*_';

    $check = hash('md5', $salt.$pass);
    echo $pdo;
    $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
    $stmt->execute(array( ':em' => $email, ':pw' => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row===false){
      return(0);
    } else {

      $_SESSION['name'] = $row['name'];
      $_SESSION['user_id'] = $row['user_id'];
      return(1);
    };

  }
?>
