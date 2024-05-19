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

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $offer_id_encoded = $_GET["id"];
  $request_id_encoded = $_GET["request_id"];

  // Decrypt the values using base64_decode
  $offer_id = base64_decode($offer_id_encoded);
  $request_id = base64_decode($request_id_encoded);

  // Now you can use the decrypted values:
  $offer_id = validateInput($offer_id, 255);
  $request_id = validateInput($request_id, 255);
  $key = validateInput($_GET["key"], 9);
  

    if ($key == "accept" || $key == "decline") {
        $host = '';
        $user = '';
        $pass = '';
        $db   = '';

        $conn = new mysqli($host, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("UPDATE offers SET status = ? WHERE id = ?");
        $stmt->bind_param("sd", $key, $offer_id);

        if ($stmt->execute()) {
          if ($key == "accept") {
              // Prepare the INSERT statement
              $stmt = $conn->prepare("INSERT INTO shipments (request_id, offer_id) VALUES (?, ?)");
              if (!$stmt) {
                  // Log error if preparation fails
                  error_log("Error preparing INSERT query: " . $conn->error);
              } else {
                  $stmt->bind_param("ii", $request_id, $offer_id); // Changed "dd" to "ii" for integers
                  if (!$stmt->execute()) {
                      // Log error if execution fails
                      error_log("Error in INSERT query: " . $stmt->error);
                  }
                  $stmt->close();
              }

              // Prepare the UPDATE statement
              $updateStmt = $conn->prepare("UPDATE requests SET status = 'inactive' WHERE id = ?");
              if (!$updateStmt) {
                  // Log error if preparation fails
                  error_log("Error preparing UPDATE query: " . $conn->error);
              } else {
                  $updateStmt->bind_param("i", $request_id); // Bind the request_id as an integer
                  if (!$updateStmt->execute()) {
                      // Log error if execution fails
                      error_log("Error in UPDATE query: " . $updateStmt->error);
                  }
                  $updateStmt->close();
              }
          }
            header("Location: ../dashboard/pages/dashboard.php?success=Operation successful!");
        } else {
            // Log error
            error_log("Error in UPDATE query: " . $stmt->error);
            header("Location: ../dashboard/pages/dashboard.php?error=Operation failed!");
        }

        $stmt->close();
        $conn->close();
    } else {
        header("Location: ../dashboard/pages/dashboard.php?error=Invalid key!");
        exit();
    }
} else {
    header("Location: ../dashboard/pages/dashboard.php?error=Invalid request method!");
    exit();
}

?>