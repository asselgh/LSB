<?php
session_start();

// Establish database connection
$host = '';
$user = '';
$pass = '';
$db   = '';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the token is present in the session
if (!isset($_SESSION['admin_token']) || empty($_SESSION['admin_token'])) {
    // Redirect the user back to the login page or display an error message
    header("Location: adminlogin.php");
    exit();
}

//approval section
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  //approve logistics companies
  if (isset($_GET["key"])) {
      if ($_GET["key"] == "approve_logistics_company") {
          if (isset($_GET["commercial_number"])) {
              // Decode the commercial number from the URL parameter
              $commercial_number = base64_decode($_GET["commercial_number"]);
              
              // Assuming there is already an established database connection
              // Prepare the SQL query with bind parameter
              $stmt = $conn->prepare("UPDATE logistics_companies SET approval = 'yes' WHERE commercial_number = ?");
              $stmt->bind_param("s", $commercial_number);
              
              // Execute the statement
              if ($stmt->execute()) {
                  // If execution is successful, redirect to admin.php
                  header("Location: admin.php?success=Account has been approved");
                  exit(); // Stop further execution
              } else {
                  echo "Error executing SQL query: " . $conn->error;
              }
              
              // Close the statement
              $stmt->close();
          } else {
              echo "Commercial number not provided.";
          }
      }
  }

  //approve clients
  if (isset($_GET["key"])) {
    if ($_GET["key"] == "approve_client") {
        if (isset($_GET["commercial_number"])) {
            // Decode the commercial number from the URL parameter
            $commercial_number = base64_decode($_GET["commercial_number"]);
            
            // Assuming there is already an established database connection
            // Prepare the SQL query with bind parameter
            $stmt = $conn->prepare("UPDATE clients SET approval = 'yes' WHERE commercial_number = ?");
            $stmt->bind_param("s", $commercial_number);
            
            // Execute the statement
            if ($stmt->execute()) {
                // If execution is successful, redirect to admin.php
                header("Location: admin.php?success=Account has been approved");
                exit(); // Stop further execution
            } else {
                echo "Error executing SQL query: " . $conn->error;
            }
            
            // Close the statement
            $stmt->close();
        } else {
            echo "Commercial number not provided.";
        }
    }
  }

  //approve Requests
  if (isset($_GET["key"])) {
    if ($_GET["key"] == "approve_request") {
        if (isset($_GET["id"])) {
            // Decode the commercial number from the URL parameter
            $id = base64_decode($_GET["id"]);
            
            // Assuming there is already an established database connection
            // Prepare the SQL query with bind parameter
            $stmt = $conn->prepare("UPDATE requests SET approval = 'yes' WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            // Execute the statement
            if ($stmt->execute()) {
                // If execution is successful, redirect to admin.php
                header("Location: admin.php?success=Request has been approved");
                exit(); // Stop further execution
            } else {
                echo "Error executing SQL query: " . $conn->error;
            }
            
            // Close the statement
            $stmt->close();
        } else {
            echo "id not provided.";
        }
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  <link rel="icon" type="image/png" href="../../img/LSB2.png">
  <title>
    Admin Panel - LSB
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.7" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="dashboard.php">
        <img src="../../img/LSB1.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">LSB Administration</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link  active" href="../admin/admin.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>Admin Panel</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g transform="translate(0.000000, 148.000000)">
                        <path class="color-background opacity-6" d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"></path>
                        <path class="color-background" d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Admin Panel</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">LSB</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Admin</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Admin Panel</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group">
              <a class="btn btn-outline-primary btn-sm mb-0 me-3" href="#" hidden>Make a Request</a>
            </div>
          </div>
          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a class="btn btn-outline-primary btn-sm mb-0 me-3" style="color: #d10000 !important;border-color: #d10000;" href="../pages/logout.php">Log out</a>
            </li>

            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->

    <div class="container-fluid pt-4">
      <div class="row">
        <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-12">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Navigate to 
                      <a href=#clients class="font-weight-bolder">Clients</a></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-12">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Navigate to 
                      <a href=#logistics class="font-weight-bolder">Logistics Companies</a></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-12">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Navigate to 
                      <a href=#requests class="font-weight-bolder">Requests</a></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-12">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Navigate to 
                      <a href=#offers class="font-weight-bolder">Offers</a></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Leave shipments and sales unchanged -->
        <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
			  <div class="row">
                <div class="col-12">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Navigate to 
                      <a href=#shipments class="font-weight-bolder">Shipments</a></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-12">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Navigate to 
                      <a href=#inquiries class="font-weight-bolder">User Inquiries</a></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <style>
        .alert {
            box-sizing: border-box;
            background: #c3f4d6;
            padding: 20px 40px;
            min-width: 420px;
            position: absolute;
            right: 0;
            top: 10px;
            border-radius: 4px;
            border-left: 8px solid #27d571;
            overflow: hidden;
            opacity: 0;
            pointer-events: none;
        }
        .alert.showAlert {
            opacity: 1;
            pointer-events: auto;
        }
        .alert.show {
            animation: show_slide 1s ease forwards;
        }
        @keyframes show_slide {
            0% {
                transform: translateX(100%);
            }
            40% {
                transform: translateX(-10%);
            }
            80% {
                transform: translateX(0%);
            }
            100% {
                transform: translateX(-10px);
            }
        }
        .alert.hide {
            animation: hide_slide 1s ease forwards;
        }
        @keyframes hide_slide {
            0% {
                transform: translateX(-10px);
            }
            40% {
                transform: translateX(0%);
            }
            80% {
                transform: translateX(-10%);
            }
            100% {
                transform: translateX(100%);
            }
        }
        .alert .fa-check-circle {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #1ab058;
            font-size: 30px;
        }
        .alert .msg {
            padding: 0 20px;
            font-size: 18px;
            color: #4f9f75;
        }
        .alert .close-btn {
            position: absolute;
            right: 0px;
            top: 50%;
            transform: translateY(-50%);
            background: #95eab8;
            padding: 20px 18px;
            cursor: pointer;
        }
        .alert .close-btn:hover {
            background: #8aff66;
        }
        .alert .close-btn .fas {
            color: #29a55f;
            font-size: 22px;
            line-height: 40px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

      <?php if (isset($_GET['success'])): ?>
      <div class="alert show showAlert">
          <span class="fas fa-check-circle"></span>
          <span class="msg"><?php echo htmlspecialchars($_GET['success']); ?></span>
          <div class="close-btn">
              <span class="fas fa-times"></span>
          </div>
      </div>
      <script>
      $(document).ready(function() {
          // Hide the alert after 5 seconds
          setTimeout(function() {
              $('.alert').removeClass("show");
              $('.alert').addClass("hide");
          }, 5000);

          // Close button functionality
          $('.close-btn').click(function() {
              $('.alert').removeClass("show");
              $('.alert').addClass("hide");
          });
      });
      </script>
      <?php endif; ?>
    
    <div class="container-fluid py-4">
      <div class="row my-4">
        <div class="col-12 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
              		<h6 id="logistics">Logistics Companies</h6>
                </div>
              </div>
            </div>
            <div class="card px-0 pb-2">
              <div class="table-responsive" style="overflow-x: auto;">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Company name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Commercial number</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Services</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Approval</th>
                      <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date-time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Fetch data from the database
                    $sql = "SELECT * FROM logistics_companies WHERE approval = 'no' ORDER BY date_time DESC";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if there are any results
                    if ($result->num_rows > 0) {
                        // Loop through the results and generate HTML dynamically
                        while ($row = $result->fetch_assoc()) {
                            ?>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?php echo $row['company_name']; ?></h6>
                          </div>
                        </div>
                      </td>
                      <td class="text-sm">
                      <p class="text-xs font-weight-bold mb-0"><?php echo $row['commercial_number']; ?></p>
                      </td>
                      <td class="text-sm">
                        <span class="text-xs font-weight-bold"><?php echo $row['email']; ?></span>
                      </td>
                      <td class="text-sm">
                          <?php
                          if ($row['car_transportation'] == 'yes') {
                              echo '<p class="text-xs font-weight-bold mb-0">Cars Transportation</p>';
                          }
                          if ($row['goods_shipment'] == 'yes') {
                              echo '<p class="text-xs font-weight-bold mb-0">Freight Transportation</p>';
                          }
                          if ($row['highrisk_shipment'] == 'yes') {
                              echo '<p class="text-xs font-weight-bold mb-0">High-risk Shipments</p>';
                          }
                          ?>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <?php 
                            if ($row['approval'] == 'yes') {
                                echo '<span class="badge badge-sm bg-gradient-success">APPROVED</span>';
                            } else {
                                echo '<span class="badge badge-sm bg-gradient-secondary">NOT APPROVED</span>';
                            }
                        ?>                      </td>
                      <td class="align-middle">
						<span class="text-secondary text-xs font-weight-bold"><?php echo date('d/m/y H:i', strtotime($row['date_time'])); ?></span>
                      </td>
                      <td>
                        <a href="admin.php?commercial_number=<?php echo base64_encode($row['commercial_number']);?>&key=approve_logistics_company" class="text-secondary font-weight-bold text-xs">Approve</a>
                      </td>
                    </tr>
                    <?php
                      }
                    } else {
                        // No results found
                        echo "<td><p>No Accounts available</p></td>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6 id="clients">Clients</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive" style="overflow-x: auto;">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Firm name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Commercial number</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Approval</th>
                      <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date-time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Fetch data from the database
                    $sql = "SELECT * FROM clients WHERE approval = 'no' ORDER BY date_time DESC";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if there are any results
                    if ($result->num_rows > 0) {
                        // Loop through the results and generate HTML dynamically
                        while ($row = $result->fetch_assoc()) {
                            ?>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?php echo $row['firm_name']; ?></h6>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-sm">
                      <p class="text-xs font-weight-bold mb-0"><?php echo $row['commercial_number']; ?></p>
                      </td>
                      <td class="align-middle text-sm">
                        <span class="text-xs font-weight-bold"><?php echo $row['email']; ?></span>
                      </td>
                      <td class="align-middle text-sm">
                        <?php 
                            if ($row['approval'] == 'yes') {
                                echo '<span class="badge badge-sm bg-gradient-success">APPROVED</span>';
                            } else {
                                echo '<span class="badge badge-sm bg-gradient-secondary">NOT APPROVED</span>';
                            }
                        ?>                      </td>
                      <td class="align-middle">
						<span class="text-secondary text-xs font-weight-bold"><?php echo date('d/m/y H:i', strtotime($row['date_time'])); ?></span>
                      </td>
                      <td>
                        <a href="admin.php?commercial_number=<?php echo base64_encode($row['commercial_number']);?>&key=approve_client" class="text-secondary font-weight-bold text-xs">Approve</a>
                      </td>
                    </tr>
                    <?php
                      }
                    } else {
                        // No results found
                        echo "<td><p>No Accounts available</p></td>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6 id="requests">Requests</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive" style="overflow-x: auto;">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Service</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">details</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Firm name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Destinations</th>
                      <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Approval</th>
                      <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date-time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Fetch data from the database
                    $sql = "SELECT r.*, r.approval AS requestapproval, c.* 
                    FROM requests r 
                    INNER JOIN clients c ON r.commercial_number = c.commercial_number 
                    WHERE r.approval = 'no' 
                    ORDER BY r.date_time DESC;
                    ";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if there are any results
                    if ($result->num_rows > 0) {
                        // Loop through the results and generate HTML dynamically
                        while ($row = $result->fetch_assoc()) {
                            ?>
                    <tr>
                    <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <?php 
                            if ($row['service_type'] == 'Car Transportation') {
                              echo '<img src="../../img/carrier.png" height="45" width="45" style="margin-right: 0.25rem;" alt="main_logo">';
                            } if ($row['service_type'] == 'High-risk Shipment') {
                              echo '<svg class="mr-1" xmlns="http://www.w3.org/2000/svg" height="23" width="50" viewBox="0 0 18 18"><title>triangle warning</title><g fill="#212121" class="nc-icon-wrapper"><path d="M16.437,12.516L11.012,3.12c-.42-.727-1.172-1.161-2.012-1.161s-1.592,.434-2.012,1.161L1.563,12.516c-.42,.727-.42,1.595,0,2.322,.42,.728,1.172,1.162,2.012,1.162H14.425c.84,0,1.592-.434,2.012-1.162,.42-.727,.42-1.595,0-2.322ZM8.25,6.5c0-.414,.336-.75,.75-.75s.75,.336,.75,.75v3.5c0,.414-.336,.75-.75,.75s-.75-.336-.75-.75v-3.5Zm.75,7.069c-.552,0-1-.449-1-1s.448-1,1-1,1,.449,1,1-.448,1-1,1Z" fill="#212121"></path></g></svg>';
                            } if ($row['service_type'] == 'Freight Transportation') {
                              echo '<img src="../../img/boxes.png" height="30" width="30" style="margin-right: 1rem;" alt="main_logo">';
                            }
                            ?>
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?php echo $row['service_type']; ?></h6>
                          </div>
                        </div>
                      </td>                      
                      <td class="align-middle text-sm">
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <a href="javascript:;" class="text-xs text-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="<?php echo $row['details']; ?>" data-bs-original-title="<?php echo $row['details']; ?>" style="cursor: default;">
                              <?php echo $row['details']; ?>
                            </a>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-sm">
                      <p class="text-xs font-weight-bold mb-0"><?php echo $row['firm_name']; ?></p>
                      </td>
                      <td class="text-sm">
                      <?php
                                      // Your JSON string
                                      $jsonString = $row['destination'];

                                      // Decode the JSON string
                                      $destinations = json_decode($jsonString, true);

                                      // Check if decoding was successful
                                      if ($destinations !== null) {
                                          // Iterate through each destination and display them
                                          for ($i = 0; $i < count($destinations); $i++) {
                                              $destinationNumber = $i + 1;
                                              $pickupCity = $destinations[$i]['pickup_city'];
                                              $deliveryCity = $destinations[$i]['delivery_city'];
                                              echo "<p class='text-xs font-weight-bold mb-0'><span class='text-xs text-secondary'>Destination $destinationNumber: </span><span class='text-xs text-secondary'>From </span>$pickupCity <span class='text-xs text-secondary'>to </span>$deliveryCity</p>";
                                          }
                                      } else {
                                          echo "Failed to decode JSON.";
                                      }
                                      ?>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <?php 
                            if ($row['requestapproval'] == 'yes') {
                                echo '<span class="badge badge-sm bg-gradient-success">APPROVED</span>';
                            } else {
                                echo '<span class="badge badge-sm bg-gradient-secondary">NOT APPROVED</span>';
                            }
                        ?>                      </td>
                      <td class="align-middle">
						            <span class="text-secondary text-xs font-weight-bold"><?php echo date('d/m/y H:i', strtotime($row['date_time'])); ?></span>
                      </td>
                      <td>
                        <a href="admin.php?id=<?php echo base64_encode($row['id']);?>&key=approve_request" class="text-secondary font-weight-bold text-xs">Approve</a>
                      </td>
                    </tr>
                    <?php
                      }
                    } else {
                        // No results found
                        echo "<td><p>No Requests available</p></td>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6 id="offers">Offers <small class="text-xs text-secondary">(Last 14 days)</small></h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive" style="overflow-x: auto;">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">From</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">To</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client acceptance</th>
                      <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date-time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Fetch data from the database
                    $sql = "SELECT o.*, lc.company_name, r.firm_name, r.service_type, r.destination
                    FROM offers o 
                    JOIN logistics_companies lc ON o.logistics_company_crn = lc.commercial_number 
                    JOIN requests r ON o.request_id = r.id
                    WHERE o.date_time BETWEEN DATE_SUB(CURRENT_DATE, INTERVAL 14 DAY) AND CURRENT_DATE
                    ORDER BY o.date_time DESC;                    
                    ";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if there are any results
                    if ($result->num_rows > 0) {
                        // Loop through the results and generate HTML dynamically
                        while ($row = $result->fetch_assoc()) {
                            ?>
                    <tr>
                      <td>
                      <h6 class="mb-0 text-sm"><?php echo $row['company_name']; ?></h6>
                      </td>                      
                      <td class="align-middle text-sm">
                      <h6 class="mb-0 text-sm"><?php echo $row['firm_name']; ?></h6>
                      </td>
                      <td class="text-sm">
                        <p class="text-xs font-weight-bold mb-0"><?php echo $row['service_type']; ?></p>
                        <?php
                        // Your JSON string
                        $jsonString = $row['destination'];

                        // Decode the JSON string
                        $destinations = json_decode($jsonString, true);

                        // Check if decoding was successful
                        if ($destinations !== null) {
                            // Iterate through each destination and display them
                            for ($i = 0; $i < count($destinations); $i++) {
                                $destinationNumber = $i + 1;
                                $pickupCity = $destinations[$i]['pickup_city'];
                                $deliveryCity = $destinations[$i]['delivery_city'];
                                echo "<p class='text-xs text-secondary' style='margin-bottom: 0px !important;'>$pickupCity to $deliveryCity</p>";
                            }
                        } 
                        ?>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold"><?php echo number_format($row["price"], 2); ?> SAR</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <?php 
                            if ($row['status'] == 'accept') {
                                echo '<span class="badge badge-sm bg-gradient-success">ACCEPTED</span>';
                            } elseif ($row['status'] == 'DECLINE') {
                                echo '<span class="badge badge-sm bg-gradient-secondary">DECLINED</span>';
                            } else {
                              echo '<span class="badge badge-sm bg-gradient-secondary">PENDING</span>';
                          }
                        ?>                      
                      </td>
                      <td class="align-middle">
						            <span class="text-secondary text-xs font-weight-bold"><?php echo date('d/m/y H:i', strtotime($row['date_time'])); ?></span>
                      </td>
                    </tr>
                    <?php
                      }
                    } else {
                        // No results found
                        echo "<td><p>No Offers available</p></td>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6 id="shipments">Shipments <small class="text-xs text-secondary">(Last 14 days)</small></h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive" style="overflow-x: auto;">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Logistic company</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</th>
                      <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date-time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Fetch data from the database
                    $sql = "SELECT s.*, r.firm_name, r.service_type, r.destination, lc.company_name, o.price
                     FROM shipments s 
                     JOIN requests r 
                     ON s.request_id = r.id 
                     JOIN offers o 
                     ON s.offer_id = o.id 
                     JOIN logistics_companies lc 
                     ON o.logistics_company_crn = lc.commercial_number 
                     WHERE s.date_time 
                     BETWEEN DATE_SUB(CURRENT_DATE, INTERVAL 14 DAY) 
                     AND CURRENT_DATE 
                     ORDER BY s.date_time 
                     DESC;";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if there are any results
                    if ($result->num_rows > 0) {
                        // Loop through the results and generate HTML dynamically
                        while ($row = $result->fetch_assoc()) {
                            ?>
                    <tr>
                      <td>
                      <h6 class="mb-0 text-sm"><?php echo $row['firm_name']; ?></h6>
                      </td>                      
                      <td class="align-middle text-sm">
                      <h6 class="mb-0 text-sm"><?php echo $row['company_name']; ?></h6>
                      </td>
                      <td class="text-sm">
                        <p class="text-xs font-weight-bold mb-0"><?php echo $row['service_type']; ?></p>
                        <?php
                        // Your JSON string
                        $jsonString = $row['destination'];

                        // Decode the JSON string
                        $destinations = json_decode($jsonString, true);

                        // Check if decoding was successful
                        if ($destinations !== null) {
                            // Iterate through each destination and display them
                            for ($i = 0; $i < count($destinations); $i++) {
                                $destinationNumber = $i + 1;
                                $pickupCity = $destinations[$i]['pickup_city'];
                                $deliveryCity = $destinations[$i]['delivery_city'];
                                echo "<p class='text-xs text-secondary' style='margin-bottom: 0px !important;'>$pickupCity to $deliveryCity</p>";
                            }
                        } 
                        ?>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold"><?php echo number_format($row["price"], 2); ?> SAR</span>
                      </td>
                      <td class="align-middle">
						            <span class="text-secondary text-xs font-weight-bold"><?php echo date('d/m/y H:i', strtotime($row['date_time'])); ?></span>
                      </td>
                    </tr>
                    <?php
                      }
                    } else {
                        // No results found
                        echo "<td><p>No Shipments available</p></td>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6 id="inquiries">Clients Inquiries</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive" style="overflow-x: auto;">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Message</th>
                      <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date-time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Fetch data from the database
                    $sql = "SELECT * FROM contact_us WHERE type = 'client';";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if there are any results
                    if ($result->num_rows > 0) {
                        // Loop through the results and generate HTML dynamically
                        while ($row = $result->fetch_assoc()) {
                            ?>
                    <tr>
                      <td style="width: 35% !important;">
                      <h6 class="mb-0 text-sm"><?php echo $row['email']; ?></h6>
                      </td>                      
                      <td class="text-sm" style="width: 50% !important;">
                      <p class="text-xs font-weight-bold mb-0"><?php echo $row['message']; ?></p>
                      </td>
                      <td class="align-middle">
						            <span class="text-secondary text-xs font-weight-bold"><?php echo date('d/m/y H:i', strtotime($row['date_time'])); ?></span>
                      </td>
                    </tr>
                    <?php
                      }
                    } else {
                        // No results found
                        echo "<td><p>No Inquiries available</p></td>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Logistics Companies Inquiries</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive" style="overflow-x: auto;">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Message</th>
                      <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date-time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Fetch data from the database
                    $sql = "SELECT * FROM contact_us WHERE type = 'logistics company';";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if there are any results
                    if ($result->num_rows > 0) {
                        // Loop through the results and generate HTML dynamically
                        while ($row = $result->fetch_assoc()) {
                            ?>
                    <tr>
                      <td style="width: 35% !important;">
                      <h6 class="mb-0 text-sm"><?php echo $row['email']; ?></h6>
                      </td>                      
                      <td class="text-sm" style="width: 50% !important;">
                      <p class="text-xs font-weight-bold mb-0"><?php echo $row['message']; ?></p>
                      </td>
                      <td class="align-middle">
						            <span class="text-secondary text-xs font-weight-bold"><?php echo date('d/m/y H:i', strtotime($row['date_time'])); ?></span>
                      </td>
                    </tr>
                    <?php
                      }
                    } else {
                        // No results found
                        echo "<td><p>No Inquiries available</p></td>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
<?php
// Close the database connection
$stmt->close();
$conn->close();
?>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
</body>

</html>