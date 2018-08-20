<?php
require_once "pdo.php";
session_start();

// checks to see logged-in session
if ( !isset($_SESSION['name']) ) {
    die("ACCESS DENIED");
};

// check for all fields
if ( empty($_POST['make']) || empty($_POST['model']) || empty($_POST['year']) || empty($_POST['mileage']) ){
    $_SESSION['error'] = 'All fields are required';
    header("Location: add.php");
    return;
};

// data validation on entries
if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage']) {
    // numeric year
    if ( !is_numeric($_POST['year']) ) {
        $_SESSION['error'] = 'Year must be numeric';
        header("Location: add.php");
        return;
    };
    // numeric mileage
    if ( !is_numeric($_POST['mileage']) ) {
        $_SESSION['error'] = 'Mileage must be numeric';
        header("Location: add.php");
        return;
    };

    $sql = "UPDATE autos SET make = :make,
            model = :model, year = :year, mileage = :mileage
            WHERE autos_id = :autos_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':autos_id' => $_POST['autos_id']));
    // set success message
    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that auto_id is present
if ( ! isset($_GET['autos_id']) ) {
  $_SESSION['error'] = "Missing autos_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM users where user_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['user_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for user_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
};

// set variables for DB info
$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$year = htmlentities($row['year']);
$mileage = htmlentities($row['mileage']);
$user_id = $row['user_id'];
?>
<html>
<head>
    <title>Samuel Adams</title>
</head>
<body>
<p>Edit Auto</p>
<form method="post">
<p>Make:
<input type="text" name="make" value="<?= $make ?>"></p>
<p>Model:
<input type="text" name="model" value="<?= $model ?>"></p>
<p>Year:
<input type="text" name="year" value="<?= $year ?>"></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= $mileage ?>"></p>
<input type="hidden" name="user_id" value="<?= $user_id ?>">
<p><input type="submit" value="Update"/>
<a href="index.php">Cancel</a></p>
</form>
</body>
</html>
