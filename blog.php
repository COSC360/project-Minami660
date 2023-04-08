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

// get the blog ID from the URL parameter
$blog_id = $_GET["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $stmt = mysqli_prepare($conn, "DELETE FROM blogs WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $blog_id);
  mysqli_stmt_execute($stmt);
}

// get the blog information from the database
$sql = "SELECT blogs.*, users.id as user_id FROM blogs INNER JOIN users ON blogs.id = users.blog_id WHERE blogs.id = $blog_id";
$result = mysqli_query($conn, $sql);
$blog = mysqli_fetch_assoc($result);

// get all posts of the blog
$sql = "SELECT posts.*, post_tags.tag FROM posts LEFT JOIN post_tags ON post_tags.post_id = posts.id WHERE blog_id = $blog_id";
$result = mysqli_query($conn, $sql);

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
      integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
      integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
      integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <title>UniFoods - Welcome</title>
    <title><?php echo $blog["title"]; ?></title>
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
          <div class="col-lg-8">
            <div class="text-center my-5">
              <p class="lead mb-4">Add to your existing blog</p>
              <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                <a class="btn btn-primary btn-lg px-4" href="./makepost.php">Make a blog post</a>
              </div>
            </div>
            <hr />
            <div>
              <?php if(isset($_SESSION["is_admin"]) || $_SESSION["user_id"] == $blog["user_id"]): ?>
                <form method="POST">
                  <input type="hidden" name="blog_id" value="<?php echo $blog_id; ?>">
                  <input class="col-lg-12 btn btn-danger btn-block mb-4"
                    type="submit" name="delete" value="Delete this blog">
                </form>
              <?php endif; ?>
              <h1><?php echo $blog["title"]; ?></h1>
              <p><?php echo $blog["description"]; ?></p>
              <h2>Posts</h2>
              <?php
              // if you have a post show them
              if (mysqli_num_rows($result) > 0) {
                while ($post = mysqli_fetch_assoc($result)) {
                  $post_id = $post["id"];
                  // display the post information
                  echo "<h3><a href=\"./post.php?id=" . $post_id . "\" >" . $post["title"] . "</a></h3>";
                  echo "<p>Tags: " . $post["tag"] . "</p>";
                  echo "<hr />";
                }
              } else {
                echo "<p>No posts yet.</p>";
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </header>
  </body>

</html>