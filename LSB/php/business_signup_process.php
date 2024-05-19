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
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        // Check maximum length
        if (strlen($data) > $max_length) {
    	header("Location: ../businessportal.php?error=Failed to sign up");
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
    if (isset($_POST["companyname"]) && isset($_POST["commercialnumber"]) && isset($_POST["email"]) && isset($_POST["password"])) {

        // Retrieve and validate form data with maximum length check
        $companyName = validateInput($_POST["companyname"], 100); // Adjust the maximum length as needed
        $commercialNumber = validateInput($_POST["commercialnumber"], 20); // Adjust the maximum length as needed
        $email = validateInput($_POST["email"], 255); // Adjust the maximum length as needed
        $password = validateInput($_POST["password"], 255); // Adjust the maximum length as needed
        $cartransportation = validateInput($_POST["cartransportation"], 18); // Adjust the maximum length as needed
        $goodsshipment = validateInput($_POST["goodsshipment"], 14); // Adjust the maximum length as needed
        $highriskshipment = validateInput($_POST["highriskshipment"], 17); // Adjust the maximum length as needed

        if (empty($_POST["cartransportation"]) && empty($_POST["goodsshipment"]) && empty($_POST["highriskshipment"])) {
    	header("Location: ../businessportal.php?error=No service was chosen");
            exit(); 
        } 
      	if (isset($_POST["cartransportation"])) {
            $cartransportation = "yes";
        }
      	if (isset($_POST["highriskshipment"])) {
            $highriskshipment = "yes";
        }
      	if (isset($_POST["goodsshipment"])) {
            $goodsshipment = "yes";
        } 

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    	header("Location: ../businessportal.php?error=Invalid email format");
            exit();
        }

        // Validate password complexity
        if (strlen($password) < 8 || !preg_match("/[A-Za-z0-9@#$%^&+=]/", $password)) {
    	header("Location: ../businessportal.php?error=Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.");
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
        $stmt = $conn->prepare("INSERT INTO logistics_companies (company_name, commercial_number, email, password, car_transportation, goods_shipment, highrisk_shipment) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $companyName, $commercialNumber, $email, $password, $cartransportation, $goodsshipment, $highriskshipment);

        if ($stmt->execute()) {
    	header("Location: ../businessportal.php?success=Account was created succesfuly, please wait for the administration to approve your account");
        } else {
            // Log the detailed error message and display a generic error
            error_log("Error: " . $stmt->error);
    	header("Location: ../businessportal.php?error=An error occurred while processing your request.");
        }

        // Close the statement
        $stmt->close();

        // Close connection
        $conn->close();
    } else {
    	header("Location: ../businessportal.php?error=All fields are required.");
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