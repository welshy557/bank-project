<!DOCTYPE html>
<html>
<head>
    <title>TABLE</title>
</head>
<body>
<?php

 // database info and connection
 $db_user = "u137";
 $db_pass = "u137";
 $db_name = "db137";
 $servername = "localhost:3306";
 $conn = mysqli_connect($servername, $db_user, $db_pass, $db_name);
 if (!$conn) {
     die("Connection Failed " . mysqli_connect_error());
 }


$sql = "CREATE TABLE transactions(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date VARCHAR(30) NOT NULL,
    user VARCHAR(30) NOT NULL,
    transfer_user VARCHAR(30) NOT NULL,
    amount VARCHAR(30) NOT NULL,
    balance VARCHAR(30) NOT NULL
    )";
    
    if ($conn->query($sql) === TRUE) {
      echo "Table MyGuests created successfully";
    } else {
      echo "Error creating table: " . $conn->error;
    }
    
    $conn->close();
    ?>


?>

</body>