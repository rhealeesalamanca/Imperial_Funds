<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM admin WHERE username = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                
                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    // Bind result variables
                    $stmt->bind_result($id, $username, $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Username doesn't exist.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
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
    <title>LOGIN</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px; }
    </style>
</head>

<body>
    <section class="vh-100" style="background-color: #041a26;">
        <div class="container py-4 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-md-6 col-lg-6 col-12" >
                    <div class="card" style="border-radius: 1rem; background-color: #0d2533;">
                        <div class="row g-0">
                            <div class="card-body p-4 p-lg-5 text-black">
                
                                <div class="d-flex align-items-center mb-3 pb-1">
                                    <span class="h2 fw-bold mb-0 text-white text-center">Admin Login</span>
                                </div>
                                <h5 class="fw-normal mb-3 pb-3 text-white" style="letter-spacing: 1px;">Sign into your account.</h5>
                                    
                                <?php 
                                if(!empty($login_err)){
                                    echo '<div class="alert alert-danger">' . $login_err . '</div>';
                                }        
                                ?>
            
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="form-outline mb-4">
                                        <label class="text-secondary">Username</label>
                                        <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                                        <span class="invalid-feedback"><?php echo $username_err; ?></span>
                                    </div>     
                                    <div class="form-outline mb-4">
                                        <label class="text-secondary">Password</label>
                                        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                    </div>
                            
                                    <div class="pt-1 mb-4">
                                        <input type="submit" class="btn btn-primary" value="LOGIN">
                                    </div>
                                    <!-- <p style="color: #6bedaf;">Don't have an account? <a href="register.php">Sign up now</a>.</p> -->
                                    <a href="#!" class="small text-muted">Terms of use.</a>
                                    <a href="#!" class="small text-muted">Privacy policy</a>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-6 col-lg-6 col-12" >
                    <div class="card" style="border-radius: 1rem; background-color: #0d2533;">
                        <div class="row g-0">
                            <div class="card-body p-4 p-lg-5 text-black">
                
                                <div class="d-flex align-items-center mb-3 pb-1">
                                    <span class="h2 fw-bold mb-0 text-white text-center">For Student</span>
                                </div>
                                <h5 class="fw-normal mb-3 pb-3 text-white" style="letter-spacing: 1px;">Search your Schedule &nbsp<a href="find-Schedule.php">Click here.</a></h5>
                                    <a href="#!" class="small text-muted">Terms of use.</a>
                                    <a href="#!" class="small text-muted">Privacy policy</a>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>