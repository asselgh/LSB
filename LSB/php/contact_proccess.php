<?php

// Validation function to sanitize and validate input data with maximum length check
function validateInput($data, $max_length) {
    // Trim whitespace
    $data = trim($data);
    // Check if the input is empty
    if (empty($data)) {
        return false;
        exit();
    }
    // Check maximum length
    if (strlen($data) > $max_length) {
        return false;
        exit();
    }
    // Check if input contains only printable characters (excluding forward slash and backslash)
    if (preg_match('/[\/\\\\]/', $data)) {
        return false;
        exit();
    }
    // Escape special characters for XSS protection
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    // Sanitize and validate input data
    $client_email = isset($_POST["client_email"]) ? validateInput($_POST["client_email"], 255) : null;
    $logistics_email = isset($_POST["logistics_email"]) ? validateInput($_POST["logistics_email"], 255) : null;
    $message = validateInput($_POST["message"], 1000);

    // Establish database connection securely
    $host = '';
    $user = '';
    $pass = '';
    $db   = '';

    // Use prepared statements to prevent SQL injection
    $conn = new mysqli($host, $user, $pass, $db);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

  if ($client_email) {
    $stmt_client = $conn->prepare("SELECT COUNT(*) AS count FROM contact_us WHERE email = ? AND DATE(date_time) = CURDATE()");
    $stmt_client->bind_param("s", $client_email);
    $stmt_client->execute();
    $result_client = $stmt_client->get_result();
    $row_client = $result_client->fetch_assoc();
    $count_client = $row_client['count'];
    $stmt_client->close();
  }
  
  if ($logistics_email) {
    $stmt_logistics = $conn->prepare("SELECT COUNT(*) AS count FROM contact_us WHERE email = ? AND DATE(date_time) = CURDATE()");
    $stmt_logistics->bind_param("s", $logistics_email);
    $stmt_logistics->execute();
    $result_logistics = $stmt_logistics->get_result();
    $row_logistics = $result_logistics->fetch_assoc();
    $count_logistics = $row_logistics['count'];
    $stmt_logistics->close();
  }
  
    // Submission limit reached for the email address on the current day
    if ($count_client > 0) {
        header("Location: ../dashboard/pages/contact.php?error=Your message for today has already been received. Our team will be in touch with you shortly.");
    	exit();
    } elseif ($count_logistics > 0) {
        header("Location: ../dashboard/pages_business/contact.php?error=Your message for today has already been received. Our team will be in touch with you shortly.");
    	exit();
    }

    // Insert the message into the database
    if ($client_email) {
        // Prepare the SQL statement for client email
        $type = 'client';
    } elseif ($logistics_email) {
        // Prepare the SQL statement for logistics email
        $type = 'logistics company';
    }
  
  	// Assign the appropriate email to a variable based on the condition
	$email_to_bind = $client_email ? $client_email : $logistics_email;
    $stmt = $conn->prepare("INSERT INTO contact_us (email, type, message) VALUES (?, ?, ?)");
	$stmt->bind_param("sss", $email_to_bind, $type, $message);
  
    // Execute the statement
    if ($stmt->execute()) {
        if ($client_email) {
            header("Location: ../dashboard/pages/contact.php?success=Message submitted successfully!");
        } elseif ($logistics_email) {
            header("Location: ../dashboard/pages_business/contact.php?success=Message submitted successfully!");
        }
    } else {
        if ($client_email) {
            header("Location: ../dashboard/pages/contact.php?error=Unable to submit your message!");
        } elseif ($logistics_email) {
            header("Location: ../dashboard/pages_business/contact.php?error=Unable to submit your message!");
        }
        // Log the error for further investigation
        error_log("Error in form submission: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();

    // Close connection
    $conn->close();

} else {
    // Handle non-POST requests
    echo 'invalid request';
    exit();
}

?>