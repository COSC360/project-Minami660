<?php
// start session
session_start();

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
    <title>UniFoods - Welcome</title>
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
    <header class="bg-dark py-5" style="height: calc(100vh - 56px);">
      <div class="container px-5">
        <div class="row gx-5 justify-content-center">
          <div class="col-lg-6">
            <div class="text-center my-5">
              <h1 class="display-5 fw-bolder text-white mb-2">Start your own university food blog today</h1>
              <p class="lead text-white-50 mb-4">Create your own blog to share what you love or don't!</p>
              <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                <a class="btn btn-primary btn-lg px-4 me-sm-3" href="./makeblog.php">Post a blog</a>
              </div>
              <?php if (isset($_SESSION["username"])): ?>
                  <a class="mt-2 btn btn-primary btn-lg px-4 me-sm-3" href="./makepost.php">Post to your blog</a>
                <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </header>
  </body>

</html>