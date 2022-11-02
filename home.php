<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
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
    $query = "SELECT * FROM `users` WHERE name='$name'";
    $result = mysqli_query($conn,$query) or die(mysql_error());
    $row = mysqli_fetch_assoc($result);
    $balance = $row["balance"];
    
?>

<div class="heading">Island Banking</div>


<div class="outer">
<div class="button_wrapper">
    <a class="button" href="logs.php">Transactions</a>
    <a class="button" href="logout.php">Log Out</a>
</div>
    <div class="middle">
        <div class="inner">
            <!-- Welcome message to user -->
            <?php echo "<br><span class='title'> Welcome " . $name . ", to <br><span style='color: green; font-weight: bold;'>Island Banking!</span><br><span id='balance'>Balance: <strong>$" . $balance . "</span></span>"?>

            <hr><div><h2 style="padding-top: 0">Transfer Funds:</h2></div>

            <form action="home.php" method="post">
                <div class="formwrap">
                    <!-- Transfer Amount Input -->
                    <label>Amount ($CAD):</label>
                    <input class="input" style="width: 100px" type="number" name="transferamount" step=".01">

                    <!-- Transfer User Input -->
                    <br><label>Transfer To:</label>
                    <select class="input" name="transfername">
                        <option selected>Name</option>

                        <?php
                            // Adds all registered users except logged in user to drop down list
                            $query = "SELECT * FROM `users`";
                            $result = mysqli_query($conn, $query) or die(mysql_error());
                            $json = mysqli_fetch_all ($result, MYSQLI_ASSOC);
                            
                            if (count($json) == 1) {
                                echo "<option>" . "No Users Registered" . "</option>";
                            }
                            foreach ($json as $user) {
                                if ($user['name'] != $name){
                                    echo "<option>" . $user['name'] . "</option>";
                                }
                            }
                        ?>

                    </select>
                </div>
                <input class ="button" type="submit" name="submit" value="Transfer Funds"/>
            </form>
            
            <?php
                // If submit button is clicked
                if (isset($_POST['submit'])) {
                    $transfername = $_POST["transfername"];
                    $transferamount = $_POST['transferamount'];
                    
                    // Error for when no user is picked
                    if ($transfername == "Name") {
                        echo "Please pick a user";
                    }
                    
                    // Error when there are no other users to transfer to
                    else if ($transfername == "No Users Registered") {
                        echo "No users registered to transfer funds to";
                    }

                    // Error for when user has infsufficent funds to transfer
                    else if ((float)$balance - (float)$transferamount < 0) {
                        echo "Insufficient Funds";
                    }

                    // Error for when user enters a negative number
                    else if ((float)$transferamount <= 0) {
                        echo "Invalid Transfer Amount of $". $transferamount;
                    } else {
                        // query transfer user from database
                        $query = "SELECT * FROM `users` WHERE name='$transfername'";
                        $result = mysqli_query($conn,$query) or die(mysql_error());
                        $row_2 = mysqli_fetch_assoc($result);
                    
                        // Updates balance
                        $balance -= (float)$transferamount;
                    
                        // Transfer User Info
                        $transferbalance = $row_2["balance"];
                        $deposit = (float)$transferbalance + (float)$transferamount;
                        
                        // Withdrawls money from user and deposits to selected account.
                        mysqli_query($conn, "UPDATE users SET balance=$balance WHERE name='$name'") or die(mysql_error());
                        mysqli_query($conn, "UPDATE users SET balance=$deposit WHERE name='$transfername'") or die(mysql_error());
                        

                        

                        // Inserts transactions into logs
                        $date = date('m/d/Y', time());
                        mysqli_query($conn, "INSERT INTO transactions (date, type, user, transfer_user, amount, balance) VALUES('$date', 'Withdrawl', '$name', '$transfername', '$$transferamount', '$$balance')");
                        mysqli_query($conn, "INSERT INTO transactions (date, type, user, transfer_user, amount, balance) VALUES('$date', 'Deposit', '$transfername', '$name', '$$transferamount', '$$deposit')");
                        // Sets confirmation message and refreshes page
                        $_SESSION['confirm'] = "<br>SUCCESFULLY TRANSFERED $". $transferamount. " TO ". '"'. $transfername. '"';
                        header("Refresh:0");
                    }   
                }
                
                // Displays confirmation message if transfer was made
                if (isset($_SESSION['confirm'])) {
                    echo $_SESSION['confirm'];
                }

            ?>
        </div>
    </div>
</div>

<script>
    // Removes form resubmission when refreshing page
    if (window.history.replaceState) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>     
    

</body>
</html>
