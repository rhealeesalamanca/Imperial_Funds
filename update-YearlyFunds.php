<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$Funds = $Year = $DepartmentId = "";
$Funds_err = $Year_err = "";

// Fetching Department Ids from another table
$departmentIds = array();
$sqlDepartmentIds = "SELECT DepartmentId, DepartmentName FROM tblDepartment ORDER BY DepartmentName ASC";
$resultDepartmentIds = $mysqli->query($sqlDepartmentIds);

while ($rowDepartmentIds = $resultDepartmentIds->fetch_assoc()) {
    $id = $rowDepartmentIds['DepartmentId'];
    $dept = $rowDepartmentIds['DepartmentName'];
    $departmentIds[$id] = $dept;
}
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $YearlyId = $_POST["id"];

    // Validate Funds
    $input_Funds = trim($_POST["Funds"]);
    if (empty($input_Funds)) {
        $Funds_err = "Do not leave empty!";
    } elseif (!is_numeric($input_Funds)) {
        $Funds_err = "Funds must be a number.";
    } else {
        $Funds = $input_Funds;
    }

    // Validate Year
    $input_Year = trim($_POST["Year"]);
    if (empty($input_Year)) {
        $Year_err = "Do not leave empty!";
    } elseif (!ctype_digit($input_Year) || strlen($input_Year) != 4) {
        $Year_err = "Enter a valid four-digit year.";
    } elseif ($input_Year < 2020) { // Removed max limit
        $Year_err = "Year must be 2020 or later.";
    } else {
        $Year = $input_Year;
    }

    // Validate DepartmentId
    $input_DepartmentId = isset($_POST["DepartmentId"]) ? trim($_POST["DepartmentId"]) : "";
    $DepartmentId = (!empty($input_DepartmentId)) ? $input_DepartmentId : NULL;

    // Check input errors before inserting into the database
    if (empty($Funds_err) && empty($Year_err)) {  // Removed Department_err since it's not declared
        // Prepare an update statement
        $sql = "UPDATE tblYearlyFunds SET Funds=?, Year=?, DepartmentId=? WHERE YearlyId=?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("dssi", $param_Funds, $param_Year, $param_DepartmentId, $param_YearlyId);

            // Set parameters
            $param_Funds = $Funds;  // Ensure Funds has a valid value
            $param_Year = $Year;
            $param_DepartmentId = $DepartmentId;
            $param_YearlyId = $YearlyId;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to landing page after a successful update
                header("location: manage-YearlyFunds.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $mysqli->close();
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $YearlyId =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM tblYearlyFunds WHERE YearlyId = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_YearlyId);
            
            // Set parameters
            $param_YearlyId = $YearlyId;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $result = $stmt->get_result();
                
                if($result->num_rows == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $Funds = $row["Funds"];
                    $Year = $row["Year"];
                    $DepartmentId = $row["DepartmentId"];
                    $CreatedDate = $row["CreatedDate"];
                    $UpdatedDate = $row["UpdatedDate"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        $stmt->close();
        
        // Close connection
        $mysqli->close();
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
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
                <small style="font-size: small; color: #47748b;" class="pt-3 pb-2"><a href="index.php" class="text-light"><i class="fa-solid fa-house"></i><b>&nbsp;&nbsp;DASHBOARD</b></a>  &nbsp;&#124;&nbsp;  <i class=""></i><b>YEARLY FUNDS</b>  &nbsp;&#124;&nbsp;  <i class=""></i><b>Update Class</b></small>                                    
            </div>

            <!-- Home -->
            <div class="container text-light">
                <h3 style="color:#3b72f9;" class="pt-4">UPDATE CLASS</h3>
                <p>Please edit the input values and submit to update the record.</p>

                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="row">
                        <div class="col col-md-3 col-lg-3 col-12 pb-2">
                            <label style="font-size:small;">Funds:</label>
                            <input type="number" name="Funds" class="form-control uppercase-input <?php echo (!empty($Funds_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Funds; ?>" min="1900" step="1">
                            <span class="invalid-feedback"><?php echo $Funds_err;?></span>
                        </div>
                        <div class="col col-md-3 col-lg-3 col-12 pb-2"> 
                            <label style="font-size:small;">Year:</label>
                            <input type="number" name="Year" class="form-control uppercase-input <?php echo (!empty($Year_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Year; ?>" min="1900" step="1">
                            <span class="invalid-feedback"><?php echo $Year_err;?></span>
                        </div>
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
                            <input type="hidden" name="id" value="<?php echo $YearlyId; ?>"/>
                            <input type="submit" class="btn btn-primary" value="SUBMIT">
                            <a class="btn btn-outline-secondary" href="manage-YearlyFunds.php">CANCEL</a>
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