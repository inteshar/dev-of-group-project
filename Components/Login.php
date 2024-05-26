<?php
session_start(); // Start the session

$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "";
$DB_name = "development-of-group";

try {
    $conn = new PDO("mysql:host=$DB_host;dbname=$DB_name", $DB_user, $DB_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    function getDeviceName($userAgent)
    {
        $device = "Unknown Device";

        if (strpos($userAgent, 'iPhone') !== false) {
            $device = "iPhone";
        } elseif (strpos($userAgent, 'Android') !== false) {
            $device = "Android Phone";
        } elseif (strpos($userAgent, 'iPad') !== false) {
            $device = "iPad";
        } elseif (strpos($userAgent, 'Macintosh') !== false) {
            $device = "Macintosh";
        } elseif (strpos($userAgent, 'Windows') !== false) {
            $device = "Windows PC";
        }

        return $device;
    }

    $deviceName = getDeviceName($userAgent);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $_SESSION['login_user'] = $email;
        $_SESSION['login_time'] = time();
        $stmt = $conn->prepare("INSERT INTO login_logs (email, ip_address, device) 
                        VALUES (:email, :ipaddress, :device)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':ipaddress', $ipAddress);
        $stmt->bindParam(':device', $deviceName);
        $stmt->execute();
        header("location: ../Admin/");
    } else {
        $error = "Your Email or Password is invalid.";
    }
}
?>
<div class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col-sm-8 login-signup-form shadow p-4 rounded bg-light">
            <h4 class="text-center mb-3 fw-bold text-muted border-bottom border-3 pb-3">
                Admin Login
            </h4>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">
                        Email address
                    </label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" />
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">
                        Password
                    </label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" />
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                        <label class="form-check-label" for="flexCheckDefault">
                            Show Password
                        </label>
                    </div>
                </div>
                <?php
                if (isset($_GET['msg'])) {
                ?>
                    <p class="text-danger text-center fw-bold bg-danger-subtle rounded p-2">
                    <?php
                    $message = $_GET['msg'];
                    echo $message;
                } elseif(isset($error)){
                    echo "<p class='text-danger text-center fw-bold bg-danger-subtle rounded p-2'>".$error."</p>";
                }
                    ?>

                    </p>
                    <button class="btn btn-primary login-form-btn">
                        Login
                    </button>
            </form>
        </div>
    </div>
</div>
<script>
    // JavaScript code
    const passwordInput = document.getElementById('exampleInputPassword1');
    const showPasswordCheckbox = document.getElementById('flexCheckDefault');

    showPasswordCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        passwordInput.type = isChecked ? 'text' : 'password';
    });
</script>
<style>
    .login-signup-form {
        width: 50rem;
    }

    .login-form-btn {
        background: #2a6877;
        width: 100%;
        color: #fff;
        font-weight: bold;
    }

    .login-signup-toggle {
        cursor: pointer;
    }
</style>