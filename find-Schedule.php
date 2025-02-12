<?php
// Include config file
require_once "config.php";

// Initialize ClassId
$ClassId = isset($_POST['ClassId']) ? $_POST['ClassId'] : '';

// Fetching Class Ids from another table
$classIds = array();
$sqlClassIds = "SELECT ClassId, GradeLevel, Section FROM tblClasses ORDER BY GradeLevel,Section ASC";
$resultClassIds = $mysqli->query($sqlClassIds);

while ($rowClassIds = $resultClassIds->fetch_assoc()) {
    $id = $rowClassIds['ClassId'];
    $gradesection = $rowClassIds['GradeLevel'] . ' - ' . $rowClassIds['Section'];
    $classIds[$id] = $gradesection;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MES - Muntinlupa Elementary School</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
    </style>
</head>

<body>
    <section class="vh-100" style="background-color: #041a26;">
        <div class="container py-4 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-6" >
                    <div class="card" style="border-radius: 1rem; background-color: #0d2533;">
                        <div class="row g-0">
                            <div class="card-body p-4 p-lg-5 text-black">
                
                                <div class="d-flex align-items-center mb-3 pb-1">
                                    <span class="h2 fw-bold mb-0 text-white text-center">MUNTINLUPA ELEMENTARY SCHOOL</span>
                                </div>

                                <form action="result-Schedule.php" method="post">
                                    <div class="form-outline mb-4">
                                        <label class="text-secondary">Enter your LRN:</label>
                                        <input type="number" name="LRN" id="LRN" class="form-control" required>
                                    </div>     
                                    <div class="form-outline mb-4">
                                        <label class="text-secondary">Select your Class:</label>
                                        <select name="ClassId" class="form-control">
                                            <option value="" selected disabled><--Select Class--></option>
                                                <?php foreach ($classIds as $id => $gradesection) : ?>
                                                    <option value="<?php echo $id; ?>" <?php echo ($ClassId == $id) ? 'selected' : ''; ?>><?php echo $gradesection; ?></option>
                                                <?php endforeach; ?>
                                        </select>
                                    </div>
                            
                                    <div class="pt-1 mb-4">
                                            <button type="submit" class="btn btn-primary">SEARCH</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>