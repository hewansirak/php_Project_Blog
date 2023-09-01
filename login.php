<?php
session_start();

// hawsattroneys abinetschool akass.net mc

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $host = 'localhost';
    $user = 'root';
    $pass = '1234';
    $dbname = 'blog';

    $con = mysqli_connect($host, $user, $pass, $dbname);

    if (!$con) {
        die('Could not connect: ' . mysqli_connect_error());
    }

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user["password"])) {
            $_SESSION["username"] = $username;
            $_SESSION["first_name"] = $user["first_name"];
            $_SESSION["last_name"] = $user["last_name"];
            $_SESSION["image_path"] = $user["image_path"];

            header("Location: home.php");
            exit(); 
        } else {
            echo "Invalid credentials. <a href='login.php'>Try Again</a>";
        }
    } else {
        echo "Invalid credentials. <a href='login.php'>Try Again</a>";
    }

    mysqli_close($con);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>
