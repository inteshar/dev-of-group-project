<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming the form is submitted using POST method
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (name, email, mobile, subject, message) VALUES (:name, :email, :mobile, :subject, :message)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);

        if ($stmt->execute()) {
            $msg = "<p class='alert alert-success text-success text-center fw-bold'>Thanks for contacting us.</p>";
        }
    } else {
        // Handle the case where one or more variables are empty
        $msg = "<p class='alert alert-danger text-danger text-center fw-bold'>All fields are required.</p>";
    }
}


?>

<div>
    <div class="container pb-5">
        <h4 class="text-muted fw-bold mb-5 text-center">Contact Us</h4>
        <div class="row d-flex justify-content-evenly">
            <div class="col-sm-4">
                <div class="row d-flex justify-content-evenly">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-geo-alt col-sm-4" viewBox="0 0 16 16">
                        <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                        <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                    </svg>
                    <div class="col-sm-8 border-start border-3 border-success mb-2 text-center">
                        <div>
                            <p class="text-muted mb-0 pb-0">Head Office Address</p>
                            <p class="fw-bold fs-5 mt-0 pt-0">
                                W-09 Khanpur Bazar, Samastipur, Bihar, 848117 India
                            </p>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-envelope col-sm-4" viewBox="0 0 16 16">
                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z" />
                    </svg>
                    <div class="col-sm-8 border-start border-3 border-success mb-2 text-center">
                        <div>
                            <p class="text-muted mb-0 pb-0">Email Address</p>
                            <p class="fw-bold fs-5 mt-0 pt-0">
                                developementofgroup@gmail.com
                            </p>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-phone col-sm-4" viewBox="0 0 16 16">
                        <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                        <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                    </svg>
                    <div class="col-sm-8 border-start border-3 border-success mb-2 text-center">
                        <div>
                            <p class="text-muted mb-0 pb-0">Mobile Number</p>
                            <p class="fw-bold fs-5 mt-0 pt-0">+91-9102531527</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 ps-5">
                <div class="p-4 rounded shadow">
                    <form action="#" method="POST">
                        <div class="form-group">
                            <?php 
                            if (isset($msg)) {
                                echo $msg;
                            }
                            ?>
                        </div>
                        <div class="form-floating mb-3 p-1">
                            <input type="text" class="form-control" name="name" id="floatingInput" required />
                            <label for="floatingInput">
                                Your Name<span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="form-floating mb-3 p-1">
                            <input type="email" class="form-control" name="email" id="floatingInput" required />
                            <label for="floatingInput">
                                Your Email address<span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="form-floating mb-3 p-1">
                            <input type="number" class="form-control" name="mobile" id="floatingInput" required />
                            <label for="floatingInput">
                                Your Mobile Number<span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="form-floating mb-3 p-1">
                            <input type="text" class="form-control" name="subject" id="floatingInput" required />
                            <label for="floatingInput">
                                Subject<span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="form-floating mb-3 p-1">
                            <textarea class="form-control" name="message" id="validationTextarea" required></textarea>
                            <label for="floatingInput">
                                Your Message<span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="" id="consentCheckbox" required>
                            <label class="form-check-label" for="consentCheckbox">
                                I am voluntarily submitting my information through this form.
                            </label>
                        </div>
                        <button class="btn btn-primary col-sm-12 fw-bold">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3590.3968104842083!2d85.92506547540357!3d25.856414277292505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMjXCsDUxJzIzLjEiTiA4NcKwNTUnMzkuNSJF!5e0!3m2!1sen!2snp!4v1702296826907!5m2!1sen!2snp" width="100%" height="450" style="border: 0; margin-top: 100" allowfullscreen="" loading="lazy" title="location" referrerpolicy="no-referrer-when-downgrade" class="shadow"></iframe>
</div>