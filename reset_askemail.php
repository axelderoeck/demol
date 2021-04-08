<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

include "includes/settings.php";
include "includes/functions.php";

if (isset($_POST["sendMail"])){
  // get email from form
  $email = $_POST["email"];

  $sql = $dbconn->query("SELECT Id
                  FROM table_Users
                  WHERE Email = '$email'");

  if($sql->num_rows > 0) {
    // IF user found -> set values
    $data = $sql->fetch_array();
    $id = ($data['Id']);

    // set a random string as security measure
    $randomGeneratedString = generateRandomString(15);
    $_SESSION["sessionString"] = $randomGeneratedString;

    // send mail
    $subject = "Wachtwoord reset";
    $message = "Klik op de onderstaande link om je wachtwoord opnieuw in te stellen. \n
    https://aksol.be/demol/reset_password.php?u=$id&s=$randomGeneratedString";
    $headers = "From: mail@aksol.be";
    mail($email,$subject,$message,$headers);
  }else{
    // user is not found
    echo "Email is niet in gebruik.";
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

      <form method="post" action="">
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
