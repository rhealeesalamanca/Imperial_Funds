<?php
// Include config file
require_once "config.php";

// Check if connection is successful
if ($mysqli === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Define variables and initialize with empty values
$Funds = $Year = $DepartmentId = "";
$Funds_err = $Year_err = $DepartmentId_err = "";
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

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate Funds (Must be a number)
    $input_Funds = trim($_POST["Funds"]);
    if (empty($input_Funds)) {
        $Funds_err = "Do not leave empty!";
    } elseif (!is_numeric($input_Funds)) {
        $Funds_err = "Enter a valid number!";
    } else {
        $Funds = floatval($input_Funds); // Convert to float
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
    $input_DepartmentId = trim($_POST["DepartmentId"]);
    if (empty($input_DepartmentId)) {
        $DepartmentId_err = "Do not leave empty!";
    } else {
        $DepartmentId = strtoupper($input_DepartmentId); // Convert to uppercase
    }

    // Check input errors before inserting into the database
    if (empty($Funds_err) && empty($Year_err) && empty($DepartmentId_err)) {
        
        // Prepare an insert statement
        $sql = "INSERT INTO tblYearlyFunds (Funds, Year, DepartmentId) VALUES (?, ?, ?)";
    
        if ($stmt = $mysqli->prepare($sql)) {
            
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("dis", $param_Funds, $param_Year, $param_DepartmentId);
            
            // Set parameters
            $param_Funds = (float)$Funds;  // Use float for Funds
            $param_Year = (int)$Year;
            $param_DepartmentId = $DepartmentId;
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $page_err = "<p style='color:green;'>Successfully inserted data!</p>";
            } else {
                $page_err = "<p style='color:red;'>Oops! Error inserting data into the database!</p>";
            }
        } else {
            die("ERROR: Could not prepare the SQL statement.");
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
                <small style="font-size: small; color: #47748b;" class="pt-3 pb-2"><a href="index.php" class="text-light"><i class="fa-solid fa-house"></i><b>&nbsp;&nbsp;DASHBOARD</b></a>  &nbsp;&#124;&nbsp;  <i class=""></i><b>YEARLY FUNDS</b>  &nbsp;&#124;&nbsp;  <i class=""></i><b>Add Yearly Fund</b></small>                                    
            </div>

            <!-- Home -->
            <div class="container text-light">
                <h3 style="color:#3b72f9;" class="pt-4">CREATE YEARLY FUND</h3>
                <?php echo $page_err;?>
            
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">           
                    <div class="row"> 
                        <div class="col col-md-3 col-lg-3 col-12 pb-2">
                            <label style="font-size:small;">Fund:</label>
                            <input type="number" name="Funds" class="form-control uppercase-input <?php echo (!empty($Funds_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Funds; ?>" step="0.01" min="0">
                            <span class="invalid-feedback"><?php echo $Funds_err;?></span>
                        </div>
                        <div class="col col-md-3 col-lg-3 col-12 pb-2"> 
                            <label style="font-size:small;">Year:</label>
                            <input type="text" name="Year" class="form-control uppercase-input <?php echo (!empty($Year_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Year; ?>" pattern="^\d{4}$" placeholder="YYYY" title="Year should be a 4-digit number (YYYY)">
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
                            <input type="submit" class="btn btn-primary" value="SUBMIT">
                            <a class="btn btn-outline-secondary" href="manage-YearlyFunds.php">MANAGE YEARLY FUNDS</a>
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