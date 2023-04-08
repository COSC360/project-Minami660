<?php
// start session
session_start();

if (!isset($_SESSION["username"])){
  header("location: login.php");
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
  // get form data
  $name = $_POST["blogname"];
  $description = $_POST["description"];
  $user_id = $_SESSION["user_id"];

  // prepare and execute SQL statement
  $stmt = mysqli_prepare($conn, "INSERT INTO blogs (title, description) VALUES (?, ?)");
  mysqli_stmt_bind_param($stmt, "ss", $name, $description);
  mysqli_stmt_execute($stmt);

  // get the ID of the new blog
  $blog_id = mysqli_insert_id($conn);

  // update the blog_id in the users table for the current user
  $user_id = $_SESSION["user_id"];
  $stmt = mysqli_prepare($conn, "UPDATE users SET blog_id = ? WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "ii", $blog_id, $user_id);
  mysqli_stmt_execute($stmt);

  // redirect to the new blog page
  header("Location: blog.php?id=" . $blog_id);
  exit;
  }
  
  // close statement
  mysqli_stmt_close($stmt);

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

    <script type="text/javascript" src="./makepost.js"></script>
    <title>UniFoods - Make Blog</title>
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
    <header class="py-5" style="height: calc(100vh - 56px);">
      <div class="container px-5">
        <div class="row gx-5 justify-content-center">
          <div class="col-lg-6">
            <form id="mainForm" method="post">
              <!-- Title input -->
              <div class="form-outline">
                <input type="text" name="blogname" id="blogname" class="form-control" />
                <label class="form-label" for="blogname">Blog Name</label>
              </div>

              <!-- Description input -->
              <div class="form-outline">
                <textarea type="text" name="description" id="description" class="form-control" rows="3"></textarea>
                <label class="form-label" for="description">Description</label>
              </div>

              <!-- Submit button -->
              <button type="submit" class="offset-lg-8 col-lg-4 btn btn-primary btn-block mb-4">Create Blog</button>
            </form>
          </div>
        </div>
      </div>
    </header>
  </body>

</html>
