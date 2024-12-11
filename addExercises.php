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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $exercise_name = trim($_POST["exercise_name"]);
    $reps = trim($_POST["reps"]);
    $sets = trim($_POST["sets"]);
    $rest_time = !empty(trim($_POST["rest_time"])) ? trim($_POST["rest_time"]) : NULL;
    $weights = !empty(trim($_POST["weights"])) ? trim($_POST["weights"]) : NULL;

    // Validate form data
    if (empty($exercise_name) || empty($reps) || empty($sets)) {
        echo "<p class='text-danger'>Please fill all required fields.</p>";
    } else {
        // Insert exercise data into the Exercise table
        $insert_sql = "INSERT INTO Exercise (exercise_name, reps, sets, rest_time, weights, workout_id) 
                       VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $insert_sql)) {
            mysqli_stmt_bind_param($stmt, "siiiii", $exercise_name, $reps, $sets, $rest_time, $weights, $workout_id);

            if (mysqli_stmt_execute($stmt)) {
                // Redirect to the exercises page after adding the exercise
                header("Location: viewExercises.php?workout_id=" . $workout_id);
                exit(); // Ensure the script stops executing after redirection
            } else {
                echo "<p class='text-danger'>Error: Could not add exercise. " . mysqli_error($link) . "</p>";
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Exercise to Workout</title>
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
                        <h2 class="pull-left">Add Exercise for Workout <?php echo htmlspecialchars($workout_id); ?></h2>
                        <a href="viewExercises.php?workout_id=<?php echo $workout_id; ?>" class="btn btn-primary pull-right">Back to Exercises</a>
                    </div>

                    <!-- Exercise Add Form -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?workout_id=' . $workout_id; ?>" method="post">
                        <div class="form-group">
                            <label for="exercise_name">Exercise Name:</label>
                            <select class="form-control" name="exercise_name" id="exercise_name" required>
                                <?php
                                // Fetch unique exercise names from the Exercise table
                                $exercise_sql = "SELECT DISTINCT exercise_name FROM Works";
                                if ($result = mysqli_query($link, $exercise_sql)) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='" . $row['exercise_name'] . "'>" . $row['exercise_name'] . "</option>";
                                    }
                                    mysqli_free_result($result);
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="reps">Reps:</label>
                            <input type="number" name="reps" class="form-control" id="reps" required>
                        </div>
                        <div class="form-group">
                            <label for="sets">Sets:</label>
                            <input type="number" name="sets" class="form-control" id="sets" required>
                        </div>
                        <div class="form-group">
                            <label for="rest_time">Rest Time (sec):</label>
                            <input type="number" name="rest_time" class="form-control" id="rest_time">
                        </div>
                        <div class="form-group">
                            <label for="weights">Weights (kg):</label>
                            <input type="number" step="0.01" name="weights" class="form-control" id="weights">
                        </div>
                        <button type="submit" class="btn btn-success">Add Exercise</button>
                    </form>


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
