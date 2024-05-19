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
  
  // Check if the form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish connection securely
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

    // Retrieve form data and validate
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $password = $_POST["password"];

    // Validate the email format
    if (!$email) {
        exit("Invalid email format.");
    }

    // Prepare and execute the SQL query for SELECT
    $stmt = $conn->prepare("SELECT * FROM logistics_companies WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Check if a matching row is found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedPasswordHash = $row['password'];

        // Verify the entered password against the stored hashed password
        if (password_verify($password, $storedPasswordHash)) {
          
            // Store email in session variable
            $_SESSION['email'] = $email;

            // Redirect to dashboard
            header("Location: ../dashboard/pages_business/dashboard.php");
            exit();
        } else {
    		header("Location: ../businessportal.php?error=Invalid Password");
        }
    } else {
    		header("Location: ../businessportal.php?error=Email not found");
    }

    // Close the statement
    $stmt->close();

    // Close connection
    $conn->close();
  }
  
  unset($_SESSION["token"]); 
  unset($_SESSION["token-expire"]);
} 

// ERROR CANNOT VALIDATE 
else { 
  exit("Invalid Token!"); 
}
?>
