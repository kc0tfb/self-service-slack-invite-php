<?php
// KC0TFB's slack.com self-signup handler
// Site-specific variables: Put yours here or in 'vars.php'

// $TOKEN    ='xoxp-XXXXXXXXXXX-XXXXXXXXXXX-XXXXXXXXXXX-XXXXXXXXXX'; // AUTH token for an admin (not owner) account
// $SUBDOMAIN='IMissedAllTheShortSubdomainsSoNowIHaveALongOne'; // .slack.com
// $TITLE    ='Join my hoopy feeb blurgh Slack.';
// $NEXTSTEP ='<p>Now <a href="http://kittenblog.example.com">go back to my kitten blog.</a></p>'; // Say this after signup

include('vars.php'); // override the above

?><html><head><title><?php echo $TITLE; ?></title>
<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'><style type="text/css">
h2 { font-family: 'Roboto', sans-serif; color: #fff; }
p { font-family: 'Roboto', sans-serif; color: #fff; }
p.err { color: #a44; }
p.ok { color: #7a7; }
input.text { width: 250px; }
form { margin: 0 auto; }
body { color: #fff; background-color: #252525; width: 100%; background-image: url(bg.jpg); }
</style></head><body><div style="text-align: center; padding: 20px; background-color: #252525;"><h2><?php echo $TITLE; ?></h2>
<?php

if (empty($TOKEN) | empty($SUBDOMAIN) | empty($TITLE) | empty($NEXTSTEP)) {
  scold("This script is not fully configured. Oops!");
} else {
  if (empty($_POST)) {
    showForm(); // Show a blank form
  } else {
    if (handlePost($SUBDOMAIN,$TOKEN)) echo $NEXTSTEP;
  }
} // all functions below

function scold($m){ // message
  echo '<p class="err" > '.$m." </p>\n";
}

function sendForm($f,$l,$e,$s,$t){ // first, last, email, subdomain, token
  $slackInviteUrl='https://'.$s.'.slack.com/api/users.admin.invite?t='.time();
  $fields = array(
    'email' => urlencode($e),
    'first_name' => urlencode($f),
    'last_name' => urlencode($l),
    'token' => $t,
    'set_active' => urlencode('true'),
    '_attempts' => '1'
  );

// url-ify the data for the POST
  $fields_string='';
  foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
  rtrim($fields_string, '&');

  $ch = curl_init();
  curl_setopt($ch,CURLOPT_URL, $slackInviteUrl);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch,CURLOPT_POST, count($fields));
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  $replyRaw = curl_exec($ch);
  curl_close($ch);
  $reply=json_decode($replyRaw,true);
  if($reply['ok']==false) {
    echo '<p class="err">Something went wrong. <a href="javascript:history.go(-1)">Try again</a> or ask for help.</p>'."\n";
    return(false);
  }
  echo '<p class="ok">Invited successfully. Check your email. It should arrive within a couple minutes.</p>'."\n";
  return(true);
} // sendForm

function showForm($f="",$l="",$e=""){ // first, last, email
  echo "\n".'<form method="post">'."\n".'<p> First Name <input type="text" name="first" value="'.$f;
  echo '" /></p>'."\n".'<p> Last Name <input type="text" name="last" value="'.$l;
  echo '" /></p>'."\n".'<p> Email <input type="text" name="email" value="'.$e;
  echo '" /></p>'."\n".'<p> <input type="submit" value="Sign me up!" /> </p>'."\n</form>\n";
} // showForm

function handlePost($s,$t){ // subdomain, token
  $err=0; $first=""; $last=""; $email="";
  $sff = (FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH); // string filter flags

  if (empty($_POST['first'])) { $err=1; scold("Empty first name.");    } else { $first=(filter_var($_POST['first'], FILTER_SANITIZE_STRING, $sff)); }
  if (empty($_POST['last']))  { $err=1; scold("Empty last name.");     } else {  $last=(filter_var($_POST['last'],  FILTER_SANITIZE_STRING, $sff)); }
  if (empty($_POST['email'])) { $err=1; scold("Empty email address."); } else { $email=(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)); }
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { $err=1; scold("Email address seems invalid."); }

  echo "\n\n<!--\nDEBUG:\nVAR:Sanitized:Raw:\n";
  echo "First:".$first.":".$_POST['first'].":\n";
  echo "Last:".$last.":".$_POST['last'].":\n";
  echo "Email:".$email.":".$POST['email'].":\n\n--!>\n";

  if ($err > 0) {
    scold("Please fill in both name fields and provide a valid email address.");
    showForm($first,$last,$email);
    return(false);
  }
  return(sendForm($first,$last,$email,$s,$t));
} // handlePost

// end of functions, back to static page content.
?></div>
</body>
</html>
