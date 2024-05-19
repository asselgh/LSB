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
    // Validate form data
    $price = validateInput($_POST["price"], 9);  // Assuming max length for price is 9
    $commercialNumber = validateInput($_POST["commercialNumber"], 255);
    $request_id = validateInput($_POST["request_id"], 255);

    // Check numeric inputs for valid numbers
    if (!is_numeric($price)) {
            header("Location: ../dashboard/pages_business/makeoffer.php?error=Price must be a number!");
        exit();
    }

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

    // Prepare and execute the SQL query for INSERT
    $stmt = $conn->prepare("INSERT INTO offers (price, logistics_company_crn, request_id, date_time) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
    $stmt->bind_param("dss", $price, $commercialNumber, $request_id);

    // Execute the statement
    if ($stmt->execute()) {
            header("Location: ../dashboard/pages_business/makeoffer.php?success=offer submitted successfully!");
    } else {
         header("Location: ../dashboard/pages_business/makeoffer.php?error=Unable to make offer!");
        // Log the error for further investigation
        error_log("Error in form submission: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();

    // Close connection
    $conn->close();

} else {
    // Handle non-POST requests
    header("Location: ../dashboard/pages_business/makeoffer.php");
    exit();
}

?>
