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

// flash message success
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
// populate table if logged in
if ( isset($_SESSION['name']) ){
    echo('<table border="1">'."\n");
    $stmt = $pdo->query("SELECT name, email, password, user_id FROM users");
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        echo "<tr><td>";
        echo(htmlentities($row['name']));
        echo("</td><td>");
        echo(htmlentities($row['email']));
        echo("</td><td>");
        echo(htmlentities($row['password']));
        echo("</td><td>");
        echo('<a href="edit.php?user_id='.$row['user_id'].'">Edit</a> / ');
        echo('<a href="delete.php?user_id='.$row['user_id'].'">Delete</a>');
        echo("</td></tr>\n");
    }
} else {
    echo('<p><a href="login.php">Please log in</a></p>');
};

?>

</table>
<a href="add.php">Add New</a>
