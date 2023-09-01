<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION["first_name"] . ' ' . $_SESSION["last_name"]; ?></h2>

    <?php
    if (!empty($_SESSION["image_path"])) {
        echo '<img src="' . $_SESSION["image_path"] . '" alt="User Image" width="200">';
    }
    ?>

    <button> <a href="logout.php">Logout</a> </button>
</body>
</html>
