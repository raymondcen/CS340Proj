<?php
    session_start();
    require_once "config.php";

    if (isset($_GET["workout_id"]) && !empty(trim($_GET["workout_id"]))) {
        $workout_id = $_GET["workout_id"];
    } else {
        header("location: error.php");
        exit();
    }

    if (isset($_SESSION["member_id"])) {
        $member_id = $_SESSION["member_id"];
        $username = $_SESSION["username"];
    } else {
        header("location: error.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exercises for Workout</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        .wrapper {
            width: 70%;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Exercises for Workout <?php echo htmlspecialchars($workout_id); ?></h2>
                        <a href="addExercises.php?workout_id=<?php echo $workout_id; ?>" class="btn btn-success pull-right">Add New Exercise</a>
                    </div>

                    <?php
                    $exercise_sql = "
                    SELECT 
                        e.exercise_name, 
                        e.reps, 
                        e.sets, 
                        e.rest_time, 
                        e.weights, 
                        IFNULL(mg.name, 'No muscle group') AS muscle_group
                    FROM 
                        Exercise e
                    LEFT JOIN Works w ON e.exercise_name = w.exercise_name
                    LEFT JOIN Muscle_Group mg ON w.muscle_id = mg.muscle_id
                    WHERE 
                        e.workout_id = ? 
                    ORDER BY 
                        e.exercise_name";

                    if ($stmt = mysqli_prepare($link, $exercise_sql)) {
                        mysqli_stmt_bind_param($stmt, "i", $workout_id);

                        if (mysqli_stmt_execute($stmt)) {
                            $result = mysqli_stmt_get_result($stmt);
                            if (mysqli_num_rows($result) > 0) {
                                echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>Exercise</th>";
                                echo "<th>Reps</th>";
                                echo "<th>Sets</th>";
                                echo "<th>Rest Time (sec)</th>";
                                echo "<th>Weights (kg)</th>";
                                echo "<th>Muscle Group</th>";
                                echo "<th>Action</th>"; 
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['exercise_name'] . "</td>";
                                    echo "<td>" . $row['reps'] . "</td>";
                                    echo "<td>" . $row['sets'] . "</td>";
                                    echo "<td>" . $row['rest_time'] . "</td>";
                                    echo "<td>" . ($row['weights'] ? $row['weights'] : 'N/A') . "</td>";
                                    echo "<td>" . $row['muscle_group'] . "</td>";
                                    echo "<td>";
                                   // echo "<a href='editExercise.php?exercise_name=" . $row['exercise_name'] . "&workout_id=" . $workout_id . "' title='Edit Exercise' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a> ";
                                    echo "<a href='deleteExercise.php?exercise_name=" . $row['exercise_name'] . "&workout_id=" . $workout_id . "' title='Delete Exercise' data-toggle='tooltip' onclick='return confirmDelete();'><span class='glyphicon glyphicon-trash'></span></a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }

                                echo "</tbody>";
                                echo "</table>";
                                mysqli_free_result($result);
                            } else {
                                echo "<p class='lead'><em>No exercises found for this workout.</em></p>";
                            }
                        } else {
                            echo "ERROR: Could not execute $exercise_sql. " . mysqli_error($link);
                        }
                    }

                    mysqli_stmt_close($stmt);
                    ?>
                
                    <!-- //confirm delete -->
                    <script type="text/javascript">
                        function confirmDelete() {
                            return confirm("Are you sure you want to delete this exercise from the workout?");
                        }
                    </script>

                    <p><a href="viewWorkouts.php" class="btn btn-primary">Back to Workouts</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Close connection
mysqli_close($link);
?>
