<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$DepartmentName = $CurrentFunds = "";
$DepartmentName_err = $CurrentFunds_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $DepartmentId = $_POST["id"];
    
    // Validate DepartmentName
    $input_DepartmentName = trim($_POST["DepartmentName"]);
    if(empty($input_DepartmentName)){
        $DepartmentName_err = "Please enter department name.";
    } else{
        $DepartmentName = strtoupper($input_DepartmentName);
    }
    
    // Validate CurrentFunds
    $input_CurrentFunds = trim($_POST["CurrentFunds"]);
    if(empty($input_CurrentFunds)){
        $CurrentFunds_err = "Please enter current funds.";
    } else{
        $CurrentFunds = strtoupper($input_CurrentFunds);
    }
    
    // Check input errors before inserting in database
    if(empty($DepartmentName_err) && empty($CurrentFunds_err)){

        // Prepare an update statement
        $sql = "UPDATE tblDepartment SET DepartmentName=?, CurrentFunds=? WHERE DepartmentId=?";
 
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssi", $param_DepartmentName, $param_CurrentFunds, $param_DepartmentId);
            
            // Set parameters
            $param_DepartmentName = $DepartmentName;
            $param_CurrentFunds = $CurrentFunds;
            $param_DepartmentId = $DepartmentId;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: manage-Department.php");
                exit();
            } else{
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
        $DepartmentId =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM tblDepartment WHERE DepartmentId = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_DepartmentId);
            
            // Set parameters
            $param_DepartmentId = $DepartmentId;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $result = $stmt->get_result();
                
                if($result->num_rows == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $DepartmentName = $row["DepartmentName"];
                    $CurrentFunds = $row["CurrentFunds"];
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
                <small style="font-size: small; color: #47748b;" class="pt-3 pb-2"><a href="index.php" class="text-light"><i class="fa-solid fa-house"></i><b>&nbsp;&nbsp;DASHBOARD</b></a>  &nbsp;&#124;&nbsp;  <i class=""></i><b>DEPARTMENTS</b>  &nbsp;&#124;&nbsp;  <i class=""></i><b>Update Department</b></small>                                    
            </div>

            <!-- Home -->
            <div class="container text-light">
                <h3 style="color:#3b72f9;" class="pt-4">UPDATE DEPARTMENT</h3>
                <p>Please edit the input values and submit to update the record.</p>
                        
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="row">
                        <div class="col col-md-4 col-lg-4 col-12 pb-2">
                            <label style="font-size:small;">Current Funds:</label>
                            <input type="text" name="CurrentFunds" placeholder="Enter CurrentFunds" class="form-control uppercase-input <?php echo (!empty($CurrentFunds_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $CurrentFunds; ?>">
                            <span class="invalid-feedback"><?php echo $CurrentFunds_err;?></span>
                        </div>
                        <div class="col col-md-8 col-lg-8 col-12 pb-2">
                            <label style="font-size:small;">Department Name:</label>
                            <input type="text" name="DepartmentName" placeholder="Enter Department Name" class="form-control uppercase-input <?php echo (!empty($DepartmentName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $DepartmentName; ?>">
                            <span class="invalid-feedback"><?php echo $DepartmentName_err;?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col d-flex justify-content-between pt-3">
                            <input type="hidden" name="id" value="<?php echo $DepartmentId; ?>"/>
                            <input type="submit" class="btn btn-primary" value="SUBMIT">
                            <a class="btn btn-outline-secondary" href="manage-Department.php">CANCEL</a>
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