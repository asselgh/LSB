<?php
//START SESSION
session_start();

//GENERATE RANDOM TOKEN (STRING)
$_SESSION["token"] = bin2hex(random_bytes(32));

// set expiry (less time for hackers)
$_SESSION["token-expire"] = time() + 3600; // 1 hour from now
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <title>Business portal - LSB</title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="../php/business_signup_process.php" method="POST">
              <h1>Create Account</h1>
              <span>Sign up to continue to LSB Business</span>

              <!-- CSRF Token -->
              <input type="hidden" name="token" value="<?=$_SESSION["token"]?>">

              <!-- Firm Name -->
              <input type="text" id="companyname" name="companyname" placeholder="Company Name">

              <!-- Commercial Number -->
              <input type="text" id="commercialnumber" maxlength="10" name="commercialnumber" placeholder="Commercial Number">

              <h4>Service Type:</h4>
             <div class="service">
              <!-- Service Type -->
              <input type="checkbox" id="cartransportation" name="cartransportation" value="cartransportation"><label for="cartransportation">Car Transportation</label>
              <input type="checkbox" id="goodsshipment" name="goodsshipment" value="goodsshipment"><label for="goodsshipment">Goods Shipment</label>
              <input type="checkbox" id="highriskshipment" name="highriskshipment" value="highriskshipment"><label for="highriskshipment">High-Risk Shipment</label>
             </div>


              <!-- to only type numbers in Commercial Number field -->
              <script>
                  $(function () {
                      $("input[name='commercialnumber']").on('input', function (e) {
                          $(this).val($(this).val().replace(/[^0-9]/g, ''));
                      });
                  });
              </script>

              <!-- Email -->
              <input type="email" id="email" name="email" placeholder="Email">

              <!-- Password -->
              <input type="password" id="password" name="password" placeholder="Password">

              <!-- Repeat Password (Added missing name attribute) -->
              <input type="password" name="repeat_password" placeholder="Repeat Password">

              <!-- Honeypot Field -->
              <input type="text" name="honeypot" style="display:none">

              <!-- Sign Up Button -->
              <button class="button2" type="submit">Sign Up</button>
          </form>
        </div>
        <div class="form-container sign-in">
            <form action="../php/business_signin.php" method="POST">
                <h1>Sign In</h1>
             	<span>Sign in to continue to LSB Business</span>
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                                                <?php if (isset($_GET["error"])) : ?>
        <p class="error-message"><?= $_GET["error"] ?></p>
    <?php endif; ?>
              
                            <!-- Honeypot Field -->
              <input type="text" name="honeypot" style="display:none">
              
                            <!-- CSRF Token -->
              <input type="hidden" name="token" value="<?=$_SESSION["token"]?>">
              
                <button class="button2" type="submit">Sign In</button>
          		<a href="index.html">Back to home page</a>  
                                               <?php if (isset($_GET["success"])) : ?>
        <p class="success-message" style="color: green;"><?= $_GET["success"] ?></p>
    <?php endif; ?>
          </form>
        </div>
        <div class="toggle-container">
            <div class="toggle2">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of our features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>New to LSB?</h1>
                    <p>Register with your personal details to use all of our features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/script.js"></script>
</body>

</html>