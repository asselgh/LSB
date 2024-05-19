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

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect the user to the login page if not logged in
    exit("Connection failed");
}

$email = $_SESSION['email'];

// Retrieve firm name and commercial number using email
$companyName = ''; // Placeholder for the retrieved firm name from the database
$commercialNumber = ''; // Placeholder for the retrieved commercial number from the database

// Prepare and execute SQL query to fetch firm name and commercial number based on email
$stmt = $conn->prepare("SELECT * FROM logistics_companies WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $company_row = $result->fetch_assoc();
    $companyName = $company_row['company_name'];
    $commercialNumber = $company_row['commercial_number'];

    // Initialize an empty array to store the values
    $services = array();

    // Map column names to their corresponding service names
    $serviceMappings = array(
        'car_transportation' => 'Car Transportation',
        'goods_shipment' => 'Freight Transportation',
        'highrisk_shipment' => 'High-risk Shipment'
    );

    // Iterate over the mappings and check if the value is 'yes'
    foreach ($serviceMappings as $column => $serviceName) {
        // Check if the value is 'yes' and add the service name to the array
        if ($company_row[$column] == 'yes') {
            $services[] = $serviceName;
        }
    }
}

// Close the statement
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Contact us - LSB
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
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg blur blur-rounded top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
          <div class="container-fluid pe-0">
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="#">
              LSB Help center
            </a>
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
            </button>
            <div class="collapse navbar-collapse" id="navigation">
              <ul class="navbar-nav mx-auto ms-xl-auto me-xl-7">
                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="dashboard.php">
                    <i class="fa fa-chart-pie opacity-6 text-dark me-1"></i>
                    Dashboard
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2" href="../pages/logout.php">
                    <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                    Log Out
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-75">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
              <div class="card card-plain mt-8">
                <div class="card-header pb-0 text-left bg-transparent">
                  <h3 class="font-weight-bolder text-info text-gradient" style="background-image: linear-gradient(310deg, #5a21ff, #6721fd) !important;">Contact Us</h3>
                  <p class="mb-0">Please provide your email address, and a message detailing your inquiry or feedback in the fields provided below</p>
                </div>
                <div class="card-body">
				  <form role="form" action="../../php/contact_proccess.php" method="POST">
                    <label>Email</label>
                    <div class="mb-3">
                      <input type="email" id="logistics_email" name="logistics_email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon" value="<?php echo $email; ?>" readonly>
                    </div>
                    <label>Message</label>
                    <div class="mb-3">
                      <textarea id="message" name="message" rows="7" cols="50" maxlength="1000" class="form-control" placeholder="Message" aria-label="Message" aria-describedby="message-addon"></textarea>
                    </div> 
                    <div class="text-center">
                      <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0" style="background-image: linear-gradient(310deg, #391293 0%, #ac21fd 100%) !important;">
                        Submit</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
					<?php if (isset($_GET['success'])) {
                            echo '<p class="mb-4 text-sm mx-auto" style="color: green;">' . htmlspecialchars($_GET['success']) . '</p>';
                        } elseif (isset($_GET['error'])) {
                            echo '<p class="mb-4 text-sm mx-auto" style="color: red;">' . htmlspecialchars($_GET['error']) . '</p>';
                        } else {
                            echo '<p class="mb-4 text-sm mx-auto"></p>';
                        } ?>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('../assets/img/curved-images/curved6.jpg')"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
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