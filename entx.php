<?php
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   Customized Ent for Ask Peek services
   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
require_once 'html2txt.php';
require_once 'url_get.php';
require_once 'lastrss.php';
require_once 'attach.php';
require_once 'leaves.php';

$message = "";
$who = "";
$type = "none";
$subject = "";
$body = "";

//while(1)
//{
  $message = "";   $who = "";  $type = "none";  $subject = "";  $body = "";

// sleep(2);

  /* Local Search */
  $message = "";   $who = "";  $type = "none";  $subject = "";  $body = "";
  $mbox = imap_open ("{localhost:110/pop3/notls}INBOX", "local", "password") or die("failure");
  $headers = @imap_headers($mbox);
  $numEmails = sizeof($headers);
  if ($numEmails > 0)
  {
    echo "Local: $numEmails\n";
    $pids = array();
    $tps = array();
    for($i = 1; $i < $numEmails+1; $i++)
    {
      $mailHeader = @imap_headerinfo($mbox, $i);
      $from = $mailHeader->fromaddress;
      $who = substr(strrchr($from, "<"), 1);
      $who = substr($who, 0, strlen($who)-1);
      if ($who == "") $who = $from;
      $message = strip_tags($mailHeader->subject);

//      $pid = pcntl_fork();
//      if($pid == -1)
//      {
//       die('could not fork');
//      }
//      if ($pid)
//        $pids[$i] = $pid;
//      else
//      {
        if ($message != "Mail delivery failed: returning message to sender")
          ask_local();
//          exit(0);
//      }
    }
    imap_delete($mbox,'1:*');
    imap_expunge($mbox);
//    foreach($pids as $pid)
//    {
//      echo "Waiting for $pid\n";
//      pcntl_waitpid($pid, $status);
//    }
  }
  imap_delete($mbox,'1:*');
  imap_expunge($mbox);
  imap_close($mbox);


  /* Maps */
  $message = "";   $who = "";  $type = "none";  $subject = "";  $body = "";
  $mbox = imap_open ("{localhost:110/pop3/notls}INBOX", "maps", "password") or die("failure");
  $headers = @imap_headers($mbox);
  $numEmails = sizeof($headers);
  if ($numEmails > 0)
  {
    echo "Maps: $numEmails\n";
    $pids = array();
    $tps = array();
    for($i = 1; $i < $numEmails+1; $i++)
    {
      $mailHeader = @imap_headerinfo($mbox, $i);
      $from = $mailHeader->fromaddress;
      $who = substr(strrchr($from, "<"), 1);
      $who = substr($who, 0, strlen($who)-1);
      if ($who == "") $who = $from;
      $message = strip_tags($mailHeader->subject);

//      $pid = pcntl_fork();
//      if($pid == -1)
//      {
//       die('could not fork');
//      }
//      if ($pid)
//        $pids[$i] = $pid;
//      else
//      {
        if ($message != "Mail delivery failed: returning message to sender")
          ask_maps();
//          exit(0);
//      }
    }
    imap_delete($mbox,'1:*');
    imap_expunge($mbox);
//    foreach($pids as $pid)
//    {
//      echo "Waiting for $pid\n";
//      pcntl_waitpid($pid, $status);
//    }
  }
  imap_delete($mbox,'1:*');
  imap_expunge($mbox);
  imap_close($mbox);
  sleep(10);

// sleep(2);

  /* News */
  $message = "";   $who = "";  $type = "none";  $subject = "";  $body = "";
  $mbox = imap_open ("{localhost:110/pop3/notls}INBOX", "news", "password") or die("failure");
  $headers = @imap_headers($mbox);
  $numEmails = sizeof($headers);
  if ($numEmails > 0)
  {
    echo "News: $numEmails\n";
    $pids = array();
    $tps = array();
    for($i = 1; $i < $numEmails+1; $i++)
    {
      $mailHeader = @imap_headerinfo($mbox, $i);
      $from = $mailHeader->fromaddress;
      $who = substr(strrchr($from, "<"), 1);
      $who = substr($who, 0, strlen($who)-1);
      if ($who == "") $who = $from;
      $message = strip_tags($mailHeader->subject);

//      $pid = pcntl_fork();
//      if($pid == -1)
//      {
//       die('could not fork');
//      }
//      if ($pid)
//        $pids[$i] = $pid;
//      else
//      {
        if ($message != "Mail delivery failed: returning message to sender")
          ask_news();
//          exit(0);
//      }
    }
    imap_delete($mbox,'1:*');
    imap_expunge($mbox);
//    foreach($pids as $pid)
//    {
//      echo "Waiting for $pid\n";
//      pcntl_waitpid($pid, $status);
//    }
  }
  imap_delete($mbox,'1:*');
  imap_expunge($mbox);
  imap_close($mbox);

  /* Traffic */
  $message = "";   $who = "";  $type = "none";  $subject = "";  $body = "";
  $mbox = imap_open ("{localhost:110/pop3/notls}INBOX", "traffic", "password") or die("failure");
  $headers = @imap_headers($mbox);
  $numEmails = sizeof($headers);
  if ($numEmails > 0)
  {
    echo "Traffic: $numEmails\n";
    $pids = array();
    $tps = array();
    for($i = 1; $i < $numEmails+1; $i++)
    {
      $mailHeader = @imap_headerinfo($mbox, $i);
      $from = $mailHeader->fromaddress;
      $who = substr(strrchr($from, "<"), 1);
      $who = substr($who, 0, strlen($who)-1);
      if ($who == "") $who = $from;
      $message = strip_tags($mailHeader->subject);

//      $pid = pcntl_fork();
//      if($pid == -1)
//      {
//        die('could not fork');
//      }
//      if ($pid)
//        $pids[$i] = $pid;
//      else
//     {
        if ($message != "Mail delivery failed: returning message to sender")
          ask_traffic();
//          exit(0);
//      }
    }
    imap_delete($mbox,'1:*');
    imap_expunge($mbox);
//    foreach($pids as $pid)
//    {
//      echo "Waiting for $pid\n";
//      pcntl_waitpid($pid, $status);
//    }
  }
  imap_delete($mbox,'1:*');
  imap_expunge($mbox);
  imap_close($mbox);

// sleep(2);
  sleep(10);

  /* Weather */
  $message = "";   $who = "";  $type = "none";  $subject = "";  $body = "";
  $mbox = imap_open ("{localhost:110/pop3/notls}INBOX", "weather", "password") or die("failure");
  $headers = @imap_headers($mbox);
  $numEmails = sizeof($headers);
  if ($numEmails > 0)
  {
    echo "Weather: $numEmails\n";
    $pids = array();
    $tps = array();
    for($i = 1; $i < $numEmails+1; $i++)
    {
      $mailHeader = @imap_headerinfo($mbox, $i);
      $from = $mailHeader->fromaddress;
      $who = substr(strrchr($from, "<"), 1);
      $who = substr($who, 0, strlen($who)-1);
      if ($who == "") $who = $from;
      $message = strip_tags($mailHeader->subject);

//      $pid = pcntl_fork();
//     if($pid == -1)
//      {
//        die('could not fork');
//      }
//      if ($pid)
//        $pids[$i] = $pid;
//      else
//      {
        if ($message != "Mail delivery failed: returning message to sender")
          ask_weather();
//          exit(0);
//      }
    }
    imap_delete($mbox,'1:*');
    imap_expunge($mbox);
//    foreach($pids as $pid)
//    {
//      echo "Waiting for $pid\n";
//      pcntl_waitpid($pid, $status);
//    }
  }
  imap_delete($mbox,'1:*');
  imap_expunge($mbox);
  imap_close($mbox);

// sleep(2);

  /* Howto */
  $message = "";   $who = "";  $type = "none";  $subject = "";  $body = "";
  $mbox = imap_open ("{localhost:110/pop3/notls}INBOX", "howto", "password") or die("failure");
  $headers = @imap_headers($mbox);
  $numEmails = sizeof($headers);
  if ($numEmails > 0)
  {
    echo "Howto: $numEmails\n";
    $pids = array();
    $tps = array();
    for($i = 1; $i < $numEmails+1; $i++)
    {
      $mailHeader = @imap_headerinfo($mbox, $i);
      $from = $mailHeader->fromaddress;
      $who = substr(strrchr($from, "<"), 1);
      $who = substr($who, 0, strlen($who)-1);
      if ($who == "") $who = $from;
      $message = strip_tags($mailHeader->subject);

//      $pid = pcntl_fork();
//      if($pid == -1)
//      {
//        die('could not fork');
//      }
//     if ($pid)
//        $pids[$i] = $pid;
//      else
//      {
        if ($message != "Mail delivery failed: returning message to sender")
          ask_howto();
//          exit(0);
//      }
    }
    imap_delete($mbox,'1:*');
    imap_expunge($mbox);
//    foreach($pids as $pid)
//    {
//      echo "Waiting for $pid\n";
//      pcntl_waitpid($pid, $status);
//    }
  }
  imap_delete($mbox,'1:*');
  imap_expunge($mbox);
  imap_close($mbox);
  echo ".";
//  sleep(30);
//}
?>
