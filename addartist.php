<?php
session_start();

if (!isset($_SESSION['first_name']) || !isset($_SESSION['last_name']) ) {

$_SESSION['goto'] = basename($_SERVER['PHP_SELF']);
	if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
		$_SESSION['goto'] .= '?'.$_SERVER['QUERY_STRING'];	
	}
header("Location: signin.php");
}

require_once "includes/header.php";
require_once "includes/login.php";

echo "<h3>Welcome, ".$_SESSION['first_name']." ".$_SESSION['last_name'];
echo " | <small><a href=\"signout.php\">Logout</a></small></h3>";

$conn = new mysqli($hn, $un, $pw, $db);

if (isset($_POST['submit'])) { 
	if ((empty($_POST['artist_name'])) || (empty($_POST['artist_role'])) || (empty($_POST['geographic_region'])) || (empty($_POST['artist_nationality'])) || (empty($_POST['birthdate'])) || (empty($_POST['birthplace'])) ) {
		echo "<p>Please fill out all of the form fields!</p>";
	} else {
        
        if ($conn->connect_error) die($conn->connect_error);
        $artist_name = sanitizeMySQL($conn, $_POST['artist_name']);
		$artist_role = sanitizeMySQL($conn, $_POST['artist_role']);			
		$geographic_region = sanitizeMySQL($conn, $_POST['geographic_region']);
        $artist_nationality = sanitizeMySQL($conn, $_POST['artist_nationality']);
		$birthdate = sanitizeMySQL($conn, $_POST['birthdate']);
        $birthplace = sanitizeMySQL($conn, $_POST['birthplace']);      
        $query = "INSERT INTO artist VALUES (NULL, \"$artist_name\", \"$artist_role\", \"$geographic_region\", \"$artist_nationality\", \"$birthdate\", \"$birthplace\")";
        $result = $conn->query($query);
        
        
        if (!$result) {
            die ("Database access failed: " . $conn->error);
            } else {
            echo '<div class="addedartist">';
			echo "<p>Successfully added new artist: $artist_name!</p>";
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

$conn->close();
?>
    
<form method="post" action="addartist.php">
	<fieldset>
		<legend>Add an Artist:</legend>
		<label for="artist_name">Name:</label>
		<input type="text" name="artist_name" required><br> 
		<label for="artist_role">Role:</label> 
		<input type="text" name="artist_role" required><br>
		<label for="geographic_region">Geographic Region:</label> 
		<input type="text" name="geographic_region" required><br>
		<label for="artist_nationality">Nationality:</label> 
		<input type="text" name="artist_nationality" required><br>
        <label for="birthdate">Birthdate (Year):</label> 
		<input type="text" name="birthdate" required><br>
        <label for="birthplace">Birthplace:</label> 
		<input type="text" name="birthplace" required><br>
		<input type="submit" name="submit">
	</fieldset>
</form>

<?php
echo '<div class="yearformat">';
echo "<p>*Please enter the year in YYYY-MM-DD format (e.g., 1940-00-00)</p>";
echo '</div>';
?>

<?php
echo '<div class="navigation">';
echo "<a href=\"index.php\">Home</a>";
echo "<br>";
echo "<a href=\"searchartist.php\">Search Artists</a>";
echo "<br>";
echo "<a href=\"addwork.php\">Add a Work</a>";
echo "<br>";
echo "<a href=\"addexhibition.php\">Add an Exhibition</a>";
echo "<br>";
echo "<a href=\"addschool.php\">Add a School</a>";
echo '</div>';

require_once "includes/footer.php";
?>


    
