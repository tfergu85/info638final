<?php
session_start(); 
require_once 'includes/header.php';
require_once 'includes/login.php';

if (isset($_POST['submit'])) { //check if the form has been submitted
	if ( empty($_POST['username']) || empty($_POST['password']) ) {
		echo "<p>Please fill out all of the form fields!</p>";
	} else {
		$conn = new mysqli($hn, $un, $pw, $db);
		if ($conn->connect_error) die($conn->connect_error);
		$username = sanitizeMySQL($conn, $_POST['username']);
		$password = sanitizeMySQL($conn, $_POST['password']);			
		$salt1 = "qm&h*";  
		$salt2 = "pg!@";  
		$password = hash('ripemd128', $salt1.$password.$salt2);
		$query  = "SELECT first_name, last_name FROM user WHERE username='$username' AND password='$password'"; 
		$result = $conn->query($query);    
		if (!$result) die($conn->error); 
		$rows = $result->num_rows;
		if ($rows==1) {
			$row = $result->fetch_assoc();
			$_SESSION['first_name'] = $row['first_name'];
			$_SESSION['last_name'] = $row['last_name'];	
			$goto = empty($_SESSION['goto']) ? 'index.php' : $_SESSION['goto'];			
			header('Location: ' . $goto);
			exit;	
		} else {
            echo '<div class="invalidlogin">';
			echo "<p>Invalid username/password combination!</p>";
            echo '</div>';
		}
	}
}
function sanitizeString($var)
{
	$var = stripslashes($var);
	$var = strip_tags($var);
	$var = htmlentities($var);
	return $var;
}
function sanitizeMySQL($connection, $var)
{
	$var = sanitizeString($var);
	$var = $connection->real_escape_string($var);
	return $var;
}
?>

<fieldset style="width:30%;"><legend>Log-in</legend>
<form method="POST" action="">
<label for="username">Username:</label>
<input type="text" name="username" size="40">
<label for="password">Password:</label>
<input type="password" name="password" size="40">
<input type="submit" name="submit" value="Log-In">
</form>
</fieldset>

<?php 
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

require_once 'includes/footer.php'; ?>