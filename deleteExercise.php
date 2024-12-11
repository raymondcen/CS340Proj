<?php
session_start();
require_once "config.php";

if (isset($_GET["exercise_name"]) && isset($_GET["workout_id"]) && !empty(trim($_GET["exercise_name"])) && !empty(trim($_GET["workout_id"]))) {
    $exercise_name = $_GET["exercise_name"];
    $workout_id = $_GET["workout_id"];
} else {
    header("location: error.php");
    exit();
}
mysqli_query($link, "SET FOREIGN_KEY_CHECKS = 0;");
$delete_exercise_sql = "DELETE FROM Exercise WHERE exercise_name = ? AND workout_id = ?";
if ($stmt = mysqli_prepare($link, $delete_exercise_sql)) {
    mysqli_stmt_bind_param($stmt, "si", $exercise_name, $workout_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_query($link, "SET FOREIGN_KEY_CHECKS = 1;");
        header("Location: viewExercises.php?workout_id=" . $workout_id);
        exit();
    } else {
        echo "ERROR: Could not execute $delete_exercise_sql. " . mysqli_error($link);
    }
    mysqli_stmt_close($stmt);
} else {
    echo "ERROR: Could not prepare query: $delete_exercise_sql. " . mysqli_error($link);
}

// Close the connection
mysqli_close($link);

?>