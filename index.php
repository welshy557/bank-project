<!DOCTYPE html>
<html>
<head>
    <title>Island Bank</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="outer">
    <div class="middle">
        <div class="inner">
            <br><div class="title">Welcome To The <br><span style="color: green;"><strong>Island Bank</strong></span></div>
            <hr>
            <div class="form_header">Sign In:</div>
            <form action="" method="post">
                <br><input type="text" name="name" placeholder="username" required>
                <br><input type="password" name="pass" placeholder="password" required>
                <br><input class ="button" type="submit" name="submit" value="Sign In"/>
                <br><p>Not already signed up? <a href=reg.php>Register</a></p>
            </form>
        <div>
            <?php
                session_start();
                // database info
                $db_user = "u137";
                $db_pass = "u137";
                $db_name = "db137";
                $servername = "localhost";
                $conn = mysqli_connect($servername, $db_user, $db_pass, $db_name);

                // checks database connection
                if (!$conn) {
                    die("Connection Faliled" . mysqli_connect_error());
                }
                
                // checks if submit button is pressed
                if (isset($_POST['submit'])) {
                    $username = $_POST["name"];
                    $password = $_POST["pass"];
	                // Checking if user exist in the database or not
                    $query = "SELECT * FROM `users` WHERE name='$username' and pass='$password'";
                    $result = mysqli_query($conn,$query) or die(mysql_error());
                    $rows = mysqli_num_rows($result);
                    if($rows==1) {
                        $_SESSION['username'] = $username;
                        // User is logged in. Redirects user to home.php
	                    header("Location: home.php");
                    } else {
                        // Error msg for incorrect username/password
	                    echo "<div><h3>Username/password is incorrect.</h3></div>";
	                }
                }
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
