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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = validateInput($_POST['email'], 255);
    $password = validateInput($_POST['password'], 1000);

    // Database connection settings
    $servername = "";
    $username = "";
    $db_password = "";
    $dbname = "";

    // Create connection
    $conn = new mysqli($servername, $username, $db_password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the email exists
    if ($result->num_rows > 0) {
        // Fetch associative array
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {

                // Password is correct, generate a token
                $token = bin2hex(random_bytes(32)); // Generate a random token
                
                // Store the token in the session
                session_start();
                $_SESSION['admin_token'] = $token;
                
                // Redirect to admin.php with the token
                header("Location: admin.php?token=" . urlencode($token));

            exit();
        } else {
            // Password is incorrect
            header("Location: adminlogin.php?error=Invalid password");
        }
    } else {
        // Email does not exist
        header("Location: adminlogin.php?error=Email not found");
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #7b2cbf, #9d4edd, #c77dff);
            color: white;
        }
        .login-container {
            background: rgba(0, 0, 0, 0.5);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 1rem;
            font-size: 2rem;
        }
        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 80%;
            padding: 0.8rem;
            margin: 0.5rem 0;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            color: #333;
        }
        .login-container input[type="submit"] {
            width: 85%;
            padding: 0.8rem;
            margin: 1rem 0;
            border: none;
            border-radius: 5px;
            background-color: #9d4edd;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .login-container input[type="submit"]:hover {
            background-color: #7b2cbf;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Panel</h2>
        <form method="POST" action="adminlogin.php">
            <input type="email" name="email" id="email" placeholder="Email" required><br>
            <input type="password" name="password" id="password" placeholder="Password" required><br>
            <input type="submit" value="Log in">
        </form>
        <p style="color: red;"><?php echo $_GET['error']?></p>
    </div>
</body>
</html>
