
<?php
// start session
session_start();

// check if user is logged in
if (!isset($_SESSION["username"])) {
  // redirect to login page
  header("Location: login.php");
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
  $title = $_POST["title"];
  $body = $_POST["body"];
  $tags = $_POST["tags"];

  // prepare and execute SQL statement
  $stmt = mysqli_prepare($conn,
    "INSERT INTO posts (title, body, blog_id) VALUES (?, ?, (SELECT blog_id FROM users WHERE users.id = ?))");
  mysqli_stmt_bind_param($stmt, "ssi", $title, $body, $_SESSION["user_id"]);
  mysqli_stmt_execute($stmt);

  // check for errors
  if (mysqli_stmt_error($stmt)) {
    $error_message = "Error: " . mysqli_stmt_error($stmt);
  } else {
    // get the ID of the new post
    $post_id = mysqli_insert_id($conn);

    // prepare and execute SQL statement
    $stmt2 = mysqli_prepare($conn, "INSERT INTO post_tags (post_id, tag) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt2, "is", $post_id, $tags);
    mysqli_stmt_execute($stmt2);

    // check for errors
    if (mysqli_stmt_error($stmt2)) {
      $error_message = "Error: " . mysqli_stmt_error($stmt2);
    } else {
      $success_message = "Post added successfully!";
      // redirect to the new blog page
      header("Location: post.php?id=" . $post_id);
      exit;
    }
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
      <script type="text/javascript" src="./makepost.js"></script>
    <title>UniFoods - Make Post</title>
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
            <form id="mainForm" method="POST">
              <?php if (!empty($error_message)): ?>
                <div style="color: red;"><?php echo $error_message; ?></div>
              <?php endif; ?>
              <?php if (!empty($success_message)): ?>
                <div style="color: green;"><?php echo $success_message; ?></div>
              <?php endif; ?>
              <!-- Title input -->
              <div class="form-outline">
                <input type="text" name="title" id="title" class="form-control" />
                <label class="form-label" for="title">Title</label>
              </div>
              <!-- Body input -->
              <div class="form-outline">
                <textarea type="text" name="body" id="body" class="form-control" rows="3"></textarea>
                <label class="form-label" for="body">Body</label>
              </div>
              <!-- Tag input -->
              <div class="form-outline">
                <input type="text" name="tags" id="tags" class="form-control" />
                <label class="form-label" for="tags">Tags</label>
              </div>

              <!-- Submit button -->
              <button type="submit" class="offset-lg-10 col-lg-2 btn btn-primary btn-block mb-4">Post</button>
            </form>
          </div>
        </div>
      </div>
    </header>
  </body>

</html>