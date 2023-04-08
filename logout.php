<?php
// start session
session_start();

// check if user is already logged in
if (!isset($_SESSION["username"])) {
  // redirect to register page
  header("Location: login.php");
  exit;
}
else{
  unset($_SESSION["username"]);
  header("location: login.php");
}
?>