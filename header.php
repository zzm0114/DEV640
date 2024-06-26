<?php 
  session_start();
  
 echo <<<_INIT
 <!DOCTYPE html> 
 <html>
  <head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'> 
    <link rel='stylesheet' href='jquery.mobile-1.4.5.min.css'>
    <link rel='stylesheet' href='styles.css'>
    <script src='javascript.js'></script>
    <script src='jquery-2.2.4.min.js'></script>
    <script src='jquery.mobile-1.4.5.min.js'></script>

 _INIT;

  require_once 'functions.php';

  $userstr = 'Welcome Guest';

  if (isset($_SESSION['user']))
  {
    $user     = $_SESSION['user'];
    $loggedin = TRUE;
    $userstr  = "Logged in as: $user";
  }
  else $loggedin = FALSE;

 echo <<<_MAIN
    <title>TeamingUp: $userstr</title>
  </head>
  <body>
  <div data-role='page'>
    <div data-role='header'>
      <div id='logo' 
        class='center'><img id='TeamingUp' src='TeamingUp.png'>TeamingUp</div>
      <div class='username'>$userstr</div>
    </div>
    <div data-role='content'>

_MAIN;

if ($loggedin)
{
          // <a data-role='button' data-inline='true' data-icon='home'
        //   data-transition="slide" href='members.php?view=$user'>Home</a>
echo <<<_LOGGEDIN
      <div class='center'>

        <a data-role='button' data-inline='true'
          data-transition="slide" href='members.php'>Members</a>
        <a data-role='button' data-inline='true'
          data-transition="slide" href='friends.php'>Friend List</a>
        <a data-role='button' data-inline='true'
          data-transition="slide" href='messages.php'>Messages</a>
        <a data-role='button' data-inline='true'
          data-transition="slide" href='profile.php'>Edit Profile</a>
        <a data-role='button' data-inline='true'
          data-transition="slide" href='logout.php'>Log out</a>
      </div>
_LOGGEDIN;
}
else
{
echo <<<_GUEST
      <div class='center'>
        <a data-role='button' data-inline='true' data-icon='home'
          data-transition='slide' href='index.php'>Home</a>
        <a data-role='button' data-inline='true' data-icon='plus'
          data-transition="slide" href='signup.php'>Sign Up</a>
        <a data-role='button' data-inline='true' data-icon='check'
          data-transition="slide" href='login.php'>Log In</a>
      </div>
      <p class='info'></p>
      
_GUEST;
}
?>