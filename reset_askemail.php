<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

include "includes/settings.php";
include "includes/functions.php";

if (isset($_POST["sendMail"])){
  $randomGeneratedString = generateRandomString(10);

  $email = $_POST["email"];
  $subject = "Wachtwoord reset";
  $message = "Jouw nieuwe wachtwoord is $randomGeneratedString.\n
  Log-in en stel je nieuwe wachtwoord in op: \n
  Profiel -> Account Acties -> Wachtwoord veranderen.";
  $headers = "From: mail@aksol.be";

  $status = mail($email,$subject,$message,$headers);

  if ($status) {
    echo "Yes";
  }else{
    echo "No";
  }
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
</head>
<body>

  <div id="main">
    <div class="respContainer">
      <h1>Wachtwoord vergeten</h1>

      <form method="post" action="reset_send_link.php">
        <p>Geef je email waar jouw account aan verbonden is</p>
        <input placeholder="Email" type="text" name="email">
        <input value="Stuur" type="submit" name="sendMail" id="sendMail">
      </form>
    </div>
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
  <?php mysqli_close($dbconn); ?>
</body>
</html>
