<?php

require_once("includes/phpdefault.php");

$stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT SUM(Score) FROM table_Scores
LEFT JOIN table_Candidates
ON table_Scores.CandidateId = table_Candidates.Id
WHERE table_Candidates.Status = ? AND UserId = ?
GROUP BY UserId');
$stmt->execute([ 0, $_SESSION["Id"] ]);
$lostPoints = $stmt->fetchColumn(0);

$stmt = $pdo->prepare('SELECT * FROM table_Scores
LEFT JOIN table_Candidates
ON table_Scores.CandidateId = table_Candidates.Id
WHERE UserId = ? AND Score > 0');
$stmt->execute([ $_SESSION["Id"] ]);
$scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Dont allow the page on vote day before vote hour
if(date('D') == VOTE_DAY && date('Hi') < VOTE_HOUR) {
  header('location: home.php');
// Time is allowed ->
}else{
  // Check if user has seen results
  if($account["SeenResults"] == 0 && $account["Voted"] == 0){
    // Check if user has red screen
    $redScreen = false;
    foreach($scores as $score){
      if($score["Status"] == 0 && $score["Score"] > 0){
        $redScreen = true;
        break;
      }
    }

    // Set variables based on result
    if($redScreen == true){
      $file_name = "demol_logo_geen_tekst_rood.png";
      $color = "red";
      $text = "Tekst rood scherm";
      $screen = 2;
    }else{
      $file_name = "demol_logo_geen_tekst_groen.png";
      $color = "green";
      $text = "Tekst groen scherm";
      $screen = 1;
    }

    // Get name to type out in animation
    if($account["Name"] != null || $account["Name"] != ""){
      $firstname = $account["Name"];
    }else{
      $firstname = $account["Username"];
    }

    // Calculate the new score
    // Set default start score
    $newScore = $account["Score"];
    // Set default bonus start score
    $bonusScore = 0;
    // Set bonus true default
    $bonus = true;
    // Set counter to 0
    $count = 0;
    foreach($scores as $score){
      // Count how many candidates the user voted on
      if($score["Score"] > 0){
        $count++;
      }
      // If points are earned ->
      if($score["Status"] == 1 && $score["Score"] > 0){
        $multiplier = 2;
        $newScore += round($score["Score"] * $multiplier); 
      }
      // If user has a wrong score -> disable bonus
      if($score["Status"] == 0 && $score["Score"] > 0){
        $bonus = false;
      }
    }
    // Bonuses
    // If user only voted on 1 candidate
    if($count == 1){
      $bonusScore += round($newScore / 5);
    }
    //Award IDEA: Royal Flush -> IF more than .... points
    //Award IDEA: Name -> IF user voted on 10 candidates
    //Award IDEA: Name -> IF lost more than .... points

    // If user has less than 10 points set it back to 10
    if($newScore < 10){
      $newScore = 10;
    }

    if($bonus == true){
      $newScore += $bonusScore;
    }
        
    // Update users values
    $stmt = $pdo->prepare('UPDATE table_Users SET Score = ?, SeenResults = ?, LastScreen = ? WHERE Id = ?');
    $stmt->execute([ $newScore, 1, $screen, $account["Id"] ]);

    // Delete score table
    $stmt = $pdo->prepare('DELETE FROM table_Scores WHERE UserId = ?');
    $stmt->execute([ $account["Id"] ]);

  }else{
    header('location: home.php');
  }
}


?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
</head>

<body class="voteScreen">
  <img id="screenFingerprint" src="img/assets/<?=$file_name?>" alt="logo van de mol">
  <?php if($account["SeenResults"] == 0 && $account["Voted"] == 0): ?>
    <div id="screen_<?=$color?>" class="screen">
      <div class="respContainer" style="height: 100%;">
        <img class="mainImage" src="img/assets/<?=$file_name?>" alt="logo van de mol">
        <p><?=$text?></p>
        <?php if($account["Score"] > 0): ?>
        <p>Niet gebruikte punten: <?=$account["Score"]?></p>
        <?php endif; ?>
        <div class="slider-nav">
          <?php foreach($scores as $score): ?>
            <div class='candidate' id='candidate<?=$score['Id']?>'>
              <?php if($score["Status"] == 0): ?>
                <img class="red" src="img/assets/demol_logo_geen_tekst_rood.png" alt="logo de mol rood">
                <span>-<?=$score["Score"]?></span>
              <?php else: ?>
                <img class="green" src="img/assets/demol_logo_geen_tekst_groen.png" alt="logo de mol groen">
                <span>+<?=round($score["Score"]*$multiplier)?></span> 
              <?php endif; ?>
              <img src="img/kandidaten/<?=$score['Name']?>.jpg" alt="foto van <?=$score['Name']?>" />
            </div>
          <?php endforeach; ?>
        </div>

        <table>
          <?php if($bonus == true): ?>
            <tr>
              <td>Bonus</td>
              <td>+<?=$bonusScore?></td>
            </tr>
          <?php endif; ?>
            <tr>
              <td>Score</td>
              <td><?=$newScore?></td>
            </tr>
        </table>
        <button onclick="location.href = 'home.php';" class="styledBtn" type="submit">Ga door</button>
      </div>
    </div>
  <?php endif; ?>
      
  <div id="screenPage">
    <img src="img/assets/demol_logo.png" alt="logo de mol">
    <div id="textfield"></div>  
  </div>

  <!-- JavaScript -->
  <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
  <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>
  <!-- Slick.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!-- Typewriter.js -->
  <script type="text/javascript" src="https://unpkg.com/typewriter-effect@latest/dist/core.js"></script>

  <script type="text/javascript">
    $(document).ready(function(){
      // Start the screen animation
      screenAnimation('textfield','<?=$firstname?>','<?=$color?>');
      // Slick settings for candidate list
      $('.slider-nav').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: false,
        arrows: false,
        centerMode: true,
        centerPadding: '5%',
        focusOnSelect: true,
        draggable: true,
        mobileFirst: true,
        infinite: false
      });
    });
  </script>

</body>
</html>