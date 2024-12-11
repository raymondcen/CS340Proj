<?php
	session_start();	
	require_once "config.php";

$username = $password = $fname = $lname = "";
$username_err = $password_err = $fname_err = $lname_err = "";

if(isset($_GET["member_id"]) && !empty(trim($_GET["member_id"]))){
	$_SESSION["member_id"] = $_GET["member_id"];

     $sql1 = "SELECT * FROM Member WHERE member_id = ?";

     if($stmt1 = mysqli_prepare($link, $sql1)){
        mysqli_stmt_bind_param($stmt1, "s", $param_memid); 
        
        $param_memid = trim($_GET["member_id"]);

         if(mysqli_stmt_execute($stmt1)){
            $result1 = mysqli_stmt_get_result($stmt1);
			if(mysqli_num_rows($result1) > 0){

				$row = mysqli_fetch_array($result1);

				$username = $row['username'];
				$password = $row['password'];
				$fname = $row['fname'];
				$lname = $row['lname'];
			}
		}
	}
}   

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $member_id = $_SESSION["member_id"];

    $username = trim($_POST["username"]);
    if (empty($username)) {
        $username_err = "Please enter a username.";
    } elseif (!preg_match("/^[a-zA-Z0-9@#\$%\^\&\*\(\)_\+\=\{\}\[\]:;'\",\.<>\/\?\-]+$/", $username)) {
        $username_err = "Please enter a valid username.";
    }
    

    $password = trim($_POST["password"]);
    if (empty($password)) {
        $password_err = "Please enter a password.";
    } elseif (!preg_match("/^[a-zA-Z0-9@#\$%\^\&\*\(\)_\+\=\{\}\[\]:;'\",\.<>\/\?\-]+$/", $password)) {
        $password_err = "Please enter a valid password.";
    }

    $fname = trim($_POST["fname"]);
    if (empty($fname)) {
        $fname_err = "Please enter a first name.";
    } elseif (!preg_match("/^[a-zA-Z\s'-]+$/", $fname)) {
        $fname_err = "Please enter a valid first name.";
    }

    $lname = trim($_POST["lname"]);
    if (empty($lname)) {
        $lname_err = "Please enter a last name.";
    } elseif (!preg_match("/^[a-zA-Z\s'-]+$/", $lname)) {
        $lname_err = "Please enter a valid last name.";
    }

    if(empty($fname_err) && empty($lname_err) && empty($username_err) && empty($password_err)){
        $sql = "UPDATE Member SET fname=?, lname=?, username=?, password=? WHERE member_id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sssss", $param_fname, $param_lname, $param_username, $param_password, $param_memid);

             // Set parameters
             $param_fname = $fname;
             $param_lname = $lname;            
             $param_username = $username;
             $param_password = $password;
             $param_memid = $member_id;

            if(mysqli_stmt_execute($stmt)){
                header("location: index.php");
                exit();
            } else{
                echo "<center><h2>Error when updating</center></h2>";
            }
        }        
        mysqli_stmt_close($stmt);
    }
    // Close connection
    mysqli_close($link);
} else {
	if(isset($_GET["member_id"]) && !empty(trim($_GET["member_id"]))){
		$_SESSION["member_id"] = $_GET["member_id"];

		$sql1 = "SELECT * FROM Member WHERE member_id = ?";
  
		if($stmt1 = mysqli_prepare($link, $sql1)){
			mysqli_stmt_bind_param($stmt1, "s", $param_memid);      
			$param_memid = trim($_GET["member_id"]);

			if(mysqli_stmt_execute($stmt1)){
				$result1 = mysqli_stmt_get_result($stmt1);
				if(mysqli_num_rows($result1) == 1){

					$row = mysqli_fetch_array($result1);
                    
                    $username = $row['username'];
                    $password = $row['password'];
					$fname = $row['fname'];
					$lname = $row['lname'];

				} else{
					header("location: error.php");
					exit();
				}                
			} else{
				echo "Error in member id while updating";
			}		
		}
			mysqli_stmt_close($stmt1);
			mysqli_close($link);
	}  else{
        header("location: error.php");
        exit();
	}	
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company DB</title>
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
                <div class="col-md-12">
                    <div class="page-header">
                        <h3>Update Record for Member_id =  <?php echo $_GET["member_id"]; ?> </H3>
                    </div>
                    <p>Please edit the input values and submit to update.
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                            <span class="help-block"><?php echo $username_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label>Password</label>
                            <input type="text" name="password" class="form-control" value="<?php echo $password; ?>">
                            <span class="help-block"><?php echo $password_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($fname_err)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input type="text" name="fname" class="form-control" value="<?php echo $fname; ?>">
                            <span class="help-block"><?php echo $fname_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($lname_err)) ? 'has-error' : ''; ?>">
                            <label>Last Name</label>
                            <input type="text" name="lname" class="form-control" value="<?php echo $lname; ?>">
                            <span class="help-block"><?php echo $lname_err;?></span>
                        </div>
                        <input type="hidden" name="member_id" value="<?php echo $member_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>