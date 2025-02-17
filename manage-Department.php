<!DOCTYPE html>
<html>
<!-- Head -->  
<?php include('includes/head.php');?> 
<body>
    <div class="wrapper">

        <!-- Sidebar Holder -->
        <?php include('includes/sidebar.php');?> 

        <!-- Page Content Holder -->
        <div id="content">
            <?php include('includes/navbar.php');?> 

            <!-- Jumbotron -->
            <div class="p-4 shadow-4 rounded-3" style="background-color:#12081B;">
                <h2>DEPARTMENT LIST</h2>                  
                <hr class="my-4" />
                <small style="font-size: small; color: #47748b;" class="pt-3 pb-2"><a href="index.php" class="text-light"><i class="fa-solid fa-house"></i><b>&nbsp;&nbsp;DASHBOARD</b></a>  &nbsp;&#124;&nbsp;  <i class=""></i><b>DEPARTMENTS</b>  &nbsp;&#124;&nbsp;  <i class=""></i><b>Manage Department</b></small>                                    
            </div>

            <!-- Table -->
            <div class="container w-auto" >
                <h3 style="color:#3b72f9;" class="pt-4">MANAGE DEPARTMENT</h3>
                <div class="row">
                    <div class="col-sm-11">
                        <div class="mt-2 mb-3">
                            <a href="create-Department.php" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp ADD NEW RECORD</a>
                        </div>

                        <div class="mt-2 mb-3">
                            <form action="" method="post" class="form-inline">
                                <div class="form-group mr-2">
                                    <input type="text" name="search" class="form-control" placeholder="Search">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;SEARCH</button>
                            </form>
                        </div>

                        <?php
                        // Include config file
                        require_once "config.php";

                        // Initialize the $sql variable for the initial query
                        $sql = "SELECT * FROM tblDepartment";

                        // Search functionality
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $search = $mysqli->real_escape_string($_POST["search"]); // Add real_escape_string to prevent SQL injection
                        
                            // Check if WHERE clause needs to be added
                            if (strpos($sql, 'WHERE') === false) {
                                // If it doesn't exist, add the WHERE clause
                                $sql .= " WHERE CurrentFunds LIKE '%$search%' OR DepartmentName LIKE '%$search%'";
                            } else {
                                // If it exists, add additional conditions using OR
                                $sql .= " AND (CurrentFunds LIKE '%$search%' OR DepartmentName LIKE '%$search%')";
                            }
                        }                        

                        // Add ORDER BY clause to the query
                        $sql .= " ORDER BY DepartmentName ASC";

                        // Attempt select query execution
                        if($result = $mysqli->query($sql)){
                            if($result->num_rows > 0){
                                echo '<div class="table-responsive">';
                                echo '<table class="table table-striped table-dark table-bordered text-center table table-hover" id="text">';
                                echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>#</th>";
                                    echo "<th>Department Name</th>";
                                    echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                $count = 1; // Initialize the counter outside the loop

                                while($row = $result->fetch_array()){
                                    echo "<tr>";
                                        echo "<td>" . $count . "</td>";
                                        echo "<td>" . $row['DepartmentName'] . "</td>";
                                        echo "<td>";
                                        echo '<a href="update-Department.php?id='. $row['DepartmentId'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="bi bi-pencil-square text-success";></span></a>';
                                        echo '<a href="delete-Department.php?id='. $row['DepartmentId'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash" style: color="crimson";></span></a>';
                                        echo "</td>";
                                    echo "</tr>";

                                    $count++; // Increment the counter for each row
                                }
                                echo "</tbody>";                            
                                echo "</table>";
                                echo '</div>';
                                // Free result set
                                $result->free();
                            } else {
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else {
                            echo "Query execution failed: " . $mysqli->error;
                        }
                        
                        // Close connection
                        $mysqli->close();
                        ?>
                    </div>
                </div>        
            </div>                
        </div>
    </div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $(this).toggleClass('active');
            });
        });
    </script>

    <style>
        #text{
            color:white;
            font-size: 14px;  
            font-family: sans-serif;
        }
    </style>
</body>
</html>