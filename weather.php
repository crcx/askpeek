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

  $message = "";   $who = "";  $type = "none";  $subject = "";  $body = "";

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
      $message = strip_tags($mailHeader->subject);

      $pid = pcntl_fork();
      if($pid == -1)
      {
        die('could not fork');
      }
      if ($pid)
        $pids[$i] = $pid;
      else
      {
        if ($message != "Mail delivery failed: returning message to sender")
          ask_weather();
      }
    }
    imap_delete($mbox,'1:*');
    imap_expunge($mbox);
    foreach($pids as $pid)
    {
      pcntl_waitpid($pid, $status);
    }
  }
  imap_close($mbox);
?>
