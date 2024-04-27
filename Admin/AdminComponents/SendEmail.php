<?php
$to = "intesharalam01@gmail.com"; // Email address of the recipient
$subject = "Test Email"; // Email subject
$message = "This is a test email sent from PHP."; // Email message
$headers = "From: sender@example.com"; // Sender's email address

// Send email
if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}
