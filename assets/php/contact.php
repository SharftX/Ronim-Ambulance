<?php
// ADD your Email and Name
$recipientEmail = 'chathukarandils@gmail.com';
$recipientName = 'Chathuka Siriwardana';

// Collect the posted variables
$senderName = isset($_POST['contact-name']) ? trim($_POST['contact-name']) : '';
$senderEmail = isset($_POST['contact-email']) ? trim($_POST['contact-email']) : '';
$senderPhone = isset($_POST['contact-phone']) ? trim($_POST['contact-phone']) : '';
$senderSubject = isset($_POST['contact-subject']) ? trim($_POST['contact-subject']) : 'General Inquiry';
$senderMessage = isset($_POST['contact-message']) ? trim($_POST['contact-message']) : '';

// Validate required fields
if (empty($senderName) || empty($senderEmail) || empty($senderMessage)) {
    echo '<div class="alert alert-danger" role="alert">Please fill in all required fields.</div>';
    exit;
}

// Validate email format
if (!filter_var($senderEmail, FILTER_VALIDATE_EMAIL)) {
    echo '<div class="alert alert-danger" role="alert">Please enter a valid email address.</div>';
    exit;
}

// Build HTML message body
$message = '<html><body>';
$message .= '<table rules="all" style="border:1px solid #666; width:100%; max-width:600px;" cellpadding="10">';
$message .= '<tr style="background: #1b5e3f;"><td colspan="2" style="color:white;"><h3>New Contact Form Submission - Ronim Ambulance</h3></td></tr>';
$message .= "<tr><td style='background:#f9f9f9;'><strong>Name:</strong></td><td>" . htmlspecialchars($senderName) . "</td></tr>";
$message .= "<tr><td style='background:#f9f9f9;'><strong>Email:</strong></td><td>" . htmlspecialchars($senderEmail) . "</td></tr>";
$message .= "<tr><td style='background:#f9f9f9;'><strong>Phone:</strong></td><td>" . htmlspecialchars($senderPhone) . "</td></tr>";
$message .= "<tr><td style='background:#f9f9f9;'><strong>Subject:</strong></td><td>" . htmlspecialchars($senderSubject) . "</td></tr>";
$message .= "<tr><td colspan='2'><strong>Message:</strong><br/>" . nl2br(htmlspecialchars($senderMessage)) . "</td></tr>";
$message .= "</table>";
$message .= "<br/><p style='color:#666; font-size:12px;'>Sent from: " . htmlspecialchars($senderEmail) . "</p>";
$message .= "</body></html>";

// Set email headers
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: " . htmlspecialchars($senderName) . " <" . htmlspecialchars($senderEmail) . ">\r\n";
$headers .= "Reply-To: " . htmlspecialchars($senderEmail) . "\r\n";

// Send email using PHP's mail function
$emailSubject = 'New Message From ' . htmlspecialchars($senderName) . ' - ' . htmlspecialchars($senderSubject);

if (mail($recipientEmail, $emailSubject, $message, $headers)) {
    echo '<div class="alert alert-success" role="alert"><strong>Thank you!</strong> We have received your message and will contact you shortly.</div>';
} else {
    echo '<div class="alert alert-danger" role="alert">Error sending email. Please try again later or contact us directly.</div>';
}
?>