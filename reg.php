
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="outer">
    <div class="middle">
        <div class="inner">
            <br><div class="title">Welcome To The<br><span style="color: green;"><strong>Island Bank</strong></span></div>
            <hr>
            <div class="form_header">Please fill in all details to register</div>
            <form action="reg.php" method="post">
                <br><input type="text" name="name" placeholder="username" required>
                <br><input type="password" name="pass" placeholder="password" required>
                <br><input type="password" name="cpass" placeholder="confirm password" required>
                <br><input class ="button" type="submit" name="submit" value="Sign Up"/>
                <p>Already have an account? <a href="index.php">Sign in</a>
            </form>

        <div>
            <?php

                // database info and connection
                $db_user = "u137";
                $db_pass = "u137";
                $db_name = "db137";
                $servername = "localhost";
                $conn = mysqli_connect($servername, $db_user, $db_pass, $db_name);
                if (!$conn) {
                    die("Connection Failed " . mysqli_connect_error());
                }

                // If submit button is clicked
                if (isset($_POST['submit'])) {
                    // Checks if password and confirm password match
                    if ($_POST["pass"] == $_POST["cpass"]){
                        $username = $_POST["name"];
                        $pass = $_POST["pass"];

                        // Checking if username already exist
                        $query = "SELECT * FROM users WHERE name='$username'";
                        $result = mysqli_query($conn,$query) or die(mysql_error());
                        $rows = mysqli_num_rows($result);                   
                        if ($rows == 1) {
                            echo "Sorry this username already exist";
                        }else if ($rows > 1){ // If there is more then 1 user with account name (Should never happen unless done manually through database)
                            echo "<span style='color: red;'>***CRITICAL ERROR***<br>CONTACT SITE ADMINISTRATOR</span>";
                        }else if (!preg_match("#^[a-zA-Z0-9]+$#", $username)){ // if username contains special characters
                            echo "No Special Characters or spaces";
                        }else if (is_numeric(substr($username, 0, 1))) { // if username starts with a digit
                            echo "Username must start with a letter";
                        }else{
                            // Adds user to database
                            $sql = "INSERT INTO users (name, pass) VALUES('$username', '$pass')";
                            if (mysqli_query($conn, $sql)) {
                                echo "Thank you " . $username . ", your account has been created. <br>You can now sign in <a href='index.php'>here</a>";
                            } else {
                                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                            }
                        }
                        
                      // Error for passwords not matching
                    } else echo "<span style='color: red;'>Passwords do not match</span>";
                } 
                mysqli_close($conn);
            ?>
	    </div>

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
