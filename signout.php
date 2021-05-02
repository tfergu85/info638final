<?php
session_start();
require_once 'includes/header.php';
require_once 'includes/login.php';

$_SESSION = array();
session_destroy();
echo '<div class="logout">';
echo "<p>You are now logged out!</p>";
echo '</div>';

echo '<div class="navigation_login">';
echo "<a href=\"index.php\">Home</a>";
echo "<br>";
echo "<a href=\"searchartist.php\">Search Artists</a>";
echo "<br>";
echo "<a href=\"addartist.php\">Add an Artist</a>";
echo "<br>";
echo "<a href=\"addwork.php\">Add a Work</a>";
echo "<br>";
echo "<a href=\"addexhibition.php\">Add an Exhibition</a>";
echo "<br>";
echo "<a href=\"addschool.php\">Add a School</a>";
echo '</div>';

require_once 'includes/footer.php';
?>