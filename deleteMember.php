<?php
session_start();
require_once "config.php";  

if (isset($_GET['member_id'])) {
    $member_id = $_GET['member_id'];

    $sql_delete_exercises = "DELETE FROM Exercise WHERE workout_id IN (SELECT workout_id FROM Workout WHERE member_id = ?)";
    if ($stmt = mysqli_prepare($link, $sql_delete_exercises)) {
        mysqli_stmt_bind_param($stmt, "i", $member_id);
        if (mysqli_stmt_execute($stmt)) {
            $sql_delete_ratings = "DELETE FROM Rating WHERE workout_id IN (SELECT workout_id FROM Workout WHERE member_id = ?)";
            if ($stmt_ratings = mysqli_prepare($link, $sql_delete_ratings)) {
                mysqli_stmt_bind_param($stmt_ratings, "i", $member_id);
                if (mysqli_stmt_execute($stmt_ratings)) {
                    $sql_delete_workouts = "DELETE FROM Workout WHERE member_id = ?";
                    if ($stmt_workouts = mysqli_prepare($link, $sql_delete_workouts)) {
                        mysqli_stmt_bind_param($stmt_workouts, "i", $member_id);
                        if (mysqli_stmt_execute($stmt_workouts)) {
                            $sql_delete_member = "DELETE FROM Member WHERE member_id = ?";
                            if ($stmt_member = mysqli_prepare($link, $sql_delete_member)) {
                                mysqli_stmt_bind_param($stmt_member, "i", $member_id);

                                if (mysqli_stmt_execute($stmt_member)) {
                                    echo "<script>alert('Member, workouts, ratings, and exercises deleted successfully');</script>";
                                    echo "<script>window.location.href = 'index.php';</script>";
                                    echo "ERROR: Could not delete member. " . mysqli_error($link);
                                }
                                mysqli_stmt_close($stmt_member);
                            } else {
                                echo "ERROR: Could not prepare query to delete member. " . mysqli_error($link);
                            }
                        } else {
                            echo "ERROR: Could not delete workouts. " . mysqli_error($link);
                        }

                        mysqli_stmt_close($stmt_workouts);
                    } else {
                        echo "ERROR: Could not prepare query to delete workouts. " . mysqli_error($link);
                    }
                } else {
                    echo "ERROR: Could not delete ratings. " . mysqli_error($link);
                }
                mysqli_stmt_close($stmt_ratings);
            } else {
                echo "ERROR: Could not prepare query to delete ratings. " . mysqli_error($link);
            }
        } else {
            echo "ERROR: Could not delete related exercises. " . mysqli_error($link);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "ERROR: Could not prepare query to delete related exercises. " . mysqli_error($link);
    }
} else {
    echo "ERROR: No member_id was provided.";
}

// Close the connection
mysqli_close($link);
?>
