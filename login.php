<?php
  require_once 'header.php';
  $error = $user = $pass = "";
  if (isset($_POST['user']))
  {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);
    if ($user == "" || $pass == "")
      $error = 'Not all fields were entered';
    else
    {
      $result = queryMySQL("SELECT user,pass FROM members
        WHERE user='$user' AND pass='$pass'");
      if ($result->num_rows == 0)
      {
        $error = "Incorrect Username or Password, please try again.";
      }
      else
      {
        $_SESSION['user'] = $user;
        $_SESSION['pass'] = $pass;
        die("You are now logged in. Please <a data-transition='slide'
             href='members.php?view=$user'>click here</a> to continue.</div>
               </body></html>");
      }
    }
  }
 echo <<<_END
      <form method='post' action='login.php'>
        <div data-role='fieldcontain' style="width: 700px; margin: 0 auto; text-align: center;">
          <label></label>
          <span class='error'>$error</span>
        </div>
        <div data-role='fieldcontain' style="width: 600px; margin: 0 auto; text-align: center;">
          <label>Username</label>
          <input type='text' maxlength='16' name='user' value='$user'>
        </div>
        <div data-role='fieldcontain' style="width: 600px; margin: 0 auto; text-align: center;">
          <label>Password</label>
          <input type='password' maxlength='16' name='pass' value='$pass'>
        </div>
        <div data-role='fieldcontain' style="width: 600px; margin: 0 auto; text-align: center;">
          <label></label>
          <input data-transition='slide' type='submit' value='Login'>
        </div>
      </form>
    </div>
  </body>
 </html>
 _END;
 ?>