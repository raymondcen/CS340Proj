<?php
session_start();
require_once "config.php";  // Include your database configuration file

// Check if the member_id is provided via URL
if (isset($_GET['member_id'])) {
    $member_id = $_GET['member_id'];

    // Delete all related exercises in the Exercise table
    $sql_delete_exercises = "DELETE FROM Exercise WHERE workout_id IN (SELECT workout_id FROM Workout WHERE member_id = ?)";
    if ($stmt = mysqli_prepare($link, $sql_delete_exercises)) {
        mysqli_stmt_bind_param($stmt, "i", $member_id);
        if (mysqli_stmt_execute($stmt)) {
            // Delete all related ratings in the Rating table (if applicable)
            $sql_delete_ratings = "DELETE FROM Rating WHERE workout_id IN (SELECT workout_id FROM Workout WHERE member_id = ?)";
            if ($stmt_ratings = mysqli_prepare($link, $sql_delete_ratings)) {
                mysqli_stmt_bind_param($stmt_ratings, "i", $member_id);
                if (mysqli_stmt_execute($stmt_ratings)) {
                    // Delete all related records in the Workout table
                    $sql_delete_workouts = "DELETE FROM Workout WHERE member_id = ?";
                    if ($stmt_workouts = mysqli_prepare($link, $sql_delete_workouts)) {
                        mysqli_stmt_bind_param($stmt_workouts, "i", $member_id);
                        if (mysqli_stmt_execute($stmt_workouts)) {
                            // Proceed to delete the member from the Member table
                            $sql_delete_member = "DELETE FROM Member WHERE member_id = ?";
                            if ($stmt_member = mysqli_prepare($link, $sql_delete_member)) {
                                // Bind the member_id parameter
                                mysqli_stmt_bind_param($stmt_member, "i", $member_id);

                                // Execute the statement to delete the member
                                if (mysqli_stmt_execute($stmt_member)) {
                                    // Redirect to the members list page with success message
                                    echo "<script>alert('Member, workouts, ratings, and exercises deleted successfully');</script>";
                                    echo "<script>window.location.href = 'index.php';</script>";  // Redirect to the members page
                                } else {
                                    echo "ERROR: Could not delete member. " . mysqli_error($link);
                                }

                                // Close the prepared statement for deleting member
                                mysqli_stmt_close($stmt_member);
                            } else {
                                echo "ERROR: Could not prepare query to delete member. " . mysqli_error($link);
                            }
                        } else {
                            echo "ERROR: Could not delete workouts. " . mysqli_error($link);
                        }

                        // Close the prepared statement for deleting workouts
                        mysqli_stmt_close($stmt_workouts);
                    } else {
                        echo "ERROR: Could not prepare query to delete workouts. " . mysqli_error($link);
                    }
                } else {
                    echo "ERROR: Could not delete ratings. " . mysqli_error($link);
                }

                // Close the prepared statement for deleting ratings
                mysqli_stmt_close($stmt_ratings);
            } else {
                echo "ERROR: Could not prepare query to delete ratings. " . mysqli_error($link);
            }
        } else {
            echo "ERROR: Could not delete related exercises. " . mysqli_error($link);
        }

        // Close the prepared statement for deleting exercises
        mysqli_stmt_close($stmt);
    } else {
        echo "ERROR: Could not prepare query to delete related exercises. " . mysqli_error($link);
    }
} else {
    // If member_id is not provided
    echo "ERROR: No member_id was provided.";
}

// Close the connection
mysqli_close($link);
?>
