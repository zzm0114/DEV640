<?php
  session_start();
  require_once 'header.php';
  echo "<div class='center'>Welcome to TeamingUp,";
  if ($loggedin) echo " $user, you are logged in";
  else           echo ' please sign up or log in';
  echo <<<_END
      </div><br>
      </div>
      <div data-role="footer">
        <h4>Web-Based Social Networking App by Team Red</h4>
        <p class="author">Version 1.1.0</p>
        <p class="author">Authors: Zheming, Zhen, Yuntong</p>
      </div>
    </body>
   </html>
   _END;
   ?>