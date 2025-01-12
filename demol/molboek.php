<?php

require_once("includes/phpdefault.php");

?>

<?php include "includes/header.php"; ?>

      <h1>Mijn Molboek</h1>

      <?php if($_SESSION["Voted"] == 1): ?>
        <p class="example">Wil je nog een wijziging maken? Klik <a href="resetvote.php">hier</a> om opnieuw te stemmen.</p>
      <?php endif; ?>

      <h2>Jouw gespendeerde punten:</h2>
      <?php
      // Select all the scores the user has on the candidates and sort them from high to low
      $stmt = $pdo->prepare('SELECT table_Scores.Score, table_Candidates.Name, table_Candidates.Status
                            FROM table_Users
                            LEFT JOIN table_Scores
                            ON table_Users.Id = table_Scores.UserId
                            LEFT JOIN table_Candidates
                            ON table_Candidates.Id = table_Scores.CandidateId
                            WHERE table_Users.Id = ?
                            AND table_Candidates.Status = 1
                            ORDER BY table_Scores.score DESC');
      $stmt->execute([ $_SESSION["Id"] ]);
      $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Loop through all scores
      if(!empty($candidates)):
        $i = 0; foreach($candidates as $candidate): ?>
          <div style="animation-delay: <?=$i/4;?>s;" class="displayItem">
            <div>
              <?php if(file_exists("img/candidates/" . $candidate['Name'] . ".jpg")): ?>
                <img src="img/candidates/<?=$candidate['Name']?>.jpg" alt="foto van <?=$candidate['Name']?>" />
              <?php else: ?>
                <img src="img/candidates/unknown.jpg" alt="foto momenteel niet beschikbaar" />
              <?php endif; ?>
            </div>
            <span><?=$candidate['Name']?></span>
            <span><?=$candidate['Score']?></span>
          </div>
        <?php $i++; endforeach; ?>
      <?php else: ?>
        <h2>Je hebt nog niet gestemd deze week.</h2>  
      <?php endif; ?>

<?php include "includes/footer.php"; ?>
