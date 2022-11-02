<!DOCTYPE html>
<html>
<head>
    <title>Transaction Logs</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    
<?php
    session_start();

    // Redirects user to login page if not logged in
    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
    }

    // database info and connection
    $db_user = "u137";
    $db_pass = "u137";
    $db_name = "db137";
    $servername = "localhost";
    $conn = mysqli_connect($servername, $db_user, $db_pass, $db_name);
    
    // Getting account balance of user from database
    $name = $_SESSION['username'];
    $query = "SELECT * FROM `transactions` WHERE user='$name' ORDER BY id DESC";
    $result = mysqli_query($conn,$query) or die(mysql_error());
    
    // Unsets any transfer confirmation messages for when user returns back to transfer.
    if (isset($_SESSION['confirm'])) {
        unset($_SESSION['confirm']);
    }
?>

<div class="heading">Island Banking</div>

    <div class="button_wrapper">
        <a class="button" href="home.php">Transfer Funds</a>
        <a class="button" href="logout.php">Log Out</a>
</div>

<div class="table_wrap">
    <table>
        <caption>Displaying Recent Transactions for <?php echo $name; ?></caption>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Transfered To/From</th>
            <th>Amount</th>
            <th>Remaining Balance</th>
        </tr>
        <?php

           if (mysqli_num_rows($result) == 0) {
               print("<td colspan='6'>***NO TRANSACTIONS***</td>");
            } else {

            // fetch each record in result set
            
            while ($row = mysqli_fetch_row($result)){
                // build table to display results
                //echo $row[2];
                $count = 0;
                print( "<tr>" );
                foreach ( $row as $value ) {
                    if ($value == $name || $value == $row[0]) {
                        continue;
                    }

                    if ($count == 3) {
                        if ($row[2] == 'Deposit') {
                            print("<td>+$value</td>");
                        } else {
                            print("<td>-$value</td>");
                        }
                        
                    } else {
                        print("<td>$value</td>");
            
                    }
                    $count += 1;
                }
                print( "</tr>" );
            }
            
        }
        ?> 
    </table>
    

</div>

</body>


</body>
</html>