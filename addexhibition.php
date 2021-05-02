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
	if ((empty($_POST['artist_id'])) || (empty($_POST['exhibition_name'])) || (empty($_POST['exhibition_date'])) || (empty($_POST['venue_name'])) || (empty($_POST['venue_location']))) {
		echo "<p>Please fill out all of the form fields!</p>";
	} else {
        
        if ($conn->connect_error) die($conn->connect_error);
        $artist_id = sanitizeMySQL($conn, $_POST['artist_id']);
        $exhibition_name = sanitizeMySQL($conn, $_POST['exhibition_name']);
		$exhibition_date = sanitizeMySQL($conn, $_POST['exhibition_date']);			
		$venue_name = sanitizeMySQL($conn, $_POST['venue_name']);
        $venue_location = sanitizeMySQL($conn, $_POST['venue_location']);     
        $query = "INSERT INTO exhibition VALUES (NULL, \"$artist_id\", \"$exhibition_name\",\"$exhibition_date\", \"$venue_name\",\"$venue_location\")";
        $result = $conn->query($query);
        
        
        if (!$result) {
            die ("Database access failed: " . $conn->error);
            } else {
            echo '<div class="addedexhibition">';
			echo "<p>Successfully added new exhibition: $exhibition_name!</p>";
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

<form method="post" action="addexhibition.php">
	<fieldset>
		<legend>Add an Exhibition:</legend>
        <label for="artist_id">Artist ID:</label> 
		<select name="artist_id" id="artist_id">
        <?php
        $query2 = "SELECT artist_id, artist_name FROM artist";
        $result2 = $conn->query($query2);
        while ($rows = $result2->fetch_assoc()){
        $artist_id = $rows['artist_id'];
        $artist_name = $rows['artist_name'];
        echo "<option value ='$artist_id'>$artist_id $artist_name</option>";
        }
        $conn->close();
        ?>
        </select>
        <br>
		<label for="exhibition_name">Name:</label> 
		<input type="text" name="exhibition_name" required><br>
		<label for="exhibition_date">Date (Year):</label> 
		<input type="text" name="exhibition_date" required><br>
		<label for="venue_name">Venue:</label> 
		<input type="text" name="venue_name" required><br>
        <label for="venue_location">Location:</label> 
		<input type="text" name="venue_location" required><br>
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
echo "<a href=\"addartist.php\">Add an Artist</a>";
echo "<br>";
echo "<a href=\"addwork.php\">Add a Work</a>";
echo "<br>";
echo "<a href=\"addschool.php\">Add a School</a>";
echo '</div>';

require_once "includes/footer.php";
?>