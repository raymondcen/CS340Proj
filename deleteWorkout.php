<?php
session_start();
require_once "config.php";

if (isset($_GET["workout_id"]) && !empty(trim($_GET["workout_id"]))) {
    $workout_id = $_GET["workout_id"];

    $delete_sql = "DELETE FROM Workout WHERE workout_id = ? AND member_id = ?";

    if (isset($_SESSION["member_id"])) {
        $member_id = $_SESSION["member_id"];
    } else {
        header("location: error.php");
        exit();
    }

    if ($stmt = mysqli_prepare($link, $delete_sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $workout_id, $member_id);

        if (mysqli_stmt_execute($stmt)) {
            header("location: viewWorkouts.php");
            exit();
        } else {
            echo "ERROR: Could not execute $delete_sql. " . mysqli_error($link);
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    }
} else {
    header("location: viewWorkouts.php");
    exit();
}

// Close the connection
mysqli_close($link);
?>
