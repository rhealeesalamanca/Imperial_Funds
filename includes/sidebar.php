<nav id="sidebar">
    <div class="sidebar-header">
        <img src="image/logo.jpg" class="img-fluid" height="100" alt="MES">
    </div>
    <ul class="list-unstyled components">
    <li>
    <a href="index.php" style="color: black;">
        &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-house"></i>&nbsp;&nbsp; DASHBOARD
    </a>
</li>

    </ul>
    <ul class="list-unstyled components">
    <li>
    <h6 style="font-size: 15px; color: black;" class="text-dark">&nbsp;&nbsp;&nbsp; MAINTENANCE</h6>
</li>
<li>
    <a href="#requestSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle" style="color: black;">
        &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-user"></i>&nbsp;&nbsp; PENDING REQUEST
    </a>
    <ul class="collapse list-unstyled" id="requestSubmenu">
        <li>
            <a href="create-Request.php" style="color: black;">
                &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-plus"></i>&nbsp;&nbsp; Add Request
            </a>
        </li>
        <li>
            <a href="manage-Request.php" style="color: black;">
                &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-grip-lines"></i>&nbsp;&nbsp; Manage Requests
            </a>
        </li>
    </ul>
</li>
<li>
    <a href="#yearlySubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle" style="color: black;">
        &nbsp;&nbsp;&nbsp;<i class="fa-solid fa-money-bills"></i>&nbsp;&nbsp; YEARLY FUND
    </a>
    <ul class="collapse list-unstyled" id="yearlySubmenu">
        <li>
            <a href="create-YearlyFunds.php" style="color: black;">
                &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-plus"></i>&nbsp;&nbsp; Add Yearly Fund
            </a>
        </li>
        <li>
            <a href="manage-YearlyFunds.php" style="color: black;">
                &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-grip-lines"></i>&nbsp;&nbsp; Manage Yearly Funds
            </a>
        </li>
    </ul>
</li>
<li>
    <a href="#departmentSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle" style="color: black;">
        &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-door-closed"></i>&nbsp;&nbsp; DEPARTMENT
    </a>
    <ul class="collapse list-unstyled" id="departmentSubmenu">
        <li>
            <a href="create-Department.php" style="color: black;">
                &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-plus"></i>&nbsp;&nbsp; Create Department
            </a>
        </li>
        <li>
            <a href="manage-Department.php" style="color: black;">
                &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-grip-lines"></i>&nbsp;&nbsp; Manage Departments
            </a>
        </li>
    </ul>
</li>
    <ul class="list-unstyled CTAs">
        <li>
            <a class="btn btn-outline-dark" href="reset-password.php" class="article">Reset Your Password</a>
        </li>
        <li>
            <a class="btn btn-outline-dark" href="admin-register.php" class="article">Add Another Admin</a>
        </li>
    </ul>
</nav>