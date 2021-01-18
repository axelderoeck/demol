<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
  <script>
    window.addEventListener('load', function() {
      <?php
        /* DEZE FUNCTIE AANZETTEN VOOR FINAL VERSIE

        if(date('D') == 'Sun' || date('D') == 'Mon') {
          ?>stemKnop("aan");<?php
        } else {
          ?>stemKnop("uit");<?php
        }

        */
      ?>
      <?php
        if ($_SESSION["Voted"] == 0) {
          ?> stemKnop("aan"); <?php
        } elseif ($_SESSION["Voted"] == 1) {
          ?> stemKnop("uit"); <?php
        }
      ?>

    })
  </script>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

  <div class="infoDiv">
    <h1>DE MOL</h1>
    <p>Welkom <?php echo $_SESSION["Naam"]; ?></p>
    <p id="stemTekst"></p>
  </div>

  <div class="submitDiv">
    <button onclick="location.href = 'stemmen.php';" id="stemKnop" class="formSubmitBtn" type="submit">Stemmen</button>
  </div>

  <!-- JavaScript -->

  <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>

<?php mysqli_close($dbconn); ?>
</body>
</html>
