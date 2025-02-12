<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$RequestId = $Budget = $Name = $Purpose = $DepartmentId = "";
$Budget_err = $Name_err = $Purpose_err = $DepartmentId_err = "";
$page_err = "";

// Fetching Department Ids from another table
$departmentIds = array();
$sqlDepartmentIds = "SELECT DepartmentId, DepartmentName FROM tblDepartment ORDER BY DepartmentName ASC";
$resultDepartmentIds = $mysqli->query($sqlDepartmentIds);
while ($rowDepartmentIds = $resultDepartmentIds->fetch_assoc()) {
    $id = $rowDepartmentIds['DepartmentId'];
    $dept = $rowDepartmentIds['DepartmentName'];
    $departmentIds[$id] = $dept;
}

// Check if ID is set before proceeding
if (isset($_GET["RequestId"]) && !empty(trim($_GET["RequestId"]))) {
    $RequestId = trim($_GET["RequestId"]);
    
    // Prepare a select statement
    $sql = "SELECT * FROM tblRequest WHERE RequestId = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $RequestId);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $Budget = $row["Budget"];
                $Name = $row["Name"];
                $Purpose = $row["Purpose"];
                $DepartmentId = $row["DepartmentId"];
            } else {
                echo "<p style='color:red;'>Record not found.</p>";
                exit();
            }
        }
        $stmt->close();
    }
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $RequestId = $_POST["RequestId"];
    
    // Validate Budget
    $input_Budget = trim($_POST["Budget"]);
    if (empty($input_Budget)) {
        $Budget_err = "Do not leave empty!";
    } elseif (!is_numeric($input_Budget) || $input_Budget < 0) {
        $Budget_err = "Enter a valid number!";
    } else {
        $Budget = floatval($input_Budget);
    }

    // Validate Name
    $input_Name = trim($_POST["Name"]);
    if (empty($input_Name)) {
        $Name_err = "Do not leave empty!";
    } elseif (!preg_match("/^[A-Za-z\s]+$/", $input_Name)) {
        $Name_err = "Only letters and spaces are allowed.";
    } else {
        $Name = strtoupper($input_Name);
    }

    // Validate Purpose
    $input_Purpose = trim($_POST["Purpose"]);
    if (empty($input_Purpose)) {
        $Purpose_err = "Do not leave empty!";
    } else {
        $Purpose = strtoupper($input_Purpose);
    }

    // Validate DepartmentId
    $input_DepartmentId = trim($_POST["DepartmentId"]);
    if (empty($input_DepartmentId)) {
        $DepartmentId_err = "Do not leave empty!";
    } elseif (!ctype_digit($input_DepartmentId) || !array_key_exists($input_DepartmentId, $departmentIds)) {
        $DepartmentId_err = "Invalid Department ID!";
    } else {
        $DepartmentId = intval($input_DepartmentId);
    }

    // Check input errors before updating the database
    if (empty($Budget_err) && empty($Name_err) && empty($Purpose_err) && empty($DepartmentId_err)) {
        
        // Prepare an update statement
        $sql = "UPDATE tblRequest SET Budget=?, Name=?, Purpose=?, DepartmentId=? WHERE RequestId=?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("dsssi", $param_Budget, $param_Name, $param_Purpose, $param_DepartmentId, $param_RequestId);
            
            // Set parameters
            $param_Budget = $Budget;
            $param_Name = $Name;
            $param_Purpose = $Purpose;
            $param_DepartmentId = $DepartmentId;
            $param_RequestId = $RequestId;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $page_err = "<p style='color:green;'>Successfully updated data!</p>";
            } else {
                $page_err = "<p style='color:red;'>Oops! Error updating data!</p>";
            }
        }
        $stmt->close();
    }
    $mysqli->close();
}
?>

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
                <h2>UPDATE EXISTING RECORD</h2>                  
                <hr class="my-4" />
                <small style="font-size: small; color: #47748b;" class="pt-3 pb-2"><a href="index.php" class="text-light"><i class="fa-solid fa-house"></i><b>&nbsp;&nbsp;DASHBOARD</b></a>  &nbsp;&#124;&nbsp;  <i class=""></i><b>RequestS</b>  &nbsp;&#124;&nbsp;  <i class=""></i><b>Update Request</b></small>                                    
            </div>

            <!-- Home -->
            <div class="container text-light">
                <h3 style="color:#3b72f9;" class="pt-4">UPDATE Request</h3>
                <p>Please edit the input values and submit to update the record.</p>
                        
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="row">
                        <div class="col col-md-4 col-lg-4 col-12 pb-2">
                            <label style="font-size:small;">Budget:</label>
                            <input type="number" name="Budget" class="form-control <?php echo (!empty($Budget_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Budget; ?>">
                            <span class="invalid-feedback"><?php echo $Budget_err;?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-4 col-lg-4 col-12 pb-2">
                            <label style="font-size:small;">Name:</label>
                            <input type="text" name="Name" class="form-control uppercase-input <?php echo (!empty($Name_err)) ? 'is-invalid' : ''; ?>" 
                                value="<?php echo $Name; ?>" 
                                pattern="[A-Za-z\s]+" 
                                title="Only letters and spaces are allowed" 
                                oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                            <span class="invalid-feedback"><?php echo $Name_err;?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-12 pb-2">
                            <label style="font-size:small;">Purpose:</label>
                            <input type="text" name="Purpose" class="form-control uppercase-input <?php echo (!empty($Purpose_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Purpose; ?>">
                            <span class="invalid-feedback"><?php echo $Purpose_err;?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-6 col-lg-6 col-12 pb-2">
                            <label style="font-size:small;">Department:</label>
                            <select name="DepartmentId" class="form-control <?php echo (!empty($DepartmentId_err)) ? 'is-invalid' : ''; ?>">
                                <option value="" selected disabled><--Select Department--></option>
                                <?php foreach ($departmentIds as $id => $dept) : ?>
                                    <option value="<?php echo $id; ?>" <?php echo ($DepartmentId == $id) ? 'selected' : ''; ?>><?php echo $dept; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $DepartmentId_err; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col d-flex justify-content-between pt-3">
                            <input type="hidden" name="id" value="<?php echo $DepartmentId; ?>"/>
                            <input type="submit" class="btn btn-primary" value="SUBMIT">
                            <a class="btn btn-outline-secondary" href="manage-Request.php">CANCEL</a>
                        </div>
                    </div>
                </form>
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

        const inputElement = document.getElementById('myInput');
        
        inputElement.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    </script>
    
    <style>
    .uppercase-input {
        text-transform: uppercase;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        display: none;
    }
    </style>
</body>

</html>