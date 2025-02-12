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
                <h2>ALL LIST</h2>                  
                <hr class="my-4" />
                <small style="font-size: small; color: #47748b;" class="pt-3 pb-2"><a href="index.php" class="text-light"><i class="fa-solid fa-house"></i><b>&nbsp;&nbsp;DASHBOARD</b></a></small>                                    
            </div>

            <!-- Table -->
            <div class="container w-auto" >
                <h3 style="color:#3b72f9;" class="pt-4">MANAGE STUDENT</h3>
                <div class="row">
                    <div class="col-sm-11">

                        <?php
                        // Include config file
                        require_once "config.php";

                        // Attempt select query execution
                        // Attempt select query execution
                        $sql = "SELECT tblClasses.*, tblStudent.* 
                                FROM tblStudent 
                                LEFT JOIN tblClasses ON tblClasses.ClassId = tblStudent.ClassId 
                                ORDER BY GradeLevel,Section ASC";
                        if($result = $mysqli->query($sql)){
                            if($result->num_rows > 0){
                                echo '<div class="table-responsive">';
                                    echo '<table class="table table-striped table-dark table-bordered text-center table table-hover" id="text">';
                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th>#</th>";
                                                echo "<th>LRN</th>";
                                                echo "<th>Full Name</th>";
                                                echo "<th>Grade and Section</th>";
                                                echo "<th>Status</th>";
                                                echo "<th>Action</th>";
                                            echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";

                                        $count = 1; // Initialize the counter outside the loop

                                        while($row = $result->fetch_array()){
                                            echo "<tr>";
                                                echo "<td>" . $count . "</td>";
                                                echo "<td>" . $row['LRN'] . "</td>";
                                                echo "<td>" . $row['Firstname'] . " " . $row['MiddleName'] . " " . $row['Lastname'] ."</td>";
                                                echo "<td>" . (($row['GradeLevel'] && $row['Section']) ? $row['GradeLevel'] . " - " . $row['Section'] : "TBA") . "</td>";
                                                echo "<td>" . ($row['Status'] !== null ? $row['Status'] : "PENDING") . "</td>";
                                                echo "<td>";
                                                echo '<a href="update-Student.php?id='. $row['StudentId'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="bi bi-pencil-square text-success";></span></a>';
                                                echo '<a href="delete-Student.php?id='. $row['StudentId'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash" style: color="crimson";></span></a>';
                                                echo "</td>";
                                            echo "</tr>";

                                            $count++; // Increment the counter for each row
                                        }
                                        echo "</tbody>";                            
                                    echo "</table>";
                                echo '</div>';
                                // Free result set
                                $result->free();
                            } else{
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else{
                            echo "Oops! Something went wrong. Please try again later.";
                        }
                        ?>
                    </div>
                </div> 
                
                <h3 style="color:#3b72f9;" class="pt-4">MANAGE TEACHER</h3>
                <div class="row">
                    <div class="col-sm-11">

                        <?php
                        // Include config file
                        require_once "config.php";

                        // Attempt select query execution
                        $sql = "SELECT * FROM tblTeacher ORDER BY Lastname,Firstname ASC";
                        if($result = $mysqli->query($sql)){
                            if($result->num_rows > 0){
                                echo '<div class="table-responsive">';
                                    echo '<table class="table table-striped table-dark table-bordered text-center table table-hover" id="text">';
                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th>#</th>";
                                                echo "<th>Employee Number</th>";
                                                echo "<th>Lastname</th>";
                                                echo "<th>Firstname</th>";
                                                echo "<th>MI</th>";
                                                echo "<th>Email</th>";
                                                echo "<th>Action</th>";
                                            echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";

                                        $count = 1; // Initialize the counter outside the loop

                                        while($row = $result->fetch_array()){
                                            echo "<tr>";
                                                echo "<td>" . $count . "</td>";
                                                echo "<td>" . $row['EmployeeNumber'] . "</td>";
                                                echo "<td>" . $row['Lastname'] . "</td>";
                                                echo "<td>" . $row['Firstname'] . "</td>";
                                                echo "<td>" . $row['MI'] . "</td>";
                                                echo "<td>" . $row['Email'] . "</td>";
                                                echo "<td>";
                                                echo '<a href="update-Teacher.php?id='. $row['TeacherId'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="bi bi-pencil-square text-success";></span></a>';
                                                echo '<a href="delete-Teacher.php?id='. $row['TeacherId'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash" style: color="crimson";></span></a>';
                                                echo "</td>";
                                            echo "</tr>";

                                            $count++; // Increment the counter for each row
                                        }
                                        echo "</tbody>";                            
                                    echo "</table>";
                                echo '</div>';
                                // Free result set
                                $result->free();
                            } else{
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else{
                            echo "Oops! Something went wrong. Please try again later.";
                        }
                        ?>
                    </div>
                </div>   
                
                <h3 style="color:#3b72f9;" class="pt-4">MANAGE CLASSES</h3>
                <div class="row">
                    <div class="col-sm-11">

                        <?php
                        // Include config file
                        require_once "config.php";

                        // Attempt select query execution
                        $sql = "SELECT  tblTeacher.*, tblClasses.* 
                                FROM tblClasses 
                                LEFT JOIN tblTeacher ON tblClasses.TeacherId = tblTeacher.TeacherId 
                                ORDER BY GradeLevel,Section ASC";
                        if($result = $mysqli->query($sql)){
                            if($result->num_rows > 0){
                                echo '<div class="table-responsive">';
                                    echo '<table class="table table-striped table-dark table-bordered text-center table table-hover" id="text">';
                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th>#</th>";
                                                echo "<th>Grade Level</th>";
                                                echo "<th>Section</th>";
                                                echo "<th>Assign Room</th>";
                                                echo "<th>Adviser</th>";
                                                echo "<th>Action</th>";
                                            echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";

                                        $count = 1; // Initialize the counter outside the loop

                                        while($row = $result->fetch_array()){
                                            echo "<tr>";
                                                echo "<td>" . $count . "</td>";
                                                echo "<td>" . $row['GradeLevel'] . "</td>";
                                                echo "<td>" . $row['Section'] . "</td>";
                                                echo "<td>" . ($row['Room'] !== null ? $row['Room'] : "TBA") . "</td>";
                                                echo "<td>" . (($row['Firstname'] && $row['Lastname']) ? $row['Firstname'] . " " . $row['Lastname'] : "TBA") . "</td>";
                                                echo "<td>";
                                                echo '<a href="update-Classes.php?id='. $row['ClassId'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="bi bi-pencil-square text-success";></span></a>';
                                                echo '<a href="delete-Classes.php?id='. $row['ClassId'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash" style: color="crimson";></span></a>';
                                                echo "</td>";
                                            echo "</tr>";

                                            $count++; // Increment the counter for each row
                                        }
                                        echo "</tbody>";                            
                                    echo "</table>";
                                echo '</div>';
                                // Free result set
                                $result->free();
                            } else{
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else{
                            echo "Oops! Something went wrong. Please try again later.";
                        }
                        ?>
                    </div>
                </div> 
                
                <h3 style="color:#3b72f9;" class="pt-4">MANAGE SUBJECT</h3>
                <div class="row">
                    <div class="col-sm-11">

                        <?php
                        // Include config file
                        require_once "config.php";

                        // Attempt select query execution
                        $sql = "SELECT * FROM tblSubject ORDER BY SubjectName ASC";
                        if($result = $mysqli->query($sql)){
                            if($result->num_rows > 0){
                                echo '<div class="table-responsive">';
                                    echo '<table class="table table-striped table-dark table-bordered text-center table table-hover" id="text">';
                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th>#</th>";
                                                echo "<th>Subject Code</th>";
                                                echo "<th>Subject Name</th>";
                                                echo "<th>Action</th>";
                                            echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";

                                        $count = 1; // Initialize the counter outside the loop

                                        while($row = $result->fetch_array()){
                                            echo "<tr>";
                                                echo "<td>" . $count . "</td>";
                                                echo "<td>" . $row['SubjectCode'] . "</td>";
                                                echo "<td>" . $row['SubjectName'] . "</td>";
                                                echo "<td>";
                                                echo '<a href="update-Subject.php?id='. $row['SubjectId'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="bi bi-pencil-square text-success";></span></a>';
                                                echo '<a href="delete-Subject.php?id='. $row['SubjectId'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash" style: color="crimson";></span></a>';
                                                echo "</td>";
                                            echo "</tr>";

                                            $count++; // Increment the counter for each row
                                        }
                                        echo "</tbody>";                            
                                    echo "</table>";
                                echo '</div>';
                                // Free result set
                                $result->free();
                            } else{
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else{
                            echo "Oops! Something went wrong. Please try again later.";
                        }
                        ?>
                    </div>
                </div> 
                
                <h3 style="color:#3b72f9;" class="pt-4">MANAGE SCHEDULE</h3>
                <div class="row">
                    <div class="col-sm-11">

                        <?php
                        // Include config file
                        require_once "config.php";

                        // Attempt select query execution
                        $sql = "SELECT tblClasses.*, tblSubject.*, tblTeacher.*, tblSchedule.* 
                                FROM tblSchedule 
                                LEFT JOIN tblClasses ON tblClasses.ClassId = tblSchedule.ClassId 
                                LEFT JOIN tblSubject ON tblSubject.SubjectId = tblSchedule.SubjectId 
                                LEFT JOIN tblTeacher ON tblTeacher.TeacherId = tblSchedule.TeacherId 
                                ORDER BY GradeLevel,Section ASC";
                        if($result = $mysqli->query($sql)){
                            if($result->num_rows > 0){
                                echo '<div class="table-responsive">';
                                    echo '<table class="table table-striped table-dark table-bordered text-center table table-hover" id="text">';
                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th>#</th>";
                                                echo "<th>Grade and Section</th>";
                                                echo "<th>Subject</th>";
                                                echo "<th>Teacher</th>";
                                                echo "<th>Class Day</th>";
                                                echo "<th>Class Time-in</th>";
                                                echo "<th>Class Time-out</th>";
                                                echo "<th>Action</th>";
                                            echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";

                                        $count = 1; // Initialize the counter outside the loop

                                        while($row = $result->fetch_array()){
                                            echo "<tr>";
                                                echo "<td>" . $count . "</td>";
                                                echo "<td>" . $row['GradeLevel'] . " - " . $row['Section'] . "</td>";
                                                echo "<td>" . $row['SubjectName'] . "</td>";
                                                echo "<td>" . (($row['Firstname'] && $row['Lastname']) ? $row['Firstname'] . " " . $row['Lastname'] : "TBA") . "</td>";
                                                echo "<td>" . ($row['ClassDay'] !== null ? $row['ClassDay'] : "TBA") . "</td>";
                                                echo "<td>" . ($row['ClassTimeIn'] !== '00:00:00' ? date_format(new DateTime($row['ClassTimeIn']), 'h:i A') : "TBA") . "</td>";
                                                echo "<td>" . ($row['ClassTimeOut'] !== '00:00:00' ? date_format(new DateTime($row['ClassTimeOut']), 'h:i A') : "TBA") . "</td>";
                                                echo "<td>";
                                                echo '<a href="update-Schedule.php?id='. $row['SchedId'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="bi bi-pencil-square text-success";></span></a>';
                                                echo '<a href="delete-Schedule.php?id='. $row['SchedId'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash" style: color="crimson";></span></a>';
                                                echo "</td>";
                                            echo "</tr>";

                                            $count++; // Increment the counter for each row
                                        }
                                        echo "</tbody>";                            
                                    echo "</table>";
                                echo '</div>';
                                // Free result set
                                $result->free();
                            } else{
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else{
                            echo "Oops! Something went wrong. Please try again later.";
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