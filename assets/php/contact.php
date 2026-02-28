<?php
require("PHPMailer/PHPMailerAutoload.php");
require("db_config.php"); // Load database configuration

// Temporarily enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ========== EMAIL CONFIGURATION ==========
// OPTION A: HOSTINGER EMAIL (Recommended - Works with real password)
// Create an email like info@yourdomain.com in Hostinger hPanel
$smtpHost = 'smtp.hostinger.com';           
$smtpPort = 465;                            // Use 465 for SSL
$smtpUser = 'ronimweb@gmail.com';           // REPLACE with your new email
$smtpPass = 'Ronimweb@2025';                // REPLACE with your real password
$smtpSecure = 'ssl';                        // Use 'ssl' for 465 or 'tls' for 587

/* 
// OPTION B: GMAIL (Requires "App Password" - Regular password will NOT work)
$smtpHost = 'smtp.gmail.com';               
$smtpPort = 465;                            
$smtpUser = 'your-gmail@gmail.com';           
$smtpPass = 'xxxx-xxxx-xxxx-xxxx';          // MUST be a 16-digit App Password
$smtpSecure = 'ssl';
*/

$recipientEmail = 'ronimweb@gmail.com';
$recipientName = 'Ronim Ambulance';

// ========== FORM DATA COLLECTION ==========
$senderName = isset($_POST['contact-name']) ? trim($_POST['contact-name']) : '';
$senderEmail = isset($_POST['contact-email']) ? trim($_POST['contact-email']) : '';
$senderPhone = isset($_POST['contact-phone']) ? trim($_POST['contact-phone']) : '';
$senderSubject = isset($_POST['contact-subject']) ? trim($_POST['contact-subject']) : 'General Inquiry';
$senderMessage = isset($_POST['contact-message']) ? trim($_POST['contact-message']) : '';

// ========== VALIDATION ==========
if (empty($senderName) || empty($senderEmail) || empty($senderMessage)) {
    echo '<div class="alert alert-danger" role="alert">Please fill in all required fields.</div>';
    exit;
}

if (!filter_var($senderEmail, FILTER_VALIDATE_EMAIL)) {
    echo '<div class="alert alert-danger" role="alert">Please enter a valid email address.</div>';
    exit;
}

// ========== BUILD EMAIL MESSAGE ==========
$message = '<html><body>';
$message .= '<table rules="all" style="border:1px solid #666; width:100%; max-width:600px;" cellpadding="10">';
$message .= '<tr style="background: #1b5e3f;"><td colspan="2" style="color:white;"><h3>New Contact Form Submission - Ronim Ambulance</h3></td></tr>';
$message .= "<tr><td style='background:#f9f9f9;'><strong>Name:</strong></td><td>" . htmlspecialchars($senderName) . "</td></tr>";
$message .= "<tr><td style='background:#f9f9f9;'><strong>Email:</strong></td><td>" . htmlspecialchars($senderEmail) . "</td></tr>";
$message .= "<tr><td style='background:#f9f9f9;'><strong>Phone:</strong></td><td>" . htmlspecialchars($senderPhone) . "</td></tr>";
$message .= "<tr><td style='background:#f9f9f9;'><strong>Subject:</strong></td><td>" . htmlspecialchars($senderSubject) . "</td></tr>";
$message .= "<tr><td colspan='2'><strong>Message:</strong><br/>" . nl2br(htmlspecialchars($senderMessage)) . "</td></tr>";
$message .= "</table>";
$message .= "</body></html>";

// ========== DATABASE STORAGE ==========
try {
    $conn = connectToDatabase();
    
    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO contact_submissions (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("sssss", $senderName, $senderEmail, $senderPhone, $senderSubject, $senderMessage);
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    // For debugging, echo the error. In production, you might want to only log it.
    echo '<div class="alert alert-warning" role="alert">Database Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    error_log("Database Connection/Insertion Error: " . $e->getMessage());
}

// ========== SEND EMAIL USING PHPMAILER ==========
try {
    $mail = new PHPMailer();
    
    // SMTP Configuration
    $mail->isSMTP();
    $mail->SMTPDebug = 2; // Enable SMTP debug for troubleshooting
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->SMTPSecure = $smtpSecure; // Using the variable from config
    $mail->Port = $smtpPort;
    $mail->Timeout = 10; // Set timeout to 10 seconds
    
    // Email Details
    $mail->setFrom($smtpUser, 'Ronim Ambulance');
    $mail->addReplyTo($senderEmail, $senderName);
    $mail->addAddress($recipientEmail, $recipientName);
    
    // Message Content
    $mail->isHTML(true);
    $mail->Subject = 'Contact Form: ' . htmlspecialchars($senderSubject) . ' from ' . htmlspecialchars($senderName);
    $mail->Body = $message;
    
    // Send
    if ($mail->send()) {
        echo '<div class="alert alert-success" role="alert"><strong>Thank you!</strong> We have received your message and will contact you shortly.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error sending email. Please contact us directly: 0702 055 055</div>';
    }
    
} catch (Exception $e) {
    echo '<div class="alert alert-danger" role="alert">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>