<?php
session_start();
require_once "config.php";

if (isset($_GET["workout_id"]) && !empty(trim($_GET["workout_id"]))) {
    $workout_id = $_GET["workout_id"];

    if (isset($_SESSION["member_id"])) {
        $member_id = $_SESSION["member_id"];
    } else {
        header("location: error.php");
        exit();
    }
    $delete_rating_sql = "DELETE FROM Rating WHERE workout_id = ? AND member_id = ?";
    if ($stmt_rating = mysqli_prepare($link, $delete_rating_sql)) {
        mysqli_stmt_bind_param($stmt_rating, "ii", $workout_id, $member_id);
        
        if (!mysqli_stmt_execute($stmt_rating)) {
            echo "ERROR: Could not delete ratings. " . mysqli_error($link);
            exit();
        }

        mysqli_stmt_close($stmt_rating);
    }

    $delete_workout_sql = "DELETE FROM Workout WHERE workout_id = ? AND member_id = ?";
    if ($stmt_workout = mysqli_prepare($link, $delete_workout_sql)) {
        mysqli_stmt_bind_param($stmt_workout, "ii", $workout_id, $member_id);

        if (mysqli_stmt_execute($stmt_workout)) {
            header("location: viewWorkouts.php");
            exit();
        } else {
            echo "ERROR: Could not delete workout. " . mysqli_error($link);
        }

        mysqli_stmt_close($stmt_workout);
    }

} else {
    header("location: viewWorkouts.php");
    exit();
}

// Close the connection
mysqli_close($link);
?>
