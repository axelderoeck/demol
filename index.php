<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

$meldingSoort = "succes";

$code = $_GET["code"];
if ($code==9) {
    $_SESSION["Id"] = NULL;
    $_SESSION["Naam"] = "";
    $_SESSION["Voted"] = 0;
    $_SESSION["Admin"] = 0;
    session_destroy();
    $meldingSoort = "succes";
    $foutmelding = "Je bent uitgelogd.";
}

if ($_SESSION["Id"] != NULL) {
  header('location:home.php');
}

if (isset($_POST["userLogin"])){
    //gegevens van de formfields ontvangen
    $naam = $_POST["Naam"];
    $wachtwoord = $_POST["Wachtwoord"];

    $sql = $dbconn->query("SELECT Id, Wachtwoord, Voted
                    FROM table_Users
                    WHERE Naam = '$naam'");

    if($sql->num_rows > 0) {
          $data = $sql->fetch_array();
          $id = ($data['Id']);
          $hasVoted = ($data['Voted']);
          if(password_verify($wachtwoord, $data['Wachtwoord'])){
            if($id == 7){
              $_SESSION["Id"] = $id;
              $_SESSION["Naam"] = $naam;
              $_SESSION["Voted"] = $hasVoted;
              $_SESSION["Admin"] = 1;
              $foutmelding = "";
              header('location:adminpanel.php');
            }elseif ($id <> NULL){
              //gebruiker is gevonden => aangemeld
              $_SESSION["Id"] = $id;
              $_SESSION["Naam"] = $naam;
              $_SESSION["Voted"] = $hasVoted;
              $_SESSION["Admin"] = 0;
              $foutmelding = "";
              header('location:home.php?code=7');
              }else{
              //gebruiker is niet gevonden => niet aangemeld
              $meldingSoort = "warning";
              $foutmelding = "Wachtwoord is niet correct!";
              }
          }
    } else {
      $meldingSoort = "warning";
      $foutmelding = "Wachtwoord is niet correct!";
    }
}

if (isset($_POST["userRegister"])){
    //waardes uit het formulier in lokale var steken
    $naam = $_POST["Naam"];
    $wachtwoord = $_POST["Wachtwoord"];
    $confirmWachtwoord = $_POST["confirmWachtwoord"];

    $sql = $dbconn->query("SELECT Naam
                    FROM table_Users
                    WHERE Naam = '$naam'");

    if($wachtwoord != $confirmWachtwoord){
        $meldingSoort = "warning";
        $foutmelding = "Het wachtwoord is niet bevestigd.";
    }else{
        $hash = password_hash($wachtwoord, PASSWORD_BCRYPT);
        $dbconn->query("INSERT INTO table_Users (Naam, Wachtwoord)
        VALUES ('$naam','$hash')");

        $selectNewId = $dbconn->query("SELECT Id
                        FROM table_Users
                        WHERE Naam = '$naam' AND Wachtwoord = '$hash'");

        $data = $selectNewId->fetch_array();
        $newId = ($data['Id']);

        for ($i=1; $i <= 10; $i++) {
          $dbconn->query("INSERT INTO table_Scores (UserId, Naam, Identifier, Score)
          VALUES ('$newId','$naam','person$i',0)");
        }
        $meldingSoort = "succes";
        $foutmelding = "Account is aangemaakt.";
    }
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <script>
  window.addEventListener('load', function() {
    <?php
      $pageRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) &&($_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ||  $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache');
      if($pageRefreshed == 1){
        echo "showNotification('$foutmelding','$meldingSoort');"; //message + color style
      }
      if ($code == 9) {
        echo "showNotification('$foutmelding','$meldingSoort');";
      }
    ?>
  })
  </script>
</head>
<body>

  <div id="informationPopup">
    <!-- Dynamische info -->
  </div>

  <div style="text-align: center; margin: 10% 0;">
    <img class="loginImg" src="img/assets/molLogo.png" alt="logo">
    <!--
    <h1>Jij weet <span>niets</span></h1>
    <h2>(Behalve dat je moet <span>inloggen</span>)</h2>
    <img style="width: 10%;" src="img/assets/arrow.png" alt=""> -->
  </div>
<!--
  <div class="gradient"></div>
-->
  <div id="loginbox">
            <div id="log">
                <form name="formLogin" action="" method="post">
                    <input placeholder="Naam" name="Naam" id="Naam" required>
                    <br>
                    <input placeholder="Wachtwoord" name="Wachtwoord" id="Wachtwoord" type="password" required>
                    <br>
                    <input type="submit" name="userLogin" id="userLogin" value="Login">
                </form>
                <p class="loginLink">Geen account? Klik <a href="javascript:openReg();">hier.</a></p>
            </div>
            <div id="reg">
                <form name="formRegister" action="" method="post">
                    <input placeholder="Naam" name="Naam" id="Naam" required>
                    <br>
                    <input placeholder="Wachtwoord" name="Wachtwoord" id="Wachtwoord" type="password" required>
                    <br>
                    <input placeholder="Wachtwoord" name="confirmWachtwoord" id="confirmWachtwoord" type="password" required>
                    <br>
                    <input type="submit" name="userRegister" id="userRegister" value="Register">
                    <br>
                </form>
                <p class="loginLink">Ga terug naar <a href="javascript:openReg();">login.</a></p>
            </div>
  </div>

  <!-- JavaScript -->

  <script type="text/javascript" src="js/scripts.js"></script>

<?php mysqli_close($dbconn); ?>
</body>
</html>
