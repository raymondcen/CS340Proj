<?php
    session_start();
    // Include config file
    require_once "config.php";

    // Check existence of id parameter
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        $_SESSION["member_id"] = $_GET["id"];
        $_SESSION["username"] = $_GET["username"];
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
    <title>Workouts</title>
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
                        <h2 class="pull-left">Workouts for <?php echo htmlspecialchars($username); ?></h2>
                        <a href="addWorkout.php" class="btn btn-success pull-right">Add New Workout</a>
                    </div>

                    <?php
                    // Fetch workouts for the member
                    $workout_sql = "
                        SELECT 
                            workout_id, 
                            CONCAT(year, '-', LPAD(month, 2, '0'), '-', LPAD(day, 2, '0')) AS formatted_date 
                        FROM 
                            Workout 
                        WHERE 
                            member_id = ? 
                        ORDER BY 
                            year DESC, month DESC, day DESC";

                    if ($stmt = mysqli_prepare($link, $workout_sql)) {
                        mysqli_stmt_bind_param($stmt, "i", $member_id);

                        if (mysqli_stmt_execute($stmt)) {
                            $result = mysqli_stmt_get_result($stmt);
                            if (mysqli_num_rows($result) > 0) {
                                echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th width='15%'>Workout ID</th>";
                                echo "<th>Date</th>";
                                echo "<th>Actions</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['workout_id'] . "</td>";
                                    echo "<td>" . $row['formatted_date'] . "</td>";
                                    echo "<td>";
                                    echo " <a href='viewExercises.php?workout_id=" . $row['workout_id'] . "' title='View Exercises' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                    echo " <a href='deleteWorkout.php?workout_id=" . $row['workout_id'] . "' title='Delete Workout' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                
                                    echo "</td>";
                                    echo "</tr>";
                                }

                                echo "</tbody>";
                                echo "</table>";
                                mysqli_free_result($result);
                            } else {
                                echo "<p class='lead'><em>No workouts were found.</em></p>";
                            }
                        } else {
                            echo "ERROR: Could not execute $workout_sql. " . mysqli_error($link);
                        }
                    }

                    // Close statement
                    mysqli_stmt_close($stmt);
                    ?>

                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
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
