<?php
require_once "pdo.php";
session_start();

// checks to see logged-in session
if ( !isset($_SESSION['email']) ) {
    die("ACCESS DENIED");
};

// data validation on entries
if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage']) && isset($_POST['autos_id']) ) {
    // check all fields
    if ( empty($_POST['make']) || empty($_POST['model']) || empty($_POST['year']) || empty($_POST['mileage']) ){
        $_SESSION['error'] = 'All fields are required';
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    };
    // numeric year
    if ( !is_numeric($_POST['year']) ) {
        $_SESSION['error'] = 'Year must be numeric';
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    };
    // numeric mileage
    if ( !is_numeric($_POST['mileage']) ) {
        $_SESSION['error'] = 'Mileage must be numeric';
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
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
};

// Guardian: Make sure that auto_id is present
if ( ! isset($_GET['autos_id']) ) {
  $_SESSION['error'] = "Missing autos_id";
  header('Location: index.php');
  return;
};

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header( 'Location: index.php' ) ;
    return;
};

// set variables for DB info
$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$year = htmlentities($row['year']);
$mileage = htmlentities($row['mileage']);
$autos_id = $row['autos_id'];
?>

<html>
<head>
    <title>Samuel Adams</title>
</head>
<body>
<h1>Edit Auto</h1>
<?PHP
// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
};

?>
<form method="post">
<p>Make:
<input type="text" name="make" value="<?= $make ?>"></p>
<p>Model:
<input type="text" name="model" value="<?= $model ?>"></p>
<p>Year:
<input type="text" name="year" value="<?= $year ?>"></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= $mileage ?>"></p>
<input type="hidden" name="autos_id" value="<?= $autos_id ?>">
<p><input type="submit" value="Save"/>
<a href="index.php">Cancel</a></p>
</form>
</body>
</html>