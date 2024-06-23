<?php
require_once 'header.php';

  if (!$loggedin) die("</div></body></html>");

  if (isset($_GET['view'])) $view = sanitizeString($_GET['view']);
  else                      $view = $user;

  if (isset($_POST['text']))
  {
    $text = sanitizeString($_POST['text']);
    if ($text != "")
    {
      $pm   = substr(sanitizeString($_POST['pm']),0,1);
      $time = time();
      $recip = ($_POST['st'])?sanitizeString($_POST['st']):$view;
      queryMysql("INSERT INTO messages VALUES(NULL, '$user',
        '$recip', '$pm', $time, '$text')");
      echo "<script type='text/javascript'>alert('Message sent successfully');</script>";
    }
  }

  if (isset($_GET['keyword'])) {
    $keyword = sanitizeString($_GET['keyword']);
  }

  if (isset($_GET['friend'])) {
    $friend = sanitizeString($_GET['friend']);
  }

  if (isset($_GET['receiver'])) {
    $receiver = sanitizeString($_GET['receiver']);
  }

  if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
  }

  if ($view != "")
  {
    if ($view == $user) $name1 = $name2 = "Your";
    else
    {
      $name1 = "<a href='members.php?view=$view'>$view</a>'s";
      $name2 = "$view's";
    }
    echo "<h3>$name1 Messages</h3>";
    showProfile($view);
    echo <<<_END
    <div>
      <form method='post' action='messages.php?view=$view'>
        <fieldset data-role="controlgroup" data-type="horizontal">
          <legend>Type here to leave a message</legend>
          <input type='radio' name='pm' id='public' value='0' checked='checked'>
          <label for="public">Public</label>
          <input type='radio' name='pm' id='private' value='1'>
          <label for="private">Private</label>
        </fieldset>
        <label style="display:inline-block">Send to</label>
        <input type='text' name='st' id='sendTo' placeholder="yourself">
        <textarea name='text'></textarea>
        <div style="display:flex">
      <input data-transition='slide' type='submit' value='Post Message'>
      </div>
    </form><br>
    </div>
    <div style="display:flex;flex-direction: row;align-items: center;">
    <form method='get' action='messages.php?view=$view'>
      <div style="display:flex;flex-direction: row;align-items: center;">
        <input style = 'display:inline;' type="text" name="sender" placeholder="Search for Sender">
        <input style = 'display:inline' type="text" name="receiver" placeholder="Search for Recipient">
        <input style = 'display:inline' type="text" name="keyword" placeholder="Search for Message">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date">
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date">
        <input style = 'display:inline' type="submit" value="Search" data-role='button' >
        
      </div>

    </form>
    <form method='post' action='messages.php?view=$view'>
    <input style = 'display:inline' type="submit" value="Clear" data-role='button' >
  </form>
    </div>
 _END;

    date_default_timezone_set('UTC');

    if (isset($_GET['erase']))
    {
      $erase = sanitizeString($_GET['erase']);
      queryMysql("DELETE FROM messages WHERE id=$erase ");
    }

    $keywordConstraint = "";
    $dateConstraint = "";
    $senderConstraint = "";
    $friendConstraint = "";

    if (isset($_GET['keyword'])) {
      $keyword = sanitizeString($_GET['keyword']);
      $keywordConstraint = "and message like '%$keyword%'";
    }

    if (isset($_GET['sender'])) {
      $sender = sanitizeString($_GET['sender']);
      $senderConstraint = "and auth like '%$sender%'";
    }

    if (isset($_GET['receiver'])) {
      $receiver = sanitizeString($_GET['receiver']);
      $friendConstraint = "and recip like '%$receiver%'";
    }

    
  if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = strtotime($_GET['start_date']);
    $end_date = strtotime($_GET['end_date']);
    if($end_date>=$start_date)$dateConstraint = "and time between '$start_date' and '$end_date' ";
  }
    

    $query  = "SELECT * FROM messages WHERE recip='$view' $keywordConstraint $dateConstraint $senderConstraint $friendConstraint ORDER BY time DESC";
    $result = queryMysql($query);
    $num    = $result->num_rows;
    
    for ($j = 0 ; $j < $num ; ++$j)
    {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      if ($row['pm'] == 0 || $row['auth'] == $user ||
          $row['recip'] == $user)
      {
        echo date('M jS \'y g:ia:', $row['time']);
        echo " <a href='messages.php?view=" . $row['auth'] .
             "'>" . $row['auth']. "</a> ";
        if ($row['pm'] == 0)
          echo "wrote: &quot;" . $row['message'] . "&quot; ";
        else
          echo "whispered: <span class='whisper'>&quot;" .
            $row['message']. "&quot;</span> ";
        if ($row['auth'] != $view)
               echo "to you";
        if ($row['recip'] == $user)
               echo "<a data-role='button' style = 'display:inline-block; padding: 6px 10px; font-size: 12px; margin: 0 auto; text-align: center; vertical-align: middle;' href='messages.php?view=$view" .
                  "&erase=" . $row['id'] . "'>erase</a>";
        echo "<br>";
        echo '<div class="separator"></div>';
      }
    }

    $query  = "SELECT * FROM messages WHERE auth = '$view' and recip!='$view' $keywordConstraint $dateConstraint $senderConstraint $friendConstraint ORDER BY time DESC";
    $result = queryMysql($query);
    $num2    = $result->num_rows;

    for ($j = 0 ; $j < $num2 ; ++$j)
    {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      if ($row['pm'] == 0 || $row['auth'] == $user ||
          $row['recip'] == $user)
      {
        echo date('M jS \'y g:ia:', $row['time']);
        echo " <a href='messages.php?view=" . $row['auth'] .
             "'>" . $row['auth']. "</a> ";
        if ($row['pm'] == 0)
          echo "wrote: &quot;" . $row['message'] . "&quot; ";
        else
          echo "whispered: <span class='whisper'>&quot;" .
            $row['message']. "&quot;</span> ";
        if($row['recip'] != $view)
            echo "to ". $row['recip'];
        if ($row['auth'] == $user)
          echo "<a data-role='button' style = 'display:inline-block; padding: 6px 10px; font-size: 12px; margin: 0 auto; text-align: center; vertical-align: middle;' href='messages.php?view=$view" .
               "&erase=" . $row['id'] . "'>erase</a>";
        echo "<br>";
      }
    }
  }
  if (!$num && !$num2)
    echo "<br><span class='info'>No messages yet</span><br><br>";
  echo "<div style='display: flex;'>
        <a  style='width:50%; margin: auto; margin-top:30px' data-role='button'
        href='messages.php?view=$view'>Refresh messages</a></div>";
 ?>
    </div><br>
  </body>
 </html>