<?php
require_once "pdo.php";
session_start();
// checks to see logged-in session
if ( !isset($_SESSION['email']) ) {
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
    // process SQL statement
    $sql = "INSERT INTO autos (make, model, year, mileage)
              VALUES (:make, :model, :year, :mileage)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage']
    ));
    // successful message
    $_SESSION['success'] = 'Record Added';
    header( 'Location: index.php' ) ;
    return;
}

?>

<html>
<head>
    <title>Samuel Adams</title>
</head>
<body>
<?php
// flash message error
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
};
?>
<p>Add A New Auto</p>
<form method="post">
    <p>Make:
        <input type="text" name="make" size="60"/>
    </p>
    <p>Model:
        <input type="text" name="model" size="60"/>
    </p>
    <p>Year:
        <input type="text" name="year"/>
    </p>
    <p>Mileage:
        <input type="text" name="mileage"/>
    </p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</form>
</body>
</html>
