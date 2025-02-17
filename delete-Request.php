<?php
// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Include config file
    require_once "config.php";
    
    // Prepare a delete statement
    $sql = "DELETE FROM tblRequest WHERE RequestId = ?";
    
    if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_RequestId);
        
        // Set parameters
        $param_RequestId = trim($_POST["id"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Records deleted successfully. Redirect to landing page
            header("location: manage-Request.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    $stmt->close();
    
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
            
            <div class="container text-secondary">
                <div class="row" style="justify-content: center;">
                    <div class="col-md-6">
                        <div class="container" style="text-align: center;">
                        <h3 style="color:#3b72f9;" class="pt-4">DELETE RECORD</h3>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="alert alert-danger">
                                <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                                <p style="color: black;">Are you sure you want to delete this record?</p>
                                <p>
                                    <input type="submit" value="Yes" class="btn btn-outline-danger">
                                    <a href="manage-Request.php" class="btn btn-outline-secondary ml-2">No</a>
                                </p>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>        
            </div>
        <div>
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
</body>
</html>