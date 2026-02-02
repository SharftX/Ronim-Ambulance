# Contact Form - Testing & Configuration Guide

## ✅ What I Fixed

### 1. **PHP Email Issues**
   - Fixed missing `$senderEmail` variable (was causing undefined variable error)
   - Fixed email sending logic - now sends to recipient email correctly
   - Added input validation for required fields
   - Added security measures (htmlspecialchars, nl2br) to prevent XSS attacks
   - Fixed label mismatch (Message was labeled as "Email" in table)
   - Added subject line from form dropdown

### 2. **HTML Form Updates**
   - Added `name="contact-subject"` attribute to subject dropdown
   - Added proper ID and value attributes to subject dropdown options
   - Now the subject selection is properly captured and sent

### 3. **PHP Email Body**
   - Now includes all fields: Name, Email, Phone, Subject, Message
   - Better HTML formatting with proper table styling
   - Messages display with line breaks preserved
   - Added email validation

## 🧪 How to Test the Form

### **Option 1: Local Testing (EASY - Recommended First)**

1. **Set Up a Local Mail Catcher** (Gmail)
   - Update `$recipientEmail` in `assets/php/contact.php`:
   ```php
   $recipientEmail='your-email@gmail.com';
   ```

2. **Fill and Submit the Form:**
   - Go to http://localhost/your-project/contact-us.html (or your server URL)
   - Fill in all required fields:
     - Name: Test User
     - Email: test@example.com
     - Phone: 123-456-7890
     - Subject: General Inquiry (or any option)
     - Message: This is a test message
   - Click "Submit Request"

3. **Check Results:**
   - **Success Message**: You should see green alert saying "Thank you! We have received your message..."
   - **Check Email**: Look in your Gmail inbox for the email from test@example.com
   - **Check Spam**: If not in inbox, check spam/promotions folder

---

### **Option 2: Advanced Testing (For Debugging)**

#### **Step 1: Enable PHP Error Logging**
Create a file `assets/php/test-connection.php`:
```php
<?php
// Test 1: Check if PHPMailer is installed
if (file_exists("PHPMailer/PHPMailerAutoload.php")) {
    echo "✓ PHPMailer found<br>";
} else {
    echo "✗ PHPMailer NOT found<br>";
}

// Test 2: Test email configuration
require("PHPMailer/PHPMailerAutoload.php");
$mail = new PHPMailer();
echo "✓ PHPMailer loaded successfully<br>";
echo "SMTP class: " . (class_exists('SMTP') ? "Available" : "Not found") . "<br>";

// Test 3: Verify file paths
echo "Current directory: " . getcwd() . "<br>";
echo "PHP version: " . phpversion() . "<br>";
?>
```

**Run it:** Open `http://localhost/your-project/assets/php/test-connection.php` in browser

#### **Step 2: Enable SMTP Debugging**
In `assets/php/contact.php`, change line 23:
```php
$mail->SMTPDebug = 2;  // Set to 2 to see SMTP debug output
```

This will show you detailed SMTP communication in the response.

---

### **Option 3: Browser Developer Tools Testing**

1. **Open Contact Form Page** - Open contact-us.html
2. **Open Developer Console** - Press `F12` → Go to "Network" tab
3. **Fill & Submit Form**
4. **Check Network Request:**
   - Look for POST request to `assets/php/contact.php`
   - Click on it → "Response" tab
   - You should see the success/error message returned

---

## 📝 Current Configuration

**File:** `assets/php/contact.php`

```php
$recipientEmail='chathukarandils@gmail.com';  ← Update this with your email
$recipientName='Chathuka Siriwardana';        ← Update this with your name
```

---

## ⚠️ Common Issues & Solutions

### **Issue 1: "Please fill in all required fields"**
- **Solution:** Ensure all form fields have values before submitting
- Check that form field names match:
  - `contact-name` (Name field)
  - `contact-email` (Email field)
  - `contact-phone` (Phone field)
  - `contact-subject` (Subject dropdown)
  - `contact-message` (Message textarea)

### **Issue 2: Email not received**
- **Check 1:** Verify recipient email is correct in contact.php
- **Check 2:** Check spam/promotions folder
- **Check 3:** Verify sender email is valid and doesn't have domain restrictions
- **Check 4:** Check server error logs

### **Issue 3: No success message appears**
- **Check 1:** Open browser console (F12) for JavaScript errors
- **Check 2:** Check Network tab to see if PHP file is being called
- **Check 3:** Check if jQuery is loaded (required for AJAX)
- **Check 4:** Verify `assets/php/contact.php` path is correct

### **Issue 4: Form keeps refreshing (not submitting via AJAX)**
- **Solution:** Check if jQuery and validation plugin are loaded in HTML
- Verify `.contact-result` div exists in form (it does at line ~345)

---

## 🔧 Setup Checklist

- [ ] Update recipient email in `assets/php/contact.php`
- [ ] Update recipient name in `assets/php/contact.php`
- [ ] Test form on local server (not file://)
- [ ] Check email is received
- [ ] Verify form doesn't refresh (AJAX working)
- [ ] Check success message appears
- [ ] Test error handling (submit with invalid email)
- [ ] Deploy to production server

---

## 📧 Email Flow Summary

```
User fills form in contact-us.html
         ↓
User clicks "Submit Request"
         ↓
AJAX sends POST to assets/php/contact.php
         ↓
PHP validates inputs
         ↓
PHPMailer creates email with form data
         ↓
Email sent to: chathukarandils@gmail.com
         ↓
Success/Error message displayed to user
```

---

## 🚀 Production Deployment

Before going live:

1. **Update Email Address**
   ```php
   $recipientEmail = 'your-production-email@ronim-ambulance.com';
   ```

2. **Test on Live Server**
   - Submit a test form
   - Verify email received
   - Check form response

3. **Monitor First Week**
   - Check spam filters
   - Ensure regular email delivery
   - Monitor for bounce-backs

---

## 📞 Need Help?

If the form isn't working:
1. Enable SMTP debugging (set `$mail->SMTPDebug = 2;`)
2. Check browser console for errors (F12)
3. Check PHP error logs on server
4. Verify all email credentials are correct

---

**Last Updated:** January 20, 2026  
**Status:** ✅ Ready for Testing
