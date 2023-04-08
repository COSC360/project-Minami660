<?php
// start session
session_start();

// check if user is already logged in
if (isset($_SESSION["username"])) {
  // redirect to home page
  header("Location: welcome.php");
  exit;
}

// database connection variables
$host = "localhost";
$user = "70613104";
$password = "70613104";
$dbname = "db_70613104";

// connect to the database
$conn = mysqli_connect($host, $user, $password, $dbname);

// check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // get data from form
  $email = $_POST["email"];
  $password = $_POST["password"];

  // prepare and execute SQL statement
  $stmt = mysqli_prepare($conn, "SELECT id, name, is_admin FROM users WHERE email = ? AND password = ?");
  mysqli_stmt_bind_param($stmt, "ss", $email, $password);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $user_id, $user_username, $is_admin);
  mysqli_stmt_fetch($stmt);

  // check if user exists
  if ($user_id) {
    // set session variables
    $_SESSION["user_id"] = $user_id;
    $_SESSION["username"] = $user_username;
    $_SESSION["is_admin"] = $is_admin;

    // redirect to home page
    header("Location: welcome.php");
    exit;
  } else {
    $error_message = "Invalid username or password.";
  }

  // close statement
  mysqli_stmt_close($stmt);
}

// close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon-32x32.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
      integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
      crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
      integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
      crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
      integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
      crossorigin="anonymous"></script>
      <script type="text/javascript" src="./login.js"></script>
    <title>UniFoods - Login</title>
  </head>

  <body>
    <nav class="bg-dark navbar navbar-expand-lg navbar-dark navbar-custom">
      <div class="container px-5">
        <img class="me-2" src="./favicon-32x32.png" />
        <a class="navbar-brand" href="./welcome.php">UniFoods</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
          aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarsExampleDefault">
          <ul class="navbar-nav mr-auto">
            <?php if (!isset($_SESSION["username"])): ?>
              <li class="nav-item">
                <a class="nav-link" href="./login.php">Login</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="./register.php">Sign Up</a>
              </li>
            <?php endif; ?>
            <?php if (isset($_SESSION["username"])): ?>
            <li class="nav-item">
              <a class="nav-link" href="./user.php?id=<?php echo $_SESSION["user_id"]; ?>">
                <?php echo $_SESSION["username"]; ?>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./logout.php">Log Out</a>
            </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container px-5 pt-4">
      <form class="offset-lg-3 col-lg-6" id="mainForm" method="POST">
        <?php if (!empty($error_message)): ?>
          <div style="color: red;"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Email input -->
        <div class="form-outline mb-4">
          <input type="email" id="form2Example1" name="email" class="form-control" />
          <label class="form-label" for="form2Example1">Email address</label>
        </div>

        <!-- Password input -->
        <div class="form-outline mb-4">
          <input type="password" id="form2Example2" name="password" class="form-control" />
          <label class="form-label" for="form2Example2">Password</label>
        </div>

        <!-- 2 column grid layout for inline styling -->
        <!-- <div class="row mb-4">
          <div class="col">
            <a href="#!">Forgot password?</a>
          </div>
        </div> -->

        <button type="submit" class="offset-lg-10 col-lg-2 btn btn-primary btn-block mb-4">Sign in</button>

        <!-- Register buttons -->
        <div class="text-center">
          <p>Not a member? <a href="./register.php">Register</a></p>
        </div>
      </form>
    </div>
  </body>

</html>