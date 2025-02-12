<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include your database connection file
require_once 'config.php';

// Function to get the count from the database
function getCount($table, $mysqli) {
    // Query to get the count
    $query = "SELECT COUNT(*) as count FROM $table";
    $result = $mysqli->query($query);

    // Check for errors
    if (!$result) {
        die("ERROR: Could not execute query. " . $mysqli->error);
    }

    // Fetch the count
    $row = $result->fetch_assoc();
    return $row['count'];
}
?>

<!DOCTYPE html>
<html>
<!-- Head -->  
<?php include('includes/head.php');?> 
<body  id="myPage" data-bs-spy="scroll" data-bs-target=".navbar">
    <div class="wrapper">

        <!-- Sidebar Holder -->
        <?php include('includes/sidebar.php');?> 

        <!-- Page Content Holder -->
        <div id="content">
            <?php include('includes/navbar.php');?>

            <!-- Home -->   
            <div class ="container">
                <div class="row pt-1 pb-4">
                    <div class="col-md-4 col-lg-4 col-12">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5 col-lg-4 col-7">
                                        <a class="bi bi-people ms-3" id="icon"></a>  
                                    </div>
                                    <div class="col-md-7 col-lg-7 col-7 justify-content-center ms-4">
                                        <h1 style="font-size: 50px; line-height: 0.4;" class="pt-4"><?php echo getCount('tblRequest', $mysqli); ?></h1>
                                        <h5 class="pt-2 text-white">REQUEST</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex bg-light text-dark">
                                <a href="manage-Request.php" class="stretched-link"><b>View Details</b></a>
                                <span class="ms-auto">
                                <i class="bi bi-caret-right-fill text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-12">
                        <div class="card bg-secondary text-white">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5 col-lg-4 col-7">
                                        <a class="bi bi-person-video3 ms-3" id="icon"></a>  
                                    </div>
                                    <div class="col-md-7 col-lg-7 col-7 justify-content-center ms-4">
                                        <h1 style="font-size: 50px; line-height: 0.4;" class="pt-4"><?php echo getCount('tblYearlyFunds', $mysqli); ?></h1>
                                        <h5 class="pt-2 text-white">YEARLY FUND</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex bg-light text-dark">
                                <a href="manage-YearlyFunds.php" class="stretched-link"><b>View Details</b></a>
                                <span class="ms-auto">
                                <i class="bi bi-caret-right-fill text-secondary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-12">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5 col-lg-4 col-7">
                                        <a class="bi bi-door-closed ms-3" id="icon"></a>  
                                    </div>
                                    <div class="col-md-7 col-lg-7 col-7 justify-content-center ms-4">
                                        <h1 style="font-size: 50px; line-height: 0.4;" class="pt-4"><?php echo getCount('tblDepartment', $mysqli); ?></h1>
                                        <h5 class="pt-2 text-white">DEPARTMENT</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex bg-light text-dark">
                                <a href="manage-Department.php" class="stretched-link"><b>View Details</b></a>
                                <span class="ms-auto">
                                <i class="bi bi-caret-right-fill text-success"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
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

        $(function(){
        var str = '#len'; //increment by 1 up to 1-nelemnts
        $(document).ready(function(){
            var i, stop;
            i = 1;
            stop = 4; //num elements
            setInterval(function(){
            if (i > stop){
                return;
            }
            $('#len'+(i++)).toggleClass('bounce');
            }, 500)
        });
        });

        function toggleFullScreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            localStorage.setItem('fullscreen', 'true'); // Store fullscreen state
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
                localStorage.removeItem('fullscreen'); // Remove fullscreen state
            }
        }
    }

    // Check and apply fullscreen on page load
    document.addEventListener('DOMContentLoaded', function () {
        const fullscreenState = localStorage.getItem('fullscreen');
        if (fullscreenState === 'true') {
            document.documentElement.requestFullscreen();
        }
    });
    </script>

    <style>
        #holder{
            border-radius: 8px;
            margin-right: 2px;
            margin-bottom: 20px;
        }
        #icon{
            font-size: 65px;
        }
        #social{
            color:#3b72f9;
            font-size: 30px;
        }
        #head{
            margin-top: 20px;
        }
        #jumbotron{
            background-image: url('image/jumbotron.jpg'); 
            background-repeat: no-repeat; 
            border-radius: 8px; 
            background-size:100% 115%; 
            height: 300px;
        }
    </style>
</body>
</html>