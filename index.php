<?php
	session_start();
	//$currentpage="View Employees"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hit the Gym</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
	<style type="text/css">
        .wrapper{
            width: 70%;
            margin:0 auto;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
		 $('.selectpicker').selectpicker();
    </script>
</head>
<body>
    <?php
        // Include config file
        require_once "config.php";
//		include "header.php";
	?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
		    <div class="page-header clearfix">
		     <h2> Gym Tracker </h2> 
                       <p> Project should include CRUD operations. In this website you can:
				<ol> 	<li> CREATE new employess and  dependents </li>
					<li> RETRIEVE all dependents and prjects for an employee</li>
                                        <li> UPDATE employeee and dependent records</li>
					<li> DELETE employee and dependent records </li>
				</ol>
		       <h2 class="pull-left">Members</h2>
                        <a href="createEmployee.php" class="btn btn-success pull-right">Add New Employee</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    

                    $sql = "SELECT member_id,username, password, fname, lname
							FROM Member";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=8%>ID</th>";
                                        echo "<th width=10%>Username</th>";
                                        echo "<th width=10%>Password</th>";
                                        echo "<th width=10%>First Name</th>";
                                        echo "<th width=10%>Last Name</th>";
                                        echo "<th width=10%>Action</th>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['member_id'] . "</td>";
                                        echo "<td>" . $row['username'] . "</td>";
                                        echo "<td>" . $row['password'] . "</td>";
                                        echo "<td>" . $row['fname'] . "</td>";
                                        echo "<td>" . $row['lname'] . "</td>";
                                        
                                        echo "<td>";
                                             echo "<a href='viewWorkouts.php?id=". $row['member_id']."&username=".$row['username']."' title='View Workouts' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                             echo "<a href='updateMember.php?member_id=". $row['member_id'] ."' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                             echo "<a href='deleteMember.php?member_id=". $row['member_id'] ."' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
					     //echo "<a href='viewDependents.php?Ssn=". $row['Ssn']."&Lname=".$row['Lname']."' title='View Dependents' data-toggle='tooltip'><span class='glyphicon glyphicon-user'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. <br>" . mysqli_error($link);
                    }


                    // //DISPLAY EXERCISES
                    // $sql2 = "SELECT * FROM DEPT_STATS";
                    // if($result2 = mysqli_query($link, $sql2)){
                    //     if(mysqli_num_rows($result2) > 0){
                    //         echo "<div class='col-md-4'>";
					// 		echo "<table width=30% class='table table-bordered table-striped'>";
                    //             echo "<thead>";
                    //                 echo "<tr>";
                    //                     echo "<th width=20%>Dno</th>";
                    //                     echo "<th width = 20%>Number of Employees</th>";
                    //                     echo "<th width = 40%>Average Salary</th>";
	
                    //             echo "</thead>";
                    //             echo "<tbody>";
                    //             while($row = mysqli_fetch_array($result2)){
                    //                 echo "<tr>";
                    //                     echo "<td>" . $row['Dnumber'] . "</td>";
                    //                     echo "<td>" . $row['Emp_count'] . "</td>";
                    //                     echo "<td>" . $row['Avg_salary'] . "</td>";
               
                    //                 echo "</tr>";
                    //             }
                    //             echo "</tbody>";                            
                    //         echo "</table>";
                    //         // Free result set
                    //         mysqli_free_result($result2);
                    //     } else{
                    //         echo "<p class='lead'><em>No records were found for Dept Stats.</em></p>";
                    //     }
                    // } else{
                    //     echo "ERROR: Could not able to execute $sql2. <br>" . mysqli_error($link);
                    // }
					
                    // Close connection
                    mysqli_close($link);
                   
                    ?>
                </div>

</body>
</html>