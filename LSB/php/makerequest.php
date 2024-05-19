<?php
// Check if the form is submitted securely via POST over HTTPS
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $serviceType = validateInput($_POST["service_type"], 255);
    $details = validateInput($_POST["details"], 1000);
    $commercialNumber = validateInput($_POST["commercial_number"], 50);
    $firmName = validateInput($_POST["firm_name"], 100);
    $email = validateInput($_POST["email"], 100);

    // Extract and validate destinations
    $pickupCities = validateArray($_POST["pickup_cities"], 500);
    $deliveryCities = validateArray($_POST["delivery_cities"], 500);
    $numDestinations = min(count($pickupCities), count($deliveryCities));
    $destinations = [];
    for ($i = 0; $i < $numDestinations; $i++) {
        $destinations[] = array(
            "pickup_city" => validateInput($pickupCities[$i], 255),
            "delivery_city" => validateInput($deliveryCities[$i], 255)
        );
    }
    $destinationsJSON = json_encode($destinations);

    // Check if all required fields are provided and valid
    if (!empty($serviceType) && !empty($details) && !empty($commercialNumber) && !empty($firmName) && !empty($email) && $numDestinations > 0) {
        // Establish a secure database connection
        $host = '';
        $user = '';
        $pass = '';
        $db   = '';

        $conn = new mysqli($host, $user, $pass, $db);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if a request has been made by the same user within the last week
        $user_email = $conn->real_escape_string($email);
        $last_week = date('Y-m-d H:i:s', strtotime('-1 week'));
        $check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM requests WHERE email = ? AND date_time > ?");
        $check_stmt->bind_param("ss", $user_email, $last_week);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $check_stmt->close();

        if ($count > 0) {
            header("Location: ../dashboard/pages/makerequest.php?error=You have already submitted a request within the last week");
            $conn->close();
            exit();
        }

        // Prepare and execute the SQL query for INSERT
        $stmt = $conn->prepare("INSERT INTO requests (service_type, details, destination, commercial_number, firm_name, email) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $serviceType, $details, $destinationsJSON, $commercialNumber, $firmName, $email);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: ../dashboard/pages/makerequest.php?success=Request submitted successfully!");
        } else {
            header("Location: ../dashboard/pages/makerequest.php?error=Unable to submit request. Please try again later");
            // Log the error for further investigation
            error_log("Error in form submission: " . $stmt->error);
        }

        // Close the statement
        $stmt->close();

        // Close connection
        $conn->close();
    } else {
        // Handle missing required fields or no destinations provided
        header("Location: ../dashboard/pages/makerequest.php?error=All fields are required, and at least one destination must be provided");
    }
} else {
    // Redirect to index.html if the form is not submitted securely via POST over HTTPS
    header("Location: ../dashboard/pages/makerequest.php");
    exit();
}

// Validation function to sanitize and validate input data with maximum length check
function validateInput($data, $max_length) {
    // Trim whitespace
    $data = trim($data);
    // Check if the input is empty
    if (empty($data)) {
        return false;
    }
    // Check maximum length
    if (strlen($data) > $max_length) {
        return false;
    }
    // Check if input contains only printable characters
    if (!ctype_print($data)) {
        return false;
    }
    return $data;
}

// Validation function to sanitize and validate array input
function validateArray($array, $max_length) {
    // Check if the input is an array
    if (!is_array($array)) {
        return false;
    }
    // Validate each element in the array
    $validatedArray = [];
    foreach ($array as $element) {
        $validatedElement = validateInput($element, $max_length);
        if ($validatedElement !== false) {
            $validatedArray[] = $validatedElement;
        }
    }
    return $validatedArray;
}
?>
