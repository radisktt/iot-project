
<div class="">
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark justify-content-between">
        <div class="container-fluid">
            <div class="d-flex justify-content-start align-items-center col-2 col-md-3"> 
                <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
                    <img src="../iot/assets/ptitlogo.png" alt="Logo PTIT" style="width: 50px;" class="rounded-pill">
                    <span class="navbar-text d-none d-lg-block" style="color: ghostwhite; margin-top: 10px;">IoT và Ứng dụng</span>
                </a> 
            </div>
            <!-- Menu -->
            <ul class="navbar-nav col d-none d-sm-flex justify-content-center">    
                <li class="nav-item" style="margin-top: 10px;">
                    <a class="nav-link <?= ($currentPage == 'dashboard.php' or $currentPage == "") ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item" style="margin-top: 10px;">
                    <a class="nav-link <?= ($currentPage == 'datasensor.php') ? 'active' : '' ?>" href="datasensor.php">Datasensor</a>
                </li>
                <li class="nav-item" style="margin-top: 10px;">
                    <a class="nav-link <?= ($currentPage == 'actionhistory.php') ? 'active' : '' ?>" href="actionhistory.php">Action History</a>
                </li>
                <li class="nav-item" style="margin-top: 10px;">
                    <a class="nav-link <?= ($currentPage == 'newdashboard.php') ? 'active' : '' ?>" href="newdashboard.php">New Dashboard</a>
                </li>
            </ul>
            <div class="dropdown d-sm-none">
                <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Menu
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">Dashboard</a></li>
                    <li><a class="dropdown-item <?= ($currentPage == 'datasensor.php') ? 'active' : '' ?>" href="datasensor.php">Datasensor</a></li>
                    <li><a class="dropdown-item <?= ($currentPage == 'actionhistory.php') ? 'active' : '' ?>" href="actionhistory.php">Action History</a></li>
                    <li><a class="dropdown-item <?= ($currentPage == 'newsdashboard.php') ? 'active' : '' ?>" href="newdashboard.php">New Dashboard</a></li>
                </ul>
            </div>
            <!-- Avatar -->
            <div class="d-sm-flex justify-content-end align-items-center col-xl-3">
                <a class="navbar-brand" href="profile.php">
                    <img src="../iot/assets/LVT.jpg" alt="Avatar" style="width: 50px; height: 50px; object-fit: cover;" class="rounded-circle">
                    <span class="navbar-text d-none d-sm-inline" style="color: ghostwhite;">Admin</span>
                </a>
            </div>
            
        </div>
        
    </nav>
</div>
