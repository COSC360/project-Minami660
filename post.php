<?php
// start session
session_start();

// check if user is logged in
if (!isset($_SESSION["username"])) {
  // redirect to login page
  header("Location: test_login.php");
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

// process comment submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // get form data
  $post_id = $_POST["post_id"];
  $user_id = $_POST["user_id"];
  $comment = $_POST["comment"];

  // prepare and execute SQL statement
  $stmt = mysqli_prepare($conn, "INSERT INTO comments (post_id, user_id, body) VALUES (?, ?, ?)");
  mysqli_stmt_bind_param($stmt, "iss", $post_id, $user_id, $comment);
  mysqli_stmt_execute($stmt);

  // check for errors
  if (mysqli_stmt_error($stmt)) {
    $error_message = "Error: " . mysqli_stmt_error($stmt);
  } else {
    $success_message = "Comment added successfully!";
  }

  // close statement
  mysqli_stmt_close($stmt);
}

// get post data from database
$post_id = $_GET["id"];
$post_result = mysqli_query($conn, "SELECT posts.*, post_tags.tag as tags FROM posts LEFT JOIN post_tags ON posts.id = post_tags.post_id WHERE id = " . $post_id);
if (mysqli_num_rows($post_result) == 0) {
  // no post found with that ID
  $error_message = "Error: post not found.";
} else {
  // display post data
  $post = mysqli_fetch_assoc($post_result);
  $title = $post["title"];
  $content = $post["content"];
  $tags = $post["tags"];
  $blog_id = $post["blog_id"];
  $blog_result = mysqli_query($conn, "SELECT title FROM blogs WHERE id = " . $blog_id);
  $blog = mysqli_fetch_assoc($blog_result);
  $blog_name = $blog["title"];
}

// get comments from database
$comments_result = mysqli_query($conn, "SELECT comments.*, users.id as user_id, users.name as username
  FROM comments INNER JOIN users ON comments.user_id = users.id
  WHERE post_id = " . $post_id . " ORDER BY comments.id DESC");
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>UniFoods - <?php echo $title; ?></title>
  </head>

  <body>
    <nav class="bg-dark navbar navbar-expand-lg navbar-dark navbar-custom">
      <div class="container px-5">
        <img class="me-2" src="./favicon-32x32.png" />
        <a class="navbar-brand" href="./welcome.php">UniFoods</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
          aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span
            class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ms-auto">
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
            <div class="text-center my-5">
              <?php if (!empty($error_message)): ?>
                <div style="color: red;"><?php echo $error_message; ?></div>
              <?php endif; ?>
              <h1><?php echo $title; ?></h1>
              <p><?php echo $content; ?></p>
              <p>Tags: <?php echo $tags; ?></p>
              <p>Posted in <a href="./blog.php?id=<?php echo $blog_id; ?>"><?php echo $blog_name; ?></a></p>
              <hr>
              <h2>Comments</h2>
              <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
                <p>
                  <a href="./user.php?id=<?php echo $comment["user_id"]; ?>">
                    <strong><?php echo $comment["username"]; ?>:</strong>
                  </a>
                  <?php echo $comment["body"]; ?>
                </p>
              <?php endwhile; ?>
              <?php if (!empty($error_message)): ?>
                <div style="color: red;"><?php echo $error_message; ?></div>
              <?php endif; ?>
              <?php if (!empty($success_message)): ?>
                <div style="color: green;"><?php echo $success_message; ?></div>
              <?php endif; ?>
              <form method="post">
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION["user_id"]; ?>">
                <label for="comment">Comment:</label><br>
                <textarea name="comment" id="comment" cols="30" rows="5"></textarea><br>
                <button type="submit">Submit Comment</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </header>
  </body>

</html>