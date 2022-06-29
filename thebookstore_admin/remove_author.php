<?php
require_once "pdo.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TB | Delete User</title>
</head>
<body>

  <nav id="navbar" class="nav">
      <ul class="nav-list">
          <li><a href="account_management.php">Account Management</a></li>
          <li><a href="library_management.php">Library Management</a></li>
          <li><a href="rentals.php">Rentals</a></li>
          <li><a href="index.php">Log Out</a></li>   
      </ul>
    </nav>

  <?php
    if ( isset($_POST['delete']) && isset($_POST['A_B_ID']) ) {
      $sql = "DELETE FROM Authors_Books WHERE Authors_Books_ID like :zip";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(':zip' => $_POST['A_B_ID']));
      $_SESSION['success'] = 'Record deleted';
      header( 'Location: library_management.php' ) ;
      return;
    }

    // Guardian: Make sure that Account_ID is present
    if ( ! isset($_GET['A_B_ID']) ) {
      $_SESSION['error'] = "Missing Authors_Books_ID";
      header('Location: library_management.php');
      return;
    }

    $stmt = $pdo->prepare(
      "SELECT concat(a.First_Name, ' ', a.Last_Name) AS Author, b.Title AS Book, Authors_Books_ID FROM Authors_Books ab
      JOIN authors a
        ON a.Author_ID = ab.Author_FK
      JOIN books b
        ON b.Book_ID = ab.Book_FK
      WHERE Authors_Books_ID = :xyz");
    $stmt->execute(array(":xyz" => $_GET['A_B_ID']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
      $_SESSION['error'] = 'Bad value for Authors_Books_ID';
      header( 'Location: library_management.php' ) ;
      return;
    }
  ?>
  <p>Confirm: Removing <?= htmlentities($row['Author']) . " from " . htmlentities($row['Book'])?></p>

  <form method="post">
  <input type="hidden" name="A_B_ID" value="<?= $row['Authors_Books_ID'] ?>">
  <input type="submit" value="Delete" name="delete">
  <a href="library_management.php">Cancel</a>
  </form>

</body>
</html>
