<!DOCTYPE html>
<html>
<!-- Head -->  
<?php include('includes/head.php');?> 
<style>
    p {
        line-height: 1.5em;
        color: #fff;
        font-weight: bold;
    }
</style>
<body>
    <div class="container-fluid text-center" style="background-color: #0d2533;">
        <h2 class="p-4">Muntinlupa Elementary School</h2>
    </div>
    <div class="container py-2">
        <div class="row justify-content-center align-items-center">
            <div class="col col-md-10 col-lg-10" >
                <div class="container p-5 text-light" style="background-color: #0d2533;">
                
                    <?php
                    // Include config file
                    require_once "config.php";

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Collect LRN and ClassId from the form
                        $LRN = $_POST["LRN"];
                        $ClassId = $_POST["ClassId"];

                        // Query to fetch student data based on LRN and ClassId
                        $sql = "SELECT tblClasses.*, tblStudent.* 
                                FROM tblStudent 
                                INNER JOIN tblClasses ON tblClasses.ClassId = tblStudent.ClassId 
                                WHERE tblStudent.LRN = ? AND tblStudent.ClassId = ?";
                        $stmt = $mysqli->prepare($sql);

                        if ($stmt) {
                            $stmt->bind_param("ss", $LRN, $ClassId);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            // Check if any rows were returned
                            if ($result->num_rows > 0) {
                                // Display student data
                                while ($row = $result->fetch_assoc()) {
                                    echo "<p>LRN:  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; " . $row["LRN"] . "</p>";
                                    echo "<p>FULL NAME: &nbsp; &nbsp; &nbsp; &nbsp;" . $row['Firstname'] . " " . $row['MiddleName'] . " " . $row['Lastname'] . "</p>";
                                    echo "<p>GENDER:  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; " . $row["Gender"] . "</p>";
                                    echo "<p>BIRTHDATE: &nbsp; &nbsp; &nbsp; &nbsp;" . date('F d, Y', strtotime($row["Birthdate"])) . "</p>";
                                    echo "<p>ADDRESS: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;" . $row["Address"] . "</p>";
                                    echo "<p>STATUS: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;" . ($row['Status'] !== null ? $row['Status'] : "PENDING") . "</p>";
                                    echo "<p>CLASS: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; " . (($row['GradeLevel'] && $row['Section']) ? $row['GradeLevel'] . " - " . $row['Section'] : "TBA") . "</p>";
                                    echo "<p>ROOM: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;" . $row["Room"] . "</p>";
                                }
                            } else {
                                echo "THIS IS NOT YOUR CLASS!";
                            }

                            $stmt->close();
                        } else {
                            echo "Error in prepared statement: " . $mysqli->error;
                        }
                    }

                    function fetchSchedId($ClassId) {
                        global $mysqli;
                    
                        // Implement the logic to fetch SchedId based on ClassId
                        $sql = "SELECT SchedId FROM tblSchedule WHERE ClassId = ? LIMIT 1";
                        $stmt = $mysqli->prepare($sql);
                    
                        if ($stmt) {
                            $stmt->bind_param("s", $ClassId);
                            $stmt->execute();
                            $stmt->bind_result($SchedId);
                            $stmt->fetch();
                            $stmt->close();
                    
                            return $SchedId; // Return the fetched SchedId
                        } else {
                            echo "Error in prepared statement: " . $mysqli->error;
                            return null;  // Return null in case of an error
                        }
                    }
                    
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Collect LRN and ClassId from the form
                        $ClassId = $_POST["ClassId"];
                        // Fetch SchedId based on the specific ClassId
                        $SchedId = fetchSchedId($ClassId);                              
                    
                        $sql = "SELECT tblClasses.*, tblSubject.*, tblTeacher.*, tblSchedule.* 
                            FROM tblSchedule 
                            LEFT JOIN tblClasses ON tblClasses.ClassId = tblSchedule.ClassId 
                            LEFT JOIN tblSubject ON tblSubject.SubjectId = tblSchedule.SubjectId 
                            LEFT JOIN tblTeacher ON tblTeacher.TeacherId = tblSchedule.TeacherId
                            WHERE tblSchedule.ClassId = ?";  // Updated to filter by ClassId
                    
                        if ($stmt = $mysqli->prepare($sql)) {
                            $stmt->bind_param("s", $ClassId);
                            $stmt->execute();
                            $result = $stmt->get_result();
                    
                            if($result->num_rows > 0){
                                echo '<div class="table-responsive">';
                                    echo '<table class="table table-striped table-dark table-bordered text-center table table-hover" id="text">';
                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th>Subject</th>";
                                                echo "<th>Teacher</th>";
                                                echo "<th>Class Day</th>";
                                                echo "<th>Class Time-in</th>";
                                                echo "<th>Class Time-out</th>";
                                            echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";
                                        while($row = $result->fetch_array()){
                                            echo "<tr>";
                                                echo "<td>" . $row['SubjectName'] . "</td>";
                                                echo "<td>" . (($row['Firstname'] && $row['Lastname']) ? $row['Firstname'] . " " . $row['Lastname'] : "TBA") . "</td>";
                                                echo "<td>" . ($row['ClassDay'] !== null ? $row['ClassDay'] : "TBA") . "</td>";
                                                echo "<td>" . ($row['ClassTimeIn'] !== '00:00:00' ? date_format(new DateTime($row['ClassTimeIn']), 'h:i A') : "TBA") . "</td>";
                                                echo "<td>" . ($row['ClassTimeOut'] !== '00:00:00' ? date_format(new DateTime($row['ClassTimeOut']), 'h:i A') : "TBA") . "</td>";
                                            echo "</tr>";
                                        }
                                        echo "</tbody>";                            
                                    echo "</table>";
                                echo '</div>';
                                // Free result set
                                $result->free();
                            } else{
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                    
                            $stmt->close();
                        } else {
                            echo "Error in prepared statement: " . $mysqli->error;
                        }
                    }
                    ?>

                    <div class="row">
                        <div class="col d-flex pt-1">
                            <a class="btn btn-outline-secondary ml-auto" href="login.php">BACK</a>
                        </div>
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
</body>
</html>