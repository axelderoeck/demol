<!DOCTYPE html>
<html lang="nl">
<head>
    <!-- FAVICON -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
    <link rel="manifest" href="img/favicon/site.webmanifest">
    <link rel="mask-icon" href="img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">

    <!-- CSS -->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/theme<?php echo "V" . STYLE_VERSION; ?>.css">
    <link rel="stylesheet" href="css/style<?php echo "V" . STYLE_VERSION; ?>.css">
    <link rel="stylesheet" href="css/navigation<?php echo "V" . STYLE_VERSION; ?>.css">
    <link rel="stylesheet" href="css/desktop<?php echo "V" . STYLE_VERSION; ?>.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

    <!-- FONTAWESOME -->
    <script src="https://kit.fontawesome.com/90f9e5d42f.js" crossorigin="anonymous"></script>

    <!-- EXTERNAL SCRIPTS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment.min.js"></script>

    <!-- META -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>De Mol</title>

    <!-- NOTIFICATION LOAD -->
    <script>
        window.addEventListener('load', function() {
            <?php
            $pageRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) &&($_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ||  $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache');
            if($pageRefreshed == 1){
                echo "showNotification('$notification->message','$notification->type');"; //message + color style
            }
            ?>
        })
    </script>
</head>
<body>
    <?php
    $enddate = new DateTime(SEASON_END);
    $now = new DateTime();

    // Get all notifications for logged in user
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM table_Notifications WHERE InvitedId = ?');
    $stmt->execute([ $_SESSION["Id"] ]);
    $amount_notifications = $stmt->fetchColumn(0);
    ?>

    <!-- Navigation -->
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="fas fa-times closeIcon"></i></a>
        <a href="home.php"><i class="fas fa-home"></i>Home</a>
        <a href="molboek.php"><i class="fas fa-fingerprint"></i>Molboek</a>
        <a href="info.php"><i class="fas fa-question-circle"></i>Uitleg</a>
        <a href="profile.php?u=<?=$_SESSION['Id']?>"><i class="fas fa-user"></i>Profiel</a>
        <a href="friends.php"><i style="transform: translateX(-5px);" class="fas fa-users"></i>Vrienden</a>
        <a href="groups.php"><i style="transform: translateX(-5px);" class="fas fa-users"></i>Groepen</a>
        <a href="statistics.php"><i class="fas fa-chart-bar"></i>Statistieken</a>
        <a href="notifications.php"><i class="fas fa-bell"><span><?=$amount_notifications?></span></i>Meldingen</a>
        <?php if ($_SESSION["Admin"] == 1): ?>
            <a href="admin/index.php"><i class="fas fa-hammer"></i>Admin</a>
        <?php endif; ?>
        <a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i>Uitloggen</a>
        <img src="img/assets/molLogo.png" alt="logo de mol">
    </div>
    <span class="navButton" onclick="openNav()"><i class="fas fa-stream"></i></span>

    <!-- Dynamic popup -->
    <div id="informationPopup"></div>
    
    <!-- Main Page -->
    <div id="main">
        <div class="respContainer">
