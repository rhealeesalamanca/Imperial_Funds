<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$DepartmentName = "";
$DepartmentName_err = "";
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
    if (empty($DepartmentName_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO tblDepartment (DepartmentName) VALUES (?)";

        if ($stmt = $mysqli->prepare($sql)) {

            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_DepartmentName); // "s" = string, "d" = double (number)

            // Set parameters
            $param_DepartmentName = $DepartmentName;

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

            <!-- Jumbotron -->
            <div class="p-4 shadow rounded-4 mb-4" style="background: linear-gradient(90deg, #FFD42A, #FFA500);">
                <h2 class="text-dark fw-bold">CREATE NEW RECORD</h2>
                <hr class="my-3" />
                <small class="d-block text-dark">
                    <a href="index.php" class="text-dark text-decoration-none">
                        <i class="fa-solid fa-house"></i> <b>DASHBOARD</b>
                    </a> &nbsp;|&nbsp;
                    <b>DEPARTMENT</b> &nbsp;|&nbsp;
                    <b>Create Department</b>
                </small>
            </div>

            <!-- Form Section -->
            <div class="container bg-white p-4 rounded-4 shadow-sm">
                <h3 class="text-dark fw-bold">CREATE DEPARTMENT</h3>
                <?php echo $page_err; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="pt-3">
                    <div class="row">
                        <div class="col-md-12 pb-3">
                            <label class="form-label">Department Name:</label>
                            <input type="text" name="DepartmentName" class="form-control rounded-3 uppercase-input <?php echo (!empty($DepartmentName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $DepartmentName; ?>">
                            <span class="invalid-feedback"><?php echo $DepartmentName_err; ?></span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pt-4">
                        <button type="submit" class="btn btn-secondary rounded-3 px-4">SUBMIT</button>
                        <a href="manage-Department.php" class="btn btn-outline-secondary rounded-3 px-4">MANAGE DEPARTMENTS</a>
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