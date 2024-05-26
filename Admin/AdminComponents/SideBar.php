<?php
session_start(); // Start the session
// Check if the user is not logged in
if (!isset($_SESSION['login_user'])) {
    header("Location: ../../pages/LoginPage.php?msg=Login to access the admin panel."); // Redirect to the login page
    exit; // Stop further execution
}

$file1 = '../../dbConnect.php';
$file2 = '../dbConnect.php';

if (file_exists($file1)) {
    require_once $file1;
} else {
    require_once $file2;
}
$stmt = $conn->prepare("SELECT role FROM users WHERE email = :login_user");
$stmt->bindParam(':login_user', $_SESSION['login_user']); 
$stmt->execute();
$userRole = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<div>
    <div class="p-3 pt-0 d-flex justify-content-between shadow">
        <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
            </svg>
        </button>
        <div class="card-group align-items-center">
            <p class="card fw-bold p-2 m-2 me-0 border-0">
                <img src="../../Assets/logo.png" alt="" style="height: 40px; width: 50px" />
                Development of Group
            </p>
            <div class="card">
                <p class="text-center border-0 text-light loggedinusermail p-2 rounded shadow">
                    <?php echo $_SESSION['login_user'] ?>
                </p>
                <p style="font-size: 12px;" class="fw-bold text-center border-0 text-danger p-0 m-0" id="timeCounter"></p>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start sideBar shadow border-0" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold" id="offcanvasScrollingLabel">
                Admin Panel
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body bg-light">
            <ul class="navbar-nav sidebar-ul">
                <li>
                    <a href="/admin" class="sidebar-link btn btn-secondary shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-speedometer2" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4M3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z" />
                            <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10m8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a class="sidebar-link btn btn-secondary shadow" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-gear" viewBox="0 0 16 16">
                            <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0" />
                        </svg>
                        Account Management
                    </a>
                    <div class="collapsible-links">
                        <div>
                            <div class="collapse multi-collapse collapsible-as" id="multiCollapseExample1">
                                <div class="card-body border-0 bg-transparent">
                                    <ul class="sidebar-ul">
                                        <a href="../../Admin/Pages/SavingsAccountPage.php" class="sidebar-link btn btn-secondary shadow">
                                            Savings Accounts
                                        </a>
                                        <a href="../../Admin/Pages/LoanAccountPage.php" class="sidebar-link btn btn-secondary shadow">
                                            Loan Accounts
                                        </a>
                                        <?php if ($userRole['role'] === 'admin') {
                                            echo '
                                        <a class="sidebar-link btn btn-secondary shadow" href="../../Admin/Pages/AccountRequest.php">
                                            Loan Requests
                                        </a>
                                        ';
                                        } ?>
                                        <!-- <a href="../../Admin/Pages/FDAccountPage.php" class="sidebar-link btn btn-secondary shadow">
                                            FD Accounts
                                        </a> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <a class="sidebar-link btn btn-secondary shadow" data-bs-toggle="collapse" href="#multiCollapseExample2" role="button" aria-expanded="false" aria-controls="multiCollapseExample2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                        </svg>
                        Member Management
                    </a>
                    <div class="collapsible-links">
                        <div>
                            <div class="collapse multi-collapse collapsible-as" id="multiCollapseExample2">
                                <div class="card-body border-0 bg-transparent">
                                    <ul class="sidebar-ul">
                                        <a href="../../Admin/Pages/ManageMemberPage.php" class="sidebar-link btn btn-secondary shadow">
                                            Manage Member
                                        </a>
                                        <?php if ($userRole['role'] === 'admin') {
                                            echo '
                                        <a href="../../Admin/Pages/MemberKYCPage.php" class="sidebar-link btn btn-secondary shadow">
                                            Member KYC
                                        </a>
                                        ';
                                        } ?>
                                        <!-- <li class="sidebar-link btn btn-secondary shadow">
                                            Member Request
                                        </li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <?php if ($userRole['role'] === 'admin') {
                    echo '<li>
                                            <a class="sidebar-link btn btn-secondary shadow" data-bs-toggle="collapse" href="#multiCollapseExample3" role="button" aria-expanded="false" aria-controls="multiCollapseExample3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-shield-lock" viewBox="0 0 16 16">
                                                    <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56" />
                                                    <path d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99a1.5 1.5 0 1 1 2-1.415z" />
                                                </svg>
                                                Staff Management
                                            </a>
                                            <div class="collapsible-links">
                                                <div>
                                                    <div class="collapse multi-collapse collapsible-as" id="multiCollapseExample3">
                                                        <div class="card-body border-0 bg-transparent">
                                                            <ul class="sidebar-ul">
                                                                <li>
                                                                    <a href="../../Admin/Pages/ManageStaffPage.php" class="sidebar-link btn btn-secondary shadow">
                                                                        Manage Staff
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="../../Admin/Pages/LoginLogs.php" class="sidebar-link btn btn-secondary shadow">
                                                                        Login Logs
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>';
                } ?>

                <!-- <li>
                    <a href="/admin/track-loan" class="sidebar-link btn btn-secondary shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-map" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103M10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98 4-.8V1.11l-4 .8zm-6-.8V1.11l-4 .8v12.98z" />
                        </svg>
                        Track Loan History
                    </a>
                </li> -->
                <li>
                    <a href="../../Admin/Pages/Payments.php" class="sidebar-link btn btn-secondary shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-currency-rupee" viewBox="0 0 16 16">
                            <path d="M4 3.06h2.726c1.22 0 2.12.575 2.325 1.724H4v1.051h5.051C8.855 7.001 8 7.558 6.788 7.558H4v1.317L8.437 14h2.11L6.095 8.884h.855c2.316-.018 3.465-1.476 3.688-3.049H12V4.784h-1.345c-.08-.778-.357-1.335-.793-1.732H12V2H4z" />
                        </svg>
                        Payments
                    </a>
                </li>
                <?php
                if ($userRole['role'] === 'admin'){
                    echo "<li>
                    <a href='../../Admin/Pages/TodaysCollection.php' class='sidebar-link btn btn-secondary shadow'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-bag-check' viewBox='0 0 16 16'>
                    <path fill-rule='evenodd' d='M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0'/>
                    <path d='M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z'/>
                  </svg>
                        Today's Collection
                    </a>
                </li>";
                }
                ?>
                <li>
                    <a href="../../Admin/AdminComponents/Logout.php" class="logout-btn btn btn-danger shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                        </svg>
                        Logout
                    </a>
                </li>
            </ul>
            <div class="text-center text-muted">
                <p class="text-center pt-3 mb-0 pb-0">
                    Developed by
                    <a target="_blank" href="https://bento.me/mrxiwlev">
                        <img src='../../Assets/devlogo.png' alt="" style="height: 80px;" />
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
<script>
    // Function to format duration
    function formatDuration(duration) {
        var hours = Math.floor(duration / 3600);
        var minutes = Math.floor((duration % 3600) / 60);
        var seconds = duration % 60;

        return hours.toString().padStart(2, '0') + ":" +
            minutes.toString().padStart(2, '0') + ":" +
            seconds.toString().padStart(2, '0');
    }

    // Function to update time counter
    function updateTimeCounter() {
        // Calculate duration in seconds
        var currentTime = Math.floor(Date.now() / 1000); 
        var loginTime = <?php echo isset($_SESSION['login_time']) ? $_SESSION['login_time'] : 0; ?>;
        var elapsedTime = currentTime - loginTime;

        // Calculate remaining time (10 minutes - elapsed time)
        var remainingTime = 600 - elapsedTime;

        // Format and display remaining duration
        var timeCounter = document.getElementById("timeCounter");
        timeCounter.textContent = "You will be logged out in: " + formatDuration(remainingTime);

        // Check if the remaining time is less than or equal to 0
        if (remainingTime <= 0) {
            // Redirect to logout page or perform logout action
            window.location.href = "../../Admin/AdminComponents/Logout.php?auto=1";
        } else {
            // Update every second
            setTimeout(updateTimeCounter, 1000);
        }
    }

    // Initial call to start updating time counter
    updateTimeCounter();
</script>


<style>
    .sideBar {
        max-width: 250px;
    }

    .sidebar-ul {
        min-height: 90%;
        list-style: none;
    }

    .sidebar-link {
        background: #2a6877;
        width: 100%;
        margin: 5px 0;
        text-decoration: none;
        color: #fff;
        text-align: start;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logout-btn {
        width: 100%;
        height: 40px;
        margin: 5px 0;
        text-decoration: none;
        color: #fff;
        text-align: start;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .collapsible-links {
        border-left: 2px solid #2a6877;
    }

    .loggedinusermail {
        background: #2a6877;
        cursor: none;
    }
</style>