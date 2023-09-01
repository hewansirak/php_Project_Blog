<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$database = "blog";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_create_db = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql_create_db) === false) {
    die("Error creating database: " . $conn->error);
}

$conn->select_db($database);

$sql_create_table = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    image_path VARCHAR(255)

)";

if ($conn->query($sql_create_table) === false) {
    die("Error creating table: " . $conn->error);
}

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $image_destination = null;


    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_destination = "uploads/" . $image_name; 

        if (move_uploaded_file($image_tmp_name, $image_destination)) {
        } else {
            echo "Error uploading image.";
            exit();
        }
    }


    $sql_check_username = "SELECT * FROM users WHERE username = '$username'";
    $result_check_username = $conn->query($sql_check_username);

    if ($result_check_username->num_rows > 0) {
        echo "Username already exists!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql_insert_user = "INSERT INTO users (username, password, first_name, last_name, email, image_path) VALUES ('$username', '$hashed_password', '$first_name', '$last_name', '$email', '$image_destination')";
        
        if ($conn->query($sql_insert_user) === true) {
            echo "Registered successfully!";
            session_start();
            $_SESSION["username"] = $username;
            header("Location: home.php");
            exit(); 
        } else {
            echo "Error registering user: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup Form</title>
</head>
<body>
    <h2>Signup</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="image">Profile Image (optional*):</label>
        <input type="file" id="image" name="image"><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" name="submit" value="Signup">
    </form>
</body>
</html>
