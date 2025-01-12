<?php

// Initialize a new session
ob_start();
session_start();

// Include the configuration file
include 'includes/config.php';
// Include functions
include 'includes/functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();

if (isset($_POST["sendMail"])){

  // get account where email is attached to
  $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Email = ?');
  $stmt->execute([ $_POST["email"] ]);
  $account = $stmt->fetch(PDO::FETCH_ASSOC);

  // Account exists -> create key and send mail
  if($account){
    // Set a random string as security measure
    $random = generateRandomString(15);
    $username = $account["Username"];
    $id = $account["Id"];
    $email = $account["Email"];

    // Link random string with user
    $stmt = $pdo->prepare('UPDATE table_Users SET UserKey = ? WHERE Id = ?');
    $stmt->execute([ $random, $id ]);

    // Set mail values
    $subject = "De Mol: Wachtwoord Reset";
    $message = "Dag (vergeetachtige) mollenjager,\n\n
    Jouw gebruikersnaam is: $username\n\n
    Klik op de onderstaande link om je wachtwoord opnieuw in te stellen.\n
    https://aksol.be/demol/resetpassword.php?u=$id&s=$random\n\n
    *Heb jij dit niet aangevraagd? Geen probleem, dan kan je dit bericht gewoon negeren.";
    $headers = "From: mail@aksol.be";

    // Send the mail
    mail($email,$subject,$message,$headers);

    // Notify user
    $foutmelding = "Email is verzonden";
    $meldingSoort = "success";
  }else{
    // Notify user
    $foutmelding = "Email is niet in gebruik";
    $meldingSoort = "warning";
  }
}

?>

<?php include "includes/header.php"; ?>

  <h1>Wachtwoord vergeten</h1>

  <form method="post" action="">
    <p>Geef je email waar jouw account aan verbonden is.</p>
    <input placeholder="Email" type="text" name="email">
    <input value="Stuur" type="submit" name="sendMail" id="sendMail">
  </form>
  
  <p class="example">Als je geen email hebt toegevoegd aan je account zal je hier geen gebruik van kunnen maken.</p>
  <p class="example">Soms duurt het een paar minuten voor de email aankomt.</p>
  <p class="example">Komt de email niet aan? Check zeker je spam folder eens na. Probeer opnieuw als het na een paar minuten nog steeds niet is aangekomen.</p>

<?php include "includes/footer.php"; ?>