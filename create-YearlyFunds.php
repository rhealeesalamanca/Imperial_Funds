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
<html lang="en">

<!-- Head -->
<?php include('includes/head.php'); ?>

<body style="background-color: #fefae0;">
    <div class="wrapper">

        <!-- Sidebar Holder -->
        <?php include('includes/sidebar.php'); ?>

        <!-- Page Content Holder -->
        <div id="content">
            <?php include('includes/navbar.php'); ?>

            <!-- Header Section -->
            <div class="p-4 shadow rounded-4 mb-4" style="background: linear-gradient(90deg, #FFD42A, #FFA500);">
                <h2 class="text-dark fw-bold">CREATE NEW RECORD</h2>
                <hr class="my-3" />
                <small class="d-block text-dark">
                    <a href="index.php" class="text-dark text-decoration-none">
                        <i class="fa-solid fa-house"></i> <b>DASHBOARD</b>
                    </a> &nbsp;|&nbsp;
                    <b>YEARLY FUND</b> &nbsp;|&nbsp;
                    <b>ADD YEARLY FUND</b>
                </small>
            </div>

            <!-- Form Section -->
            <div class="container bg-white p-4 rounded-4 shadow-sm">
                <h3 class="text-dark fw-bold">CREATE YEARLY FUNDS</h3>
                <?php echo $page_err; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="pt-3">
                    <div class="row">
                        <div class="col-md-4 pb-3">
                            <label class="form-label">Fund:</label>
                            <input type="number" name="Funds" class="form-control rounded-3 <?php echo (!empty($Funds_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Funds; ?>">
                            <span class="invalid-feedback"><?php echo $Funds_err; ?></span>
                        </div>

                        <div class="col-md-4 pb-3">
                            <label class="form-label">Year:</label>
                            <input type="text" name="Year" class="form-control rounded-3 <?php echo (!empty($Year_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Year; ?>" placeholder="YYYY" pattern="\d{4}" title="Enter a 4-digit year">
                            <span class="invalid-feedback"><?php echo $Year_err; ?></span>
                        </div>

                        <div class="col-md-4 pb-3">
                            <label class="form-label">Department:</label>
                            <select name="DepartmentId" class="form-control rounded-3 <?php echo (!empty($DepartmentId_err)) ? 'is-invalid' : ''; ?>">
                                <option value="" selected disabled>← Select Department →</option>
                                <?php foreach ($departmentIds as $id => $dept) : ?>
                                    <option value="<?php echo $id; ?>" <?php echo ($DepartmentId == $id) ? 'selected' : ''; ?>><?php echo $dept; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $DepartmentId_err; ?></span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pt-4">
                        <button type="submit" class="btn btn-secondary rounded-3 px-4">SUBMIT</button>
                        <a href="manage-YearlyFunds.php" class="btn btn-outline-secondary rounded-3 px-4">MANAGE YEARLY FUNDS</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .shadow {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .rounded-4 {
            border-radius: 1rem;
        }

        .form-label {
            font-weight: 600;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }
    </style>

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