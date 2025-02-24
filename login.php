<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

// Include database connection
require_once "config.php";

// Initialize variables
$email = $password = $department = "";
$email_err = $password_err = $department_err = $login_err = "";

// Fetch Department IDs for the dropdown
$departments = [];
$sql = "SELECT DepartmentId, DepartmentName FROM tbldepartment"; // Ensure table name is correct
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[$row["DepartmentId"]] = $row["DepartmentName"];
    }
}

// Process form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }
    
    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate department
    if (empty($_POST["department"])) {
        $department_err = "Please select your department.";
    } else {
        $department = (int)$_POST["department"]; // Ensure it's an integer
    }
    
    // Check for errors before querying database
    if (empty($email_err) && empty($password_err) && empty($department_err)) {
        $sql = "SELECT Usersid, email, password, DepartmentId FROM users WHERE email = ? AND DepartmentId = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("si", $param_email, $param_department);
            $param_email = $email;
            $param_department = $department;

            if ($stmt->execute()) {
                $stmt->store_result();

                // Check if user exists
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $email, $hashed_password, $db_department);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Start session and store user details
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;
                            $_SESSION["department_id"] = $db_department;

                            // Redirect to user dashboard
                            header("location: index.php");
                            exit;
                        } else {
                            $login_err = "Invalid password."; 
                        }
                    }
                } else {
                    $login_err = "No account found with this email and department.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again.";
            }
            $stmt->close();
        }
    }
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LOGIN</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font: 14px; }
    </style>
</head>
<body>
    <section class="vh-100" style="background-color:rgb(224, 114, 11);">
        <div class="container py-4 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                
                <!-- USER LOGIN -->
                <div class="col-md-6">
                    <div class="card" style="border-radius: 1rem; background-color:#0d2533;">
                        <div class="card-body text-white">
                            <h2 class="text-center">User Login</h2>
                            <h5 class="mb-3">Sign into your account.</h5>

                            <!-- Display login errors -->
                            <?php if (!empty($login_err)) { echo '<div class="alert alert-danger">' . $login_err . '</div>'; } ?>

                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                                </div>     

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                </div>

                                <!-- Department Dropdown -->
                                <div class="form-group">
                                    <label>Department</label>
                                    <select name="department" class="form-control <?php echo (!empty($department_err)) ? 'is-invalid' : ''; ?>">
                                        <option value="" selected disabled><--Select Department--></option>
                                        <?php foreach ($departments as $id => $name): ?>
                                            <option value="<?php echo $id; ?>" <?php echo ($department == $id) ? 'selected' : ''; ?>>
                                                <?php echo $name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $department_err; ?></span>
                                </div>

                                <div class="mt-3">
                                    <input type="submit" class="btn btn-primary btn-block" value="LOGIN">
                                </div>
                                <p class="mt-3">Don't have an account? <a href="register.php">Sign up now</a>.</p>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- ADMIN LOGIN -->
                <div class="col-md-6">
                    <div class="card" style="border-radius: 1rem; background-color:rgb(224, 61, 11);">
                        <div class="card-body text-white">
                            <h2 class="text-center">Admin Login</h2>
                            <h5 class="mb-3">Login as an admin. <a href="admin-login.php" class="text-light">Click here.</a></h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</body>
</html>