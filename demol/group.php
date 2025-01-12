<?php

require_once("includes/phpdefault.php");

// Select all the group info from the url id
$stmt = $pdo->prepare('SELECT * FROM table_Groups WHERE Id = ?');
$stmt->execute([ $_GET["g"] ]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

// If group doesn't exist -> go back
if(empty($group)){
  header('location: groups.php');
}

// Select all the group members from the url id
$stmt = $pdo->prepare('SELECT DISTINCT * FROM table_UsersInGroups 
LEFT JOIN table_Users ON table_UsersInGroups.UserId = table_Users.Id 
WHERE GroupId = ? LIMIT 10');
$stmt->execute([ $group["Id"] ]);
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total score of all group members
$getGroupScore = $pdo->prepare('SELECT SUM(Score)
FROM table_Groups
LEFT JOIN table_UsersInGroups
ON table_Groups.Id = table_UsersInGroups.GroupId
LEFT JOIN table_Users
ON table_UsersInGroups.UserId = table_Users.Id
WHERE GroupId = ?');
$getGroupScore->execute([ $group["Id"] ]);
$groupScore = $getGroupScore->fetchColumn(0); 

// Get amount of group members
$getAmountMembers = $pdo->prepare('SELECT COUNT(UserId)
FROM table_Groups
LEFT JOIN table_UsersInGroups
ON table_Groups.Id = table_UsersInGroups.GroupId
WHERE GroupId = ?');
$getAmountMembers->execute([ $group["Id"] ]);
$groupMembers = $getAmountMembers->fetchColumn(0); 

// Check if current user is a member of this group
$stmt = $pdo->prepare('SELECT UserId FROM table_UsersInGroups WHERE UserId = ? AND GroupId = ?');
$stmt->execute([ $_SESSION["Id"], $_GET["g"] ]);
$ismember = $stmt->fetch(PDO::FETCH_ASSOC);
// If member exists in group -> set variable
if($ismember){
  $user_is_member = true;
}else{
  $user_is_member = false;
}

// check if the current profile is the group admin
if ($group["AdminId"] == $_SESSION["Id"]) {
  $user_is_admin = true;
}else{
  $user_is_admin = false;
}

if (isset($_POST["saveGroupSettings"])){
  // Check if something changed before executing function
  if($_POST["description"] != $group["Description"]){
    $notification = changeGroupDescription($group["Id"], $_POST["description"]);
  }
  if($_POST["private"] != $group["Private"]){
    $notification = changeGroupPrivacy($group["Id"], $_POST["private"]);
  }
  if($_POST["name"] != $group["Name"]){
    $notification = changeGroupName($group["Id"], $_POST["name"]);
  }
  // If no errors -> set general success message
  if($notification->type != 'warning'){
    $notification->type = "success";
    $notification->message = "Gegevens opgeslagen";
    // Refresh after 1 second to show the updated info
    header('Refresh:1');
  }
}

if (isset($_POST["joinGroup"])){
  $notification = addUserToGroup($_SESSION["Id"], $group["Id"]);
  // Refresh after 1 second to show the updated info
  header('Refresh:1');
}

if (isset($_POST["leaveGroup"])){
  $notification = deleteUserFromGroup($_SESSION["Id"], $group["Id"]);
  // Refresh after 1 second to show the updated info
  header('Refresh:1');
}

?>

<?php include "includes/header.php"; ?>

  <?php if($user_is_admin): ?>
  <a href="javascript:editMode('editscreen', true);">
    <div class="editbutton">
        <i class="fas fa-edit"></i>
    </div>
  </a>
  <?php endif; ?>

  <!-- User info -->
  <h1><?=$group["Name"]?></h1>
  <p><?=$group["Description"]?></p>
  <p>Deze groep is <?php if($group["Private"] == 0){echo "publiek.";}else{echo "privé.";} ?></p>
  <!-- <p>Score: <?=$groupScore?></p> -->
  <h3>Leden 
    <?php if($user_is_member && $group["Private"] == 1 || $group["Private"] == 0): ?>
      (<?=$groupMembers?>)
    <?php endif; ?>
  </h3>

  <?php if($group["Private"] == 0): ?>
    <?php if(!$user_is_member): ?>
      <form action="" method="post">
        <input type="submit" name="joinGroup" id="joinGroup" value="Aansluiten bij groep">
      </form>
    <?php endif; ?>
  <?php endif; ?>

  <?php if($user_is_member): ?>
    <form action="" method="post">
      <input type="submit" name="leaveGroup" id="leaveGroup" value="Groep verlaten">
    </form>
  <?php endif; ?>

  <?php if($user_is_admin): ?>
    <button onclick="location.href = 'addusertogroup.php?g=<?=$group["Id"]?>';" class="styledBtn" type="submit" name="button">Nodig spelers uit</button>
  <?php endif; ?>

  <div>
    <?php if($user_is_member && $group["Private"] == 1 || $group["Private"] == 0): ?>
      <?php if(!empty($members)): ?>
        <?php $i = 0; foreach($members as $member): ?>
          <a href="profile.php?u=<?=$member["Id"]?>">
            <div style="animation-delay: <?=$i/4;?>s;" class="displayUser">
              <div>
                <span><?=getVotedPoints($member["Id"]) + $member["Score"]?></span>
                <?php if($member["Screen"] == 0): ?>
                  <img src="img/assets/demol_logo_geen_tekst_groen.png" alt="de mol logo">
                <?php elseif($member["Screen"] == 1): ?>
                  <img src="img/assets/demol_logo_geen_tekst_rood.png" alt="de mol logo">
                <?php else: ?>
                  <img src="img/assets/demol_logo_geen_tekst.png" alt="de mol logo">
                <?php endif; ?>
              </div>
              <span><?=$member["Username"]?></span>
            </div>
          </a>
        <?php $i++; endforeach; ?>
      <?php else: ?>
        <p style="text-align: center !important;">Er zijn nog geen leden</p>
      <?php endif; ?>
    <?php else: ?>
      <p style="text-align: center !important;">Je kan geen leden bekijken van een privé groep.</p>
    <?php endif; ?>
  </div>

  <?php if($user_is_admin): ?>
    <div id="editscreen" class="editmenu">
      <a href="javascript:editMode('editscreen', false);">&times;</a>

      <form action="" method="post">
        <label>Naam</label>
        <input type="text" id="name" name="name" value="<?=$group["Name"]?>">
        <br>
        <label>Beschrijving</label>
        <input type="text" id="description" name="description" value="<?=$group["Description"]?>">
        <br>
        <label>Privé groep</label>
        <input style="border: 0;" type="text" readonly>
        <input type="hidden" name="private" value="0">
        <input <?php if($group['Private'] == 1) {echo 'checked';}; ?> type="checkbox" id="private" name="private" value="1" />
        <br>
        <input type="submit" name="saveGroupSettings" id="saveGroupSettings" value="Opslaan">
      </form>   
    </div>
  <?php endif; ?>

<?php include "includes/footer.php"; ?>
