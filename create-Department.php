<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$DepartmentName = $CurrentFunds = "";
$DepartmentName_err = $CurrentFunds_err = "";
$page_err = ""; 

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate DepartmentName
    $input_DepartmentName = trim($_POST["DepartmentName"]);
    if (empty($input_DepartmentName)) {
        $DepartmentName_err = "Do not leave empty!";
    } else {
        $DepartmentName = strtoupper($input_DepartmentName);
    }

    // Check input errors before inserting into the database
    if (empty($DepartmentName_err) && empty($CurrentFunds_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO tblDepartment (DepartmentName, CurrentFunds) VALUES (?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {

            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sd", $param_DepartmentName, $param_CurrentFunds); // "s" = string, "d" = double (number)

            // Set parameters
            $param_DepartmentName = $DepartmentName;
            $param_CurrentFunds = $CurrentFunds;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $page_err = "<p style='color:green;'>Successfully inserted data!</p>";
            } else {
                $page_err = "<p style='color:red;'>Oops! Error inserting data into the database!</p>";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
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
                <h2>CREATE NEW RECORD</h2>                  
                <hr class="my-4" />
                <small style="font-size: small; color: #47748b;" class="pt-3 pb-2"><a href="index.php" class="text-light"><i class="fa-solid fa-house"></i><b>&nbsp;&nbsp;DASHBOARD</b></a>  &nbsp;&#124;&nbsp;  <i class=""></i><b>DEPARTMENTS</b>  &nbsp;&#124;&nbsp;  <i class=""></i><b>Create Department</b></small>                                    
            </div>

            <!-- Home -->
            <div class="container text-light">
                <h3 style="color:#3b72f9;" class="pt-4">CREATE DEPARTMENT</h3>
                <?php echo $page_err;?>
            
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">           
                    <div class="row">
                        <div class="col col-md-8 col-lg-8 col-12 pb-2">
                            <label style="font-size:small;">Department Name:</label>
                            <input type="text" name="DepartmentName" class="form-control uppercase-input <?php echo (!empty($DepartmentName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $DepartmentName; ?>">
                            <span class="invalid-feedback"><?php echo $DepartmentName_err;?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col d-flex justify-content-between pt-3">
                            <input type="submit" class="btn btn-primary" value="SUBMIT">
                            <a class="btn btn-outline-secondary" href="manage-Department.php">MANAGE DEPARTMENT</a>
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