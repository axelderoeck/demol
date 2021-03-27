<?php
  // ALGEMENE SETTINGS
    $admins = array(7, 10); // array van alle admin's hun ID
    $stemmen_dag = 'Sun'; // dag waarop gestemd wordt
    $stemmen_uur = "2200"; // vanaf dit uur mag er gestemd worden
    date_default_timezone_set('Europe/Brussels'); // tijdzone

  // SPEL SETTINGS
    $seizoen_start = "03/21/2021"; // maand/dag/jaar   echte: 03/21/2021
    $seizoen_eind = "05/23/2021"; // maand/dag/jaar    echte: 05/23/2021
    $aantal_kandidaten = 10; // hoeveel kandidaten in het spel
    $top_aantal = 20; // algemene ranglijst top ... tonen

  // STYLE VERSIES - CACHING
    $styleversion = 2; // versie van css als ik dit wil forceren te updaten bij iedereen

  // ALERT SETTINGS
    $bericht = true;
    $melding = (object) [
      'soort' => 'info', // 'info' = blauw, 'succes' = groen, 'warning' = rood
      'tekst' => "Voor degenen die nog geen 'deelnemer' award hebben gekregen. De volgende keer dat jullie stemmen zal dit automatisch op je profiel komen. <br/>Hoe behaal je die andere awards? Dat is nog een geheim."
    ];

  // AWARDS SETTINGS
    // VAST
      $award_weetniets = 5;
      $award_tunnelvisie = 8;
      $award_allin = 9;
      $award_gilles = 10;
    // JAARLIJKS - elk jaar aanpassen, nieuwe edities maken
      $award_winnaar = 1;
      $award_topper = 2;
      $award_deelnemer = 4;

?>
