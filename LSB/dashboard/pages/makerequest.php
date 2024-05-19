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
$firmName = ''; // Placeholder for the retrieved firm name from the database
$commercialNumber = ''; // Placeholder for the retrieved commercial number from the database

// Prepare and execute SQL query to fetch firm name and commercial number based on email
$stmt_firm = $conn->prepare("SELECT firm_name, commercial_number FROM clients WHERE email = ?");
$stmt_firm->bind_param("s", $email);
$stmt_firm->execute();
$result_firm = $stmt_firm->get_result();

if ($result_firm->num_rows > 0) {
    $firm_row = $result_firm->fetch_assoc();
    $firmName = $firm_row['firm_name'];
    $commercialNumber = $firm_row['commercial_number'];
}

// Close the statement
$stmt_firm->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Make a Request - LSB
  </title>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  
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
          <a class="nav-link  " href="../pages/dashboard.php">
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
          <a class="nav-link  " href="../pages/requests.php">
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
          <a class="nav-link  " href="../pages/offers.php">
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
          <a class="nav-link  " href="../pages/shipments.php">
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
            <a href="contact.php" class="btn btn-white btn-sm w-100 mb-0">CONTACT US</a>
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
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">LSB</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Requests</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Make a Request</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group">
              <a class="btn btn-outline-primary btn-sm mb-0 me-3" href="#">Make a Request</a>
            </div>
          </div>
          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a class="btn btn-outline-primary btn-sm mb-0 me-3" style="color: #d10000 !important;border-color: #d10000;" href="logout.php">Log out</a>
            </li>
            <li class="nav-item d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body font-weight-bold px-0">
                <i class="fa fa-user me-sm-1"></i>
                <span class="d-sm-inline d-none">Hi, <?php echo $firmName; ?></span>
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
    <section class="min-vh-100 mb-8">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('../assets/img/curved-images/curved14.jpg');">
          <span class="mask bg-gradient-dark opacity-6"></span>
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-lg-5 text-center mx-auto">
                <h1 class="text-white mb-2 mt-5">Make a Request!</h1>
                <p class="text-lead text-white">Fill up form fields to send a request.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="row mt-lg-n10 mt-md-n11 mt-n10">
            <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
              <div class="card z-index-0">
                <div class="card-body">
<form role="form text-left" method="POST" action="../../php/makerequest.php" id="requestForm">
    <div class="mb-2">
        <label for="service-type" class="form-label">Service type</label>
        <select class="form-select" id="service-type" name="service_type" aria-label="Service type">
            <option value="Car Transportation">Car Transportation</option>
            <option value="Freight Transportation">Freight Transportation</option>
            <option value="High-risk Shipment">High-risk Shipment</option>
        </select>
    </div>
    
    <div class="mb-2">
        <input type="text" class="form-control" id="details" name="details" placeholder="add additional details about the shipment">
    </div>

    <div id="locationcreator">
        <div class="location-container">
            <label for="destination1" class="form-label">Destination 1:</label>
            <div class="mb-2">
                <label for="destination1From" class="form-label">From</label>
                <select class="form-select" id="city1From" name="pickup_cities[]" aria-label="City" style="width: 32%; display: inline-block;">
                        <option value="Abha">Abha</option>
                        <option value="Ad-Dilam">Ad-Dilam</option>
                        <option value="Al-Abwa">Al-Abwa</option>
                        <option value="Al Artaweeiyah">Al Artaweeiyah</option>
                        <option value="Al Bukayriyah">Al Bukayriyah</option>
                        <option value="Badr">Badr</option>
                        <option value="Baljurashi">Baljurashi</option>
                        <option value="Bisha">Bisha</option>
                        <option value="Bareq">Bareq</option>
                        <option value="Buraydah">Buraydah</option>
                        <option value="Al Bahah">Al Bahah</option>
                        <option value="Arar">Arar</option>
                        <option value="Dammam">Dammam</option>
                        <option value="Dhahran">Dhahran</option>
                        <option value="Dhurma">Dhurma</option>
                        <option value="Dahaban">Dahaban</option>
                        <option value="Diriyah">Diriyah</option>
                        <option value="Duba">Duba</option>
                        <option value="Dumat Al-Jandal">Dumat Al-Jandal</option>
                        <option value="Dawadmi">Dawadmi</option>
                        <option value="Farasan">Farasan</option>
                        <option value="Gatgat">Gatgat</option>
                        <option value="Gerrha">Gerrha</option>
                        <option value="Ghawiyah">Ghawiyah</option>
                        <option value="Al-Gwei'iyyah">Al-Gwei'iyyah</option>
                        <option value="Hautat Sudair">Hautat Sudair</option>
                        <option value="Habaala">Habaala</option>
                        <option value="Hajrah">Hajrah</option>
                        <option value="Haql">Haql</option>
                        <option value="Al-Hareeq">Al-Hareeq</option>
                        <option value="Harmah">Harmah</option>
                        <option value="Ha'il">Ha'il</option>
                        <option value="Hotat Bani Tamim">Hotat Bani Tamim</option>
                        <option value="Hofuf-Al-Mubarraz">Hofuf-Al-Mubarraz</option>
                        <option value="Huraymila">Huraymila</option>
                        <option value="Hafr Al-Batin">Hafr Al-Batin</option>
                        <option value="Jabal Umm al Ru'us">Jabal Umm al Ru'us</option>
                        <option value="Jalajil">Jalajil</option>
                        <option value="Jeddah">Jeddah</option>
                        <option value="Jizan">Jizan</option>
                        <option value="Jazan Economic City">Jazan Economic City</option>
                        <option value="Jubail">Jubail</option>
                        <option value="Al Jafr">Al Jafr</option>
                        <option value="Irqah">Irqah</option>
                        <option value="Khafji">Khafji</option>
                        <option value="Khaybar">Khaybar</option>
                        <option value="King Abdullah Economic City">King Abdullah Economic City</option>
                        <option value="Khamis Mushait">Khamis Mushait</option>
                        <option value="King Khalid Military City">King Khalid Military City</option>
                        <option value="Al-Saih">Al-Saih</option>
                        <option value="Knowledge Economic City, Medina">Knowledge Economic City, Medina</option>
                        <option value="Khobar">Khobar</option>
                        <option value="Al-Khutt">Al-Khutt</option>
                        <option value="Layla">Layla</option>
                        <option value="Lihyan">Lihyan</option>
                        <option value="Al Lith">Al Lith</option>
                        <option value="Al Majma'ah">Al Majma'ah</option>
                        <option value="Mastoorah">Mastoorah</option>
                        <option value="Al Mikhwah">Al Mikhwah</option>
                        <option value="Al Mawain">Al Mawain</option>
                        <option value="Medina">Medina</option>
                        <option value="Mecca">Mecca</option>
                        <option value="Muzahmiyya">Muzahmiyya</option>
                        <option value="Najran">Najran</option>
                        <option value="Al-Namas">Al-Namas</option>
                        <option value="Neom">Neom</option>
                        <option value="Umluj">Umluj</option>
                        <option value="Al-Omran">Al-Omran</option>
                        <option value="Al-Oyoon">Al-Oyoon</option>
                        <option value="Qadeimah">Qadeimah</option>
                        <option value="Qatif">Qatif</option>
                        <option value="Qaisumah">Qaisumah</option>
                        <option value="Al Qadeeh">Al Qadeeh</option>
                        <option value="Al Qunfudhah">Al Qunfudhah</option>
                        <option value="Qurayyat">Qurayyat</option>
                        <option value="Rabigh">Rabigh</option>
                        <option value="Rafha">Rafha</option>
                        <option value="Ar Rass">Ar Rass</option>
                        <option value="Ras Tanura">Ras Tanura</option>
                        <option value="Rumah">Rumah</option>
                        <option value="Ranyah">Ranyah</option>
                        <option value="Riyadh">Riyadh</option>
                        <option value="Riyadh Al-Khabra">Riyadh Al-Khabra</option>
                        <option value="Rumailah">Rumailah</option>
                        <option value="Sabt Al Alaya">Sabt Al Alaya</option>
                        <option value="Sabya">Sabya</option>
                        <option value="Sarat Abidah">Sarat Abidah</option>
                        <option value="Saihat">Saihat</option>
                        <option value="Safwa city">Safwa city</option>
                        <option value="Sakakah">Sakakah</option>
                        <option value="Sharurah">Sharurah</option>
                        <option value="Shaqra">Shaqra</option>
                        <option value="Shaybah">Shaybah</option>
                        <option value="As Sulayyil">As Sulayyil</option>
                        <option value="Taif">Taif</option>
                        <option value="Tabuk">Tabuk</option>
                        <option value="Tanomah">Tanomah</option>
                        <option value="Tarout">Tarout</option>
                        <option value="Tayma">Tayma</option>
                        <option value="Thadiq">Thadiq</option>
                        <option value="Thuwal">Thuwal</option>
                        <option value="Thuqbah">Thuqbah</option>
                        <option value="Turaif">Turaif</option>
                        <option value="Tabarjal">Tabarjal</option>
                        <option value="Udhailiyah">Udhailiyah</option>
                        <option value="Al-'Ula">Al-'Ula</option>
                        <option value="Um Al-Sahek">Um Al-Sahek</option>
                        <option value="Unaizah">Unaizah</option>
                        <option value="Uqair">Uqair</option>
                        <option value="Uyayna">Uyayna</option>
                        <option value="Uyun AlJiwa">Uyun AlJiwa</option>
                        <option value="Wadi Al-Dawasir">Wadi Al-Dawasir</option>
                        <option value="Al Wajh">Al Wajh</option>
                        <option value="Yanbu">Yanbu</option>
                        <option value="Az Zaimah">Az Zaimah</option>
                        <option value="Zulfi">Zulfi</option>
                </select>
                <label for="destination1To" class="form-label" style="margin-left: 15px;">to</label>
                <select class="form-select" id="city1To" name="delivery_cities[]" aria-label="City" style="width: 32%; display: inline-block;">
                        <option value="Abha">Abha</option>
                        <option value="Ad-Dilam">Ad-Dilam</option>
                        <option value="Al-Abwa">Al-Abwa</option>
                        <option value="Al Artaweeiyah">Al Artaweeiyah</option>
                        <option value="Al Bukayriyah">Al Bukayriyah</option>
                        <option value="Badr">Badr</option>
                        <option value="Baljurashi">Baljurashi</option>
                        <option value="Bisha">Bisha</option>
                        <option value="Bareq">Bareq</option>
                        <option value="Buraydah">Buraydah</option>
                        <option value="Al Bahah">Al Bahah</option>
                        <option value="Arar">Arar</option>
                        <option value="Dammam">Dammam</option>
                        <option value="Dhahran">Dhahran</option>
                        <option value="Dhurma">Dhurma</option>
                        <option value="Dahaban">Dahaban</option>
                        <option value="Diriyah">Diriyah</option>
                        <option value="Duba">Duba</option>
                        <option value="Dumat Al-Jandal">Dumat Al-Jandal</option>
                        <option value="Dawadmi">Dawadmi</option>
                        <option value="Farasan">Farasan</option>
                        <option value="Gatgat">Gatgat</option>
                        <option value="Gerrha">Gerrha</option>
                        <option value="Ghawiyah">Ghawiyah</option>
                        <option value="Al-Gwei'iyyah">Al-Gwei'iyyah</option>
                        <option value="Hautat Sudair">Hautat Sudair</option>
                        <option value="Habaala">Habaala</option>
                        <option value="Hajrah">Hajrah</option>
                        <option value="Haql">Haql</option>
                        <option value="Al-Hareeq">Al-Hareeq</option>
                        <option value="Harmah">Harmah</option>
                        <option value="Ha'il">Ha'il</option>
                        <option value="Hotat Bani Tamim">Hotat Bani Tamim</option>
                        <option value="Hofuf-Al-Mubarraz">Hofuf-Al-Mubarraz</option>
                        <option value="Huraymila">Huraymila</option>
                        <option value="Hafr Al-Batin">Hafr Al-Batin</option>
                        <option value="Jabal Umm al Ru'us">Jabal Umm al Ru'us</option>
                        <option value="Jalajil">Jalajil</option>
                        <option value="Jeddah">Jeddah</option>
                        <option value="Jizan">Jizan</option>
                        <option value="Jazan Economic City">Jazan Economic City</option>
                        <option value="Jubail">Jubail</option>
                        <option value="Al Jafr">Al Jafr</option>
                        <option value="Irqah">Irqah</option>
                        <option value="Khafji">Khafji</option>
                        <option value="Khaybar">Khaybar</option>
                        <option value="King Abdullah Economic City">King Abdullah Economic City</option>
                        <option value="Khamis Mushait">Khamis Mushait</option>
                        <option value="King Khalid Military City">King Khalid Military City</option>
                        <option value="Al-Saih">Al-Saih</option>
                        <option value="Knowledge Economic City, Medina">Knowledge Economic City, Medina</option>
                        <option value="Khobar">Khobar</option>
                        <option value="Al-Khutt">Al-Khutt</option>
                        <option value="Layla">Layla</option>
                        <option value="Lihyan">Lihyan</option>
                        <option value="Al Lith">Al Lith</option>
                        <option value="Al Majma'ah">Al Majma'ah</option>
                        <option value="Mastoorah">Mastoorah</option>
                        <option value="Al Mikhwah">Al Mikhwah</option>
                        <option value="Al Mawain">Al Mawain</option>
                        <option value="Medina">Medina</option>
                        <option value="Mecca">Mecca</option>
                        <option value="Muzahmiyya">Muzahmiyya</option>
                        <option value="Najran">Najran</option>
                        <option value="Al-Namas">Al-Namas</option>
                        <option value="Neom">Neom</option>
                        <option value="Umluj">Umluj</option>
                        <option value="Al-Omran">Al-Omran</option>
                        <option value="Al-Oyoon">Al-Oyoon</option>
                        <option value="Qadeimah">Qadeimah</option>
                        <option value="Qatif">Qatif</option>
                        <option value="Qaisumah">Qaisumah</option>
                        <option value="Al Qadeeh">Al Qadeeh</option>
                        <option value="Al Qunfudhah">Al Qunfudhah</option>
                        <option value="Qurayyat">Qurayyat</option>
                        <option value="Rabigh">Rabigh</option>
                        <option value="Rafha">Rafha</option>
                        <option value="Ar Rass">Ar Rass</option>
                        <option value="Ras Tanura">Ras Tanura</option>
                        <option value="Rumah">Rumah</option>
                        <option value="Ranyah">Ranyah</option>
                        <option value="Riyadh">Riyadh</option>
                        <option value="Riyadh Al-Khabra">Riyadh Al-Khabra</option>
                        <option value="Rumailah">Rumailah</option>
                        <option value="Sabt Al Alaya">Sabt Al Alaya</option>
                        <option value="Sabya">Sabya</option>
                        <option value="Sarat Abidah">Sarat Abidah</option>
                        <option value="Saihat">Saihat</option>
                        <option value="Safwa city">Safwa city</option>
                        <option value="Sakakah">Sakakah</option>
                        <option value="Sharurah">Sharurah</option>
                        <option value="Shaqra">Shaqra</option>
                        <option value="Shaybah">Shaybah</option>
                        <option value="As Sulayyil">As Sulayyil</option>
                        <option value="Taif">Taif</option>
                        <option value="Tabuk">Tabuk</option>
                        <option value="Tanomah">Tanomah</option>
                        <option value="Tarout">Tarout</option>
                        <option value="Tayma">Tayma</option>
                        <option value="Thadiq">Thadiq</option>
                        <option value="Thuwal">Thuwal</option>
                        <option value="Thuqbah">Thuqbah</option>
                        <option value="Turaif">Turaif</option>
                        <option value="Tabarjal">Tabarjal</option>
                        <option value="Udhailiyah">Udhailiyah</option>
                        <option value="Al-'Ula">Al-'Ula</option>
                        <option value="Um Al-Sahek">Um Al-Sahek</option>
                        <option value="Unaizah">Unaizah</option>
                        <option value="Uqair">Uqair</option>
                        <option value="Uyayna">Uyayna</option>
                        <option value="Uyun AlJiwa">Uyun AlJiwa</option>
                        <option value="Wadi Al-Dawasir">Wadi Al-Dawasir</option>
                        <option value="Al Wajh">Al Wajh</option>
                        <option value="Yanbu">Yanbu</option>
                        <option value="Az Zaimah">Az Zaimah</option>
                        <option value="Zulfi">Zulfi</option>
                </select>
                <img src="../assets/img/add.png" onclick="createDiv()" width="20" height="20" title="add another location" alt="add locations icon" style="cursor: pointer;">
            </div>
        </div>
    </div>

    <div class="mb-2">
        <label for="commercialnumber" class="form-label">Commercial Number</label>
        <input type="text" class="form-control" id="commercialnumber" name="commercial_number" value="<?php echo $commercialNumber; ?>" readonly>
    </div>

    <div class="mb-2">
        <label for="firmname" class="form-label">Firm Name</label>
        <input type="text" class="form-control" id="firmname" name="firm_name" value="<?php echo $firmName; ?>" readonly>
    </div>

    <div>
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" readonly>
    </div>

    <!--<div class="form-check form-check-info text-left">
        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
        <label class="form-check-label" for="flexCheckDefault">
            I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and Conditions</a>
        </label>
    </div>-->
		
    <div class="text-center">
        <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Send Request</button>
    </div>
  
    <?php
    // Check if there is a success or error message in the GET parameters
    if (isset($_GET['success'])) {
        ?>
        <label style="color: green;" class="form-check-label" for="flexCheckDefault">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </label>
        <?php
    } elseif (isset($_GET['error'])) {
        ?>
        <label style="color: red;" class="form-check-label" for="flexCheckDefault">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </label>
        <?php
    }
    ?>
</form>

<script>
    let divCount = 1; // Start from 1 as the initial div is already there

    function createDiv() {
        divCount++;
        const container = document.getElementById('locationcreator');
        const lastLocation = document.querySelector('.location-container:last-child');
        const clone = lastLocation.cloneNode(true);

        // Update labels for the new set of inputs
        const labels = clone.querySelectorAll('label');
        labels.forEach(label => {
            const labelText = label.textContent.trim();
            const labelFor = label.getAttribute('for');
            const labelNumber = parseInt(labelFor.replace('destination', ''));
            label.textContent = labelText.replace(labelNumber, divCount);
            label.setAttribute('for', labelFor.replace(labelNumber, divCount));
        });

        // Update IDs and names for the new set of select elements
        const selects = clone.querySelectorAll('select');
        selects.forEach(select => {
            const selectId = select.getAttribute('id');
            const selectName = select.getAttribute('name');
            const selectNumber = parseInt(selectId.replace('city', ''));
            select.setAttribute('id', selectId.replace(selectNumber, divCount));
            select.setAttribute('name', selectName.replace(selectNumber, divCount));
        });

        container.appendChild(clone);
    }
</script>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
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