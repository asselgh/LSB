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
    Dashboard - LSB
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

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="# ">
        <img src="../../img/LSB1.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">LSB Dashboard</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link  active" href="dashboard.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>shop </title>
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
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="../pages_business/requests.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>office</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-1869.000000, -293.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g id="office" transform="translate(153.000000, 2.000000)">
                        <path class="color-background opacity-6" d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z"></path>
                        <path class="color-background" d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Requests</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="offers.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>credit-card</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g transform="translate(453.000000, 454.000000)">
                        <path class="color-background opacity-6" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"></path>
                        <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Offers</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="shipments.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="12px" height="12px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <title>box-3d-50</title>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                    <g transform="translate(1716.000000, 291.000000)">
                      <g transform="translate(603.000000, 0.000000)">
                        <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z"></path>
                        <path class="color-background opacity-6" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z"></path>
                        <path class="color-background opacity-6" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z"></path>
                      </g>
                    </g>
                  </g>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Shipments</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer mx-3 mt-3">
      <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
        <div class="full-background" style="background-image: url('../assets/img/curved-images/white-curved.jpg')"></div>
        <div class="card-body text-start p-3 w-100">
          <div class="icon icon-shape icon-sm bg-white shadow text-center mb-3 d-flex align-items-center justify-content-center border-radius-md">
            <i class="ni ni-diamond text-dark text-gradient text-lg top-0" aria-hidden="true" id="sidenavCardIcon"></i>
          </div>
          <div class="docs-info">
            <h6 class="text-white up mb-0">Need help?</h6>
            <p class="text-xs font-weight-bold">Please click the button below to contact us through e-mail</p>
            <a href="../pages_business/contact.php" class="btn btn-white btn-sm w-100 mb-0">CONTACT US</a>
          </div>
        </div>
      </div>
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">LSB Business</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Dashboard</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group">
              <a class="btn btn-outline-primary btn-sm mb-0 me-3" href="../pages/makerequest.php" hidden>Make a Request</a>
            </div>
          </div>
          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a class="btn btn-outline-primary btn-sm mb-0 me-3" style="color: #d10000 !important;border-color: #d10000;" href="../pages/logout.php">Log out</a>
            </li>
            <li class="nav-item d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body font-weight-bold px-0">
                <i class="fa fa-user me-sm-1"></i>
                <span class="d-sm-inline d-none">Hi, <?php echo $companyName; ?></span>
              </a>
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
    <?php
    // Execute SQL queries to get counts for requests and offers
    // Assuming $conn is the established database connection

    // Query to get the count of requests for the last month
    $requestQuery = "SELECT COUNT(*) AS num_requests FROM requests WHERE YEAR(date_time) = YEAR(CURRENT_DATE) AND MONTH(date_time) = MONTH(CURRENT_DATE);";
    $requestResult = mysqli_query($conn, $requestQuery);
    $requestRow = mysqli_fetch_assoc($requestResult);
    $numRequests = $requestRow['num_requests'];

    // Query to get the count of offers for the last month
    $offerQuery = "SELECT COUNT(*) AS num_offers FROM offers o WHERE o.logistics_company_crn = $commercialNumber AND YEAR(o.date_time) = YEAR(CURRENT_DATE) AND MONTH(o.date_time) = MONTH(CURRENT_DATE)";
    $offerResult = mysqli_query($conn, $offerQuery);
    $offerRow = mysqli_fetch_assoc($offerResult);
    $numOffers = $offerRow['num_offers'];
    
        // Query to get the count of offers for the last month
    $shipmentsQuery = "SELECT COUNT(*) AS num_shipments FROM shipments s JOIN offers o ON s.offer_id = o.id WHERE o.logistics_company_crn = $commercialNumber AND YEAR(s.date_time) = YEAR(CURRENT_DATE) AND MONTH(s.date_time) = MONTH(CURRENT_DATE)";
    $shipmentsResult = mysqli_query($conn, $shipmentsQuery);
    $shipmentsRow = mysqli_fetch_assoc($shipmentsResult);
    $numShipments = $shipmentsRow['num_shipments'];
    
    // Query to get the count of offers for the last month
    $expensesQuery = "SELECT SUM(o.price) AS sum_prices
                      FROM shipments s
                      JOIN offers o ON s.offer_id = o.id
                      WHERE o.logistics_company_crn = $commercialNumber;
                      ";
    $expensesResult = mysqli_query($conn, $expensesQuery);
    $expensesRow = mysqli_fetch_assoc($expensesResult);
    $sumExpenses = $expensesRow['sum_prices'];
    ?>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">This month's Requests</p>
                    <h5 class="font-weight-bolder mb-0">
                      <?php echo $numRequests; ?> Requests
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">This month's Offers</p>
                    <h5 class="font-weight-bolder mb-0">
                      <?php echo $numOffers; ?> Offers
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">This month's Shipments</p>
                    <h5 class="font-weight-bolder mb-0">
                      <?php echo $numShipments; ?> Shipments
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Earnings</p>
                    <h5 class="font-weight-bolder mb-0">
                     <?php echo number_format($sumExpenses); ?> SAR
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row my-4">
        <div class="col-12 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
              		<h6>Shipments <small class="text-xs text-secondary">(Last 6)</small><a href="shipments.php" class="text-secondary font-weight-bold text-xs px-3">See all</a></h6>
                </div>
              </div>
            </div>
            <div class="card px-0 pb-2">
              <div class="table-responsive" style="overflow-x: auto;">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cost</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">details</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                      <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date-time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Fetch data from the database
                            $sql = "SELECT s.*, r.service_type, r.destination, r.details, o.price, r.firm_name 
                    		FROM shipments s 
                            JOIN requests r 
                            ON s.request_id = r.id 
                            JOIN offers o 
                            ON s.offer_id = o.id 
                            JOIN logistics_companies lc 
                            ON o.logistics_company_crn = lc.commercial_number 
                            WHERE o.logistics_company_crn = ? 
                            ORDER BY s.date_time 
                            DESC 
                            LIMIT 6";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $commercialNumber);
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
                      <td>
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
                      ?>                              </td>
                      <td class="align-middle text-center text-sm">
                        <span class="text-xs font-weight-bold"><?php echo number_format($row["price"], 2); ?> SAR</span>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <a href="javascript:;" class="text-xs text-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="<?php echo $row['details']; ?>" data-bs-original-title="<?php echo $row['details']; ?>" style="cursor: default;">
                            <?php echo $row['details']; ?>
                          </a>                      </td>
                      <td class="align-middle text-center text-sm">
                        <?php 
                            if ($row['status'] == 'active') {
                                echo '<span class="badge badge-sm bg-gradient-success">ACTIVE</span>';
                            } else {
                                echo '<span class="badge badge-sm bg-gradient-secondary">INACTIVE</span>';
                            }
                        ?>                      </td>
                      <td class="align-middle">
						<span class="text-secondary text-xs font-weight-bold"><?php echo date('d/m/y H:i', strtotime($row['date_time'])); ?></span>
                      </td>
                    </tr>
                    <?php
                      }
                    } else {
                        // No results found
                        echo "No shipments available";
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
              <h6>Sent Offers <small class="text-xs text-secondary">(Last 6)</small><a href="offers.php" class="text-secondary font-weight-bold text-xs px-3">See all</a></h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">To</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Request type</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Destinations</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price offered</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">date-time</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php // SQL query to retrieve offers with client information
                    $sql = "SELECT o.*, r.`firm_name` AS client_name, r.`service_type` AS request_type,
                             r.`destination` AS destination, r.`email` AS client_email
                      FROM offers o 
                      INNER JOIN requests r ON o.request_id = r.id
                      WHERE logistics_company_crn = ?
                      ORDER BY date_time DESC 
                      LIMIT 6;";
                      // Prepare the statement
                      $stmt = $conn->prepare($sql);

                      // Bind the logistics_company_crn parameter
                      $stmt->bind_param("s", $commercialNumber);

                      // Execute the statement
                      $stmt->execute();

                      // Get the result
                      $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex px-2 py-1">
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm"><?php echo $row["client_name"]; ?></h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="text-xs font-weight-bold mb-0"><?php echo $row["request_type"]; ?></p>
						</td>
                      <td>
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
                        <span class="text-secondary text-xs font-weight-bold"><?php echo number_format($row["price"], 2); ?> SAR</span>
                        </td>
                        <td class="align-middle text-center">
                            <span class="text-secondary text-xs font-weight-bold"><?php echo date('d/m/y H:i', strtotime($row['date_time'])); ?></span>
                        </td>
                        <!--<td class="align-middle">
                            <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                Edit
                            </a>
                        </td>-->
                    </tr>
                <?php
                }
                } else {
                echo "<tr><td colspan='5'>You did not send any offers yet.</td></tr>";
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
              <h6>Active Requests <small class="text-xs text-secondary">(Last 6)</small><a href="requests.php" class="text-secondary font-weight-bold text-xs px-3">See all</a></h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
              
              <?php
              // Format the $services array into a comma-separated string
              $serviceString = "'" . implode("', '", $services) . "'";

              // Construct the SQL query with the formatted $serviceString
              $sql = "SELECT *
                      FROM requests
                      WHERE approval = 'yes' 
                        AND status = 'active' 
                        AND NOT EXISTS (
                            SELECT 1 
                            FROM offers 
                            WHERE offers.request_id = requests.id 
                            AND offers.logistics_company_crn = ?
                        )
                        AND service_type IN ($serviceString)
                      ORDER BY date_time DESC 
                      LIMIT 6";

              // Prepare the statement
              $stmt = $conn->prepare($sql);

              // Bind commercialNumber parameter
              $stmt->bind_param("s", $commercialNumber);

              // Execute the statement
              $stmt->execute();

              // Get the result
              $result = $stmt->get_result();
                ?>
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Service</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">details</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Firm name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Destinations</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date-Time</th>
                            <th class="text-secondary opacity-7"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if there is data returned from the query
                        if ($result->num_rows > 0) {
                            // Output data of each row
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
                                          echo '<img src="../../img/boxes.png" height="30" width="30" style="margin-right: 0.75rem; margin-left: 0.50rem;" alt="main_logo">';
                                        }
                                        ?>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm"><?php echo $row['service_type']; ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="text-xs text-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="<?php echo $row['details']; ?>" data-bs-original-title="<?php echo $row['details']; ?>" style="cursor: default;">
                                            <?php echo $row['details']; ?>
                                        </a>
                                  </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?php echo $row['firm_name']; ?></p>
                                    </td>
                                    <td>
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
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold"><?php echo date('d/m/y H:i', strtotime($row['date_time'])); ?></span>
                                    </td>
                                    <td class="align-middle">
                                    <?php
                                    $request_id = base64_encode($row['id']);
                                    $firm_name = base64_encode($row['firm_name']);
                                    $service_type = base64_encode($row['service_type']);
                                    $details = base64_encode($row['details']);
                                    $destination = base64_encode($row['destination']);
                                    ?>
                                    <a href="makeoffer.php?request_id=<?php echo $request_id; ?>&firm_name=<?php echo $firm_name; ?>&service_type=<?php echo $service_type; ?>&details=<?php echo $details; ?>&destination=<?php echo $destination; ?>" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Send Offer">
                                        Send Offer
                                    </a>
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            // If no data found in the database
                            echo "<tr><td colspan='5'>There is no available Requests</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <?php
                // Close the database connection
                $stmt->close();
                $conn->close();
                ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <script>
    var ctx = document.getElementById("chart-bars").getContext("2d");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Sales",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#fff",
          data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
          maxBarThickness: 6
        }, ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 500,
              beginAtZero: true,
              padding: 15,
              font: {
                size: 14,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
              color: "#fff"
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false
            },
            ticks: {
              display: false
            },
          },
        },
      },
    });


    var ctx2 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

    var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
    gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "Mobile apps",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#cb0c9f",
            borderWidth: 3,
            backgroundColor: gradientStroke1,
            fill: true,
            data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
            maxBarThickness: 6

          },
          {
            label: "Websites",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#3A416F",
            borderWidth: 3,
            backgroundColor: gradientStroke2,
            fill: true,
            data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
            maxBarThickness: 6
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#b2b9bf',
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#b2b9bf',
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
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