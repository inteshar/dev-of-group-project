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


    function getBrowser() 
    { 
        $u_agent = $_SERVER['HTTP_USER_AGENT']; 
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        // First get the platform
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'Linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'Mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'Windows';
        }
        elseif (preg_match('/iphone/i', $u_agent)) {
            $platform = 'iPhone';
        }
        elseif (preg_match('/ipad/i', $u_agent)) {
            $platform = 'iPad';
        }
        elseif (preg_match('/android/i', $u_agent)) {
            $platform = 'Android';
        }
        elseif (preg_match('/blackberry/i', $u_agent)) {
            $platform = 'BlackBerry';
        }
        elseif (preg_match('/webos/i', $u_agent)) {
            $platform = 'Mobile';
        }
        
        // Next get the name of the browser
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'Internet Explorer'; 
            $ub = "MSIE"; 
        } 
        elseif(preg_match('/Firefox/i',$u_agent)) 
        { 
            $bname = 'Mozilla Firefox'; 
            $ub = "Firefox"; 
        } 
        elseif(preg_match('/Chrome/i',$u_agent)) 
        { 
            $bname = 'Google Chrome'; 
            $ub = "Chrome"; 
        } 
        elseif(preg_match('/Safari/i',$u_agent)) 
        { 
            $bname = 'Apple Safari'; 
            $ub = "Safari"; 
        } 
        elseif(preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'Opera'; 
            $ub = "Opera"; 
        } 
        elseif(preg_match('/Netscape/i',$u_agent)) 
        { 
            $bname = 'Netscape'; 
            $ub = "Netscape"; 
        } 
        
        // Finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
        
        // See how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            // We will have two since we are not using 'other' argument yet
            // See if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }
        
        // Check if we have a number
        if ($version==null || $version=="") {$version="?";}
        
        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'   => $pattern
        );
    } 

    // Now try it
    $ua = getBrowser();
    $deviceType = (preg_match('/Mobile|Android|BlackBerry|iPhone|iPad|webOS/i', $ua['userAgent'])) ? 'Mobile' : 'PC';
    $deviceDetails = "Device: " . $deviceType . "\nBrowser: " . $ua['name'] . "\nVersion: " . $ua['version'] . "\nPlatform: " . $ua['platform'] . "\nUser Agent: " . $ua['userAgent'];

    $deviceName = nl2br($deviceDetails);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $_SESSION['login_user'] = $email;
        $_SESSION['login_time'] = time();
        $time = date('Y-m-d h:i:s A');
        $stmt = $conn->prepare("INSERT INTO login_logs (email, datetime, ip_address, device) 
                        VALUES (:email, :time, :ipaddress, :device)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':time', $time);
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