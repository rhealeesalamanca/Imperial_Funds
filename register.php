<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email = $password = $confirm_password = $department_id = "";
$email_err = $password_err = $confirm_password_err = $department_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        // Check if email already exists
        $sql = "SELECT Usersid FROM users WHERE email = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = trim($_POST["email"]);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $email_err = "This email is already registered.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";     
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password !== $confirm_password) {
            $confirm_password_err = "Passwords do not match.";
        }
    }

    // Validate department
    if (empty(trim($_POST["department_id"]))) {
        $department_err = "Please select a department.";
    } else {
        $department_id = trim($_POST["department_id"]);
    }

    // Check input errors before inserting into database
    if (empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($department_err)) {
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (email, password, DepartmentId) VALUES (?, ?, ?)";
         
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ssi", $param_email, $param_password, $param_department);
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash password
            $param_department = $department_id;
            
            if ($stmt->execute()) {
                // Redirect to login page
                header("location: login.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font: 14px sans-serif; }
    </style>
</head>
<body>
<section class="vh-100" style="background-color: #041a26;">
    <div class="container py-1 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-xl-6">
                <div class="card" style="border-radius: 1rem; background-color: #0d2533;">
                    <div class="card-body p-4 p-lg-5 text-black">
                        <div class="d-flex align-items-center mb-3 pb-1">
                            <span class="h2 fw-bold mb-0 text-white">User Sign Up</span>
                        </div>
                        <h5 class="fw-normal mb-3 pb-3 text-white">Fill this form to create an account.</h5>

                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-outline mb-4">
                                <label class="text-secondary">Email</label>
                                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                                <span class="invalid-feedback"><?php echo $email_err; ?></span>
                            </div>   

                            <div class="form-outline mb-4">
                                <label class="text-secondary">Password</label>
                                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>

                            <div class="form-outline mb-4">
                                <label class="text-secondary">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                            </div>

                            <!-- Department Dropdown -->
                            <div class="form-outline mb-4">
                                <label class="text-secondary">Department</label>
                                <select name="department_id" class="form-control <?php echo (!empty($department_err)) ? 'is-invalid' : ''; ?>" required>
                                    <option value="">Select Department</option>
                                    <?php
                                    require_once "config.php";
                                    $result = $mysqli->query("SELECT DepartmentId, DepartmentName FROM tbldepartment");
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="'.$row["DepartmentId"].'">'.$row["DepartmentName"].'</option>';
                                    }
                                    ?>
                                </select>
                                <span class="invalid-feedback"><?php echo $department_err; ?></span>
                            </div>

                            <div class="pt-1 mb-4">
                                <input type="submit" class="btn btn-success" value="Register">
                            </div>

                            <p class="mb-5 pb-lg-2 text-white">Already have an account? <a href="login.php">Login here</a>.</p>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>