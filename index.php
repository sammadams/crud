<?php
require_once "pdo.php";
session_start();
?>

<html>
<head>
    <title>Samuel Adams</title>
</head>
<body>
    <h1>Welcome to the Automobiles Database</h1>

<?php

// flash message error
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
// flash message success
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

// populate table if logged in
if ( isset($_SESSION['email']) ){
    echo('<table border="1">');
    echo('<thead>');
    echo('<td>Make</td>');
    echo('<td>Model</td>');
    echo('<td>Year</td>');
    echo('<td>Mileage</td>');
    echo('</thead>');
    echo('<tbody>');
    $sql = "SELECT autos_id, make, model, year, mileage FROM autos";
    $stmt = $pdo->query($sql);
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        echo "<tr><td>";
        echo(htmlentities($row['make']));
        echo("</td><td>");
        echo(htmlentities($row['model']));
        echo("</td><td>");
        echo(htmlentities($row['year']));
        echo("</td><td>");
        echo(htmlentities($row['mileage']));
        echo("</td><td>");
        echo('<a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> / ');
        echo('<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a>');
        echo("</td></tr>\n");
    };
    echo('</tbody></table>');
} else {
    echo('<p><a href="login.php">Please log in</a></p>');
};

?>
<?PHP
if ( isset($_SESSION['email']) ){
    echo('<a href="add.php">Add New Entry</a><br>');
    echo('<a href="logout.php">Logout</a>');
}; 
?>
</body>
</html>