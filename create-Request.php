<?php
// Include database connection
require_once "config.php";

// Initialize variables
$Name = $Budget = $Purpose = $DepartmentId = "";
$Name_err = $Budget_err = $Purpose_err = $DepartmentId_err = "";
$page_err = "";

// Fetch Department IDs for the dropdown
$departmentIds = [];
$sql = "SELECT DepartmentId, DepartmentName FROM tblDepartment";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departmentIds[$row["DepartmentId"]] = $row["DepartmentName"];
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Budget
    if (empty(trim($_POST["Budget"]))) {
        $Budget_err = "Please enter a budget amount.";
    } elseif (!is_numeric($_POST["Budget"]) || $_POST["Budget"] <= 0) {
        $Budget_err = "Enter a valid budget amount.";
    } else {
        $Budget = $_POST["Budget"];
    }

    // Validate Name
    if (empty(trim($_POST["Name"]))) {
        $Name_err = "Please enter a name.";
    } else {
        $Name = strtoupper(trim($_POST["Name"]));
    }

    // Validate Purpose
    if (empty(trim($_POST["Purpose"]))) {
        $Purpose_err = "Please enter the purpose.";
    } else {
        $Purpose = trim($_POST["Purpose"]);
    }

    // Validate Department
    if (empty($_POST["DepartmentId"])) {
        $DepartmentId_err = "Please select a department.";
    } else {
        $DepartmentId = $_POST["DepartmentId"];
    }

    // Check for errors before inserting
    if (empty($Budget_err) && empty($Name_err) && empty($Purpose_err) && empty($DepartmentId_err)) {
        // Check available yearly funds
        $fundQuery = "SELECT Funds FROM tblYearlyFunds WHERE DepartmentId = ? ORDER BY Year DESC LIMIT 1";
        if ($stmt = $mysqli->prepare($fundQuery)) {
            $stmt->bind_param("i", $DepartmentId);
            $stmt->execute();
            $stmt->bind_result($availableFunds);
            $stmt->fetch();
            $stmt->close();
        }

        if ($availableFunds >= $Budget) {
            // Insert request
            $sql = "INSERT INTO tblRequest (Name, Budget, Purpose, DepartmentId) VALUES (?, ?, ?, ?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("sdsi", $Name, $Budget, $Purpose, $DepartmentId);
                if ($stmt->execute()) {
                    // Deduct budget from yearly funds
                    $updateFunds = "UPDATE tblYearlyFunds SET Funds = Funds - ? WHERE DepartmentId = ? ORDER BY Year DESC LIMIT 1";
                    if ($stmt2 = $mysqli->prepare($updateFunds)) {
                        $stmt2->bind_param("di", $Budget, $DepartmentId);
                        $stmt2->execute();
                        $stmt2->close();
                    }

                    // Redirect to manage request page
                    header("location: manage-Request.php");
                    exit();
                } else {
                    $page_err = "<div class='alert alert-danger'>Something went wrong. Please try again.</div>";
                }
                $stmt->close();
            }
        } else {
            $page_err = "<div class='alert alert-warning'>Insufficient funds in the selected department.</div>";
        }
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
                <h2>CREATE NEW RECORD</h2>                  
                <hr class="my-4" />
                <small style="font-size: small; color: #47748b;" class="pt-3 pb-2"><a href="index.php" class="text-light"><i class="fa-solid fa-house"></i><b>&nbsp;&nbsp;DASHBOARD</b></a>  &nbsp;&#124;&nbsp;  <i class=""></i><b>REQUESTS</b>  &nbsp;&#124;&nbsp;  <i class=""></i><b>Add Request</b></small>                                    
            </div>

            <!-- Home -->
            <div class="container text-light">
                <h3 style="color:#3b72f9;" class="pt-4">ADD REQUEST</h3>
                <?php echo $page_err;?>
            
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
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
                            <input type="submit" class="btn btn-primary" value="SUBMIT">
                            <a class="btn btn-outline-secondary" href="manage-Request.php">MANAGE REQUEST</a>
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