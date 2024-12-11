<?php
session_start();
require_once "config.php";  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];

    $sql_check = "SELECT member_id FROM Member WHERE username = ?";
    if ($stmt_check = mysqli_prepare($link, $sql_check)) {
        mysqli_stmt_bind_param($stmt_check, "s", $username);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $username_error = "Username already exists. Please choose another.";
        } else {
            $sql = "INSERT INTO Member (username, password, fname, lname) VALUES (?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssss", $username, $password, $fname, $lname);

                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('New member added successfully');</script>";
                    echo "<script>window.location.href = 'index.php';</script>";  
                } else {
                    echo "ERROR: Could not execute query. " . mysqli_error($link);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "ERROR: Could not prepare the query. " . mysqli_error($link);
            }
        }
        mysqli_stmt_close($stmt_check);
    } else {
        echo "ERROR: Could not prepare the query. " . mysqli_error($link);
    }
}


mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Member</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        .wrapper {
            width: 50%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="page-header">
                <h2>Add New Member</h2>
            </div>
            <?php 
                if (isset($username_error)) {
                    echo "<div class='alert alert-danger'>$username_error</div>";
                }
            ?>
            <form action="addMembers.php" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="fname" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="lname" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Member</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
