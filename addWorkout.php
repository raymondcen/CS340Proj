<?php
session_start();
require_once "config.php";

if (isset($_SESSION["member_id"])) {
    $member_id = $_SESSION["member_id"];
    $username = $_SESSION["username"];
} else {
    header("location: error.php");
    exit();
}

$day = $month = $year = "";
$day_err = $month_err = $year_err = "";
$SQL_err = "";
$ment_rating = $phys_rating = "";
$ment_rating_err = $phys_rating_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ment_rating = trim($_POST["ment_rating"]);
    if (empty($ment_rating) || $ment_rating < 1 || $ment_rating > 5) {
        $ment_rating_err = "Please enter a valid mental rating (1-5).";
    }

    $phys_rating = trim($_POST["phys_rating"]);
    if (empty($phys_rating) || $phys_rating < 1 || $phys_rating > 5) {
        $phys_rating_err = "Please enter a valid physical rating (1-5).";
    }

    $day = trim($_POST["day"]);
    if (empty($day) || $day < 1 || $day > 31) {
        $day_err = "Please enter a valid day (1-31).";
    }

    $month = trim($_POST["month"]);
    if (empty($month) || $month < 1 || $month > 12) {
        $month_err = "Please enter a valid month (1-12).";
    }

    $year = trim($_POST["year"]);
    if (empty($year) || $year < 1900 || $year > date("Y")) {
        $year_err = "Please enter a valid year.";
    }

    if (empty($day_err) && empty($month_err) && empty($year_err) && empty($ment_rating_err) && empty($phys_rating_err)) {
        $check_sql = "SELECT workout_id FROM Workout WHERE member_id = ? AND day = ? AND month = ? AND year = ?";
        if ($stmt_check = mysqli_prepare($link, $check_sql)) {
            mysqli_stmt_bind_param($stmt_check, 'iiii', $param_member_id, $param_day, $param_month, $param_year);
            $param_member_id = $member_id;
            $param_day = $day;
            $param_month = $month;
            $param_year = $year;

            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                $SQL_err = "A workout for this date already exists. Please choose a different date.";
            } else {
                // Define the SQL query for inserting a workout
                $insert_sql = "INSERT INTO Workout (member_id, day, month, year) VALUES (?, ?, ?, ?)";
                if ($stmt_insert = mysqli_prepare($link, $insert_sql)) {
                    mysqli_stmt_bind_param($stmt_insert, 'iiii', $param_member_id, $param_day, $param_month, $param_year);
                    if (mysqli_stmt_execute($stmt_insert)) {
                        $workout_id = mysqli_insert_id($link);

                        // Define the SQL query for inserting the ratings
                        $rating_sql = "INSERT INTO Rating (workout_id, member_id, ment_rating, phys_rating) VALUES (?, ?, ?, ?)";
                        if ($stmt_rating = mysqli_prepare($link, $rating_sql)) {
                            mysqli_stmt_bind_param($stmt_rating, 'iiii', $workout_id, $param_member_id, $param_ment_rating, $param_phys_rating);
                            $param_ment_rating = $ment_rating;
                            $param_phys_rating = $phys_rating;

                            if (mysqli_stmt_execute($stmt_rating)) {
                                // Successful insert, redirect to viewWorkouts
                                header("Location: viewWorkouts.php");
                                exit(); // Make sure the script ends after redirect
                            } else {
                                $SQL_err = "Error inserting rating: " . mysqli_error($link);
                            }
                        }
                    } else {
                        $SQL_err = "Error inserting workout: " . mysqli_error($link);
                    }
                    mysqli_stmt_close($stmt_insert);
                }
            }
            mysqli_stmt_close($stmt_check);
        }
    }
    mysqli_close($link);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Workout</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <div class="page-header">
                        <h3>Add New Workout for <?php echo htmlspecialchars($username); ?></h3>
                    </div>

                    <!-- Display SQL Error if any -->
                    <?php if(!empty($SQL_err)) echo "<div class='alert alert-danger'>$SQL_err</div>"; ?>

                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($day_err)) ? 'has-error' : ''; ?>">
                            <label>Day</label>
                            <input type="number" name="day" class="form-control" min="1" max="31" value="<?php echo $day; ?>">
                            <span class="help-block"><?php echo $day_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($month_err)) ? 'has-error' : ''; ?>">
                            <label>Month</label>
                            <input type="number" name="month" class="form-control" min="1" max="12" value="<?php echo $month; ?>">
                            <span class="help-block"><?php echo $month_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($year_err)) ? 'has-error' : ''; ?>">
                            <label>Year</label>
                            <input type="number" name="year" class="form-control" min="1900" max="<?php echo date("Y"); ?>" value="<?php echo $year; ?>">
                            <span class="help-block"><?php echo $year_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($ment_rating_err)) ? 'has-error' : ''; ?>">
                            <label>Mental Rating (1-5)</label>
                            <input type="number" name="ment_rating" class="form-control" min="1" max="5" value="<?php echo $ment_rating; ?>">
                            <span class="help-block"><?php echo $ment_rating_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($phys_rating_err)) ? 'has-error' : ''; ?>">
                            <label>Physical Rating (1-5)</label>
                            <input type="number" name="phys_rating" class="form-control" min="1" max="5" value="<?php echo $phys_rating; ?>">
                            <span class="help-block"><?php echo $phys_rating_err; ?></span>
                        </div>

                        <div>
                            <input type="submit" class="btn btn-success" value="Add Workout">
                            <a href="viewWorkouts.php" class="btn btn-primary">Back to Workouts</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
