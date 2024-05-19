<?php
// START SESSION 
session_start(); 

// CHECK TOKEN MUST BE SET
if (!isset($_POST["token"]) || !isset($_SESSION["token"])) {
  exit("Token not set!");
}
// CHECK VALIDATE TOKEN 
if ($_POST["token"] == $_SESSION["token"]) {
  
  // check expiry
  if (time() >= $_SESSION["token-expire"]) {
    exit("Token expired. Reload the page");
  }
// PROCEED FORM SUBMISSION
 // Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validation function to sanitize and validate input data with maximum length check
    function validateInput($data, $max_length) {
        // Trim whitespace
        $data = trim($data);
        // Check if the input is empty
        if (empty($data)) {
    	header("Location: ../clientportal.php?error=Failed to sign up");
            exit();
        }
        // Check maximum length
        if (strlen($data) > $max_length) {
    	header("Location: ../clientportal.php?error=input exceeded maximum length");
            exit();
        }
        // Check if input contains only text (no files or other non-text content)
        if (!preg_match('/[A-Za-z0-9@#$%^&+=]/', $data)) {
    	header("Location: ../clientportal.php?error=Failed to sign up");
            exit();
        }
        return $data;
    }

    // Honeypot: Check if the honeypot field is empty (indicating it was not filled by a bot)
    if (!empty($_POST["honeypot"])) {
        // Honeypot field is not empty, log the attempt and display an error
        error_log("Honeypot field filled - potential spam attempt.");
        echo "Invalid request.";
        exit();
    }

    // Check if all required fields are present
    if (isset($_POST["firmname"]) && isset($_POST["commercialnumber"]) && isset($_POST["email"]) && isset($_POST["password"])) {

        // Retrieve and validate form data with maximum length check
        $firmName = validateInput($_POST["firmname"], 100); // Adjust the maximum length as needed
        $commercialNumber = validateInput($_POST["commercialnumber"], 20); // Adjust the maximum length as needed
        $email = validateInput($_POST["email"], 255); // Adjust the maximum length as needed
        $password = validateInput($_POST["password"], 255); // Adjust the maximum length as needed

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    	header("Location: ../clientportal.php?error=Invalid email format");
            exit();
        }

        // Validate password complexity
        if (strlen($password) < 8 || !preg_match("/[A-Za-z0-9@#$%^&+=]/", $password)) {
    	header("Location: ../clientportal.php?error=Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.");
            exit();
        }

        // Hash the password using bcrypt
        $password = password_hash($password, PASSWORD_BCRYPT);

        // Database connection details
        $host = '';
        $user = '';
        $pass = '';
        $db   = '';

        // Establish connection
        $conn = new mysqli($host, $user, $pass, $db);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute the SQL query with parameterized query
        $stmt = $conn->prepare("INSERT INTO clients (firm_name, commercial_number, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firmName, $commercialNumber, $email, $password);

        if ($stmt->execute()) {
    	header("Location: ../clientportal.php?success=Account was created succesfuly, please wait for the administration to approve your account");
        } else {
            // Log the detailed error message and display a generic error
            error_log("Error: " . $stmt->error);
    	header("Location: ../clientportal.php?error=An error occurred while processing your request.");
        }

        // Close the statement
        $stmt->close();

        // Close connection
        $conn->close();
    } else {
    	header("Location: ../clientportal.php?error=All fields are required.");
    }
}
  
  unset($_SESSION["token"]); 
  unset($_SESSION["token-expire"]);
} 

// ERROR CANNOT VALIDATE 
else { 
  exit("Invalid Token!"); 
}
?>