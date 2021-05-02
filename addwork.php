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
	if ((empty($_POST['artist_id'])) || (empty($_POST['work_title'])) || (empty($_POST['work_date'])) || (empty($_POST['medium'])) || (empty($_POST['subject']))) {
		echo "<p>Please fill out all of the form fields!</p>";
	} else {
        
        if ($conn->connect_error) die($conn->connect_error);
        $artist_id = sanitizeMySQL($conn, $_POST['artist_id']);
        $work_title = sanitizeMySQL($conn, $_POST['work_title']);
		$work_date = sanitizeMySQL($conn, $_POST['work_date']);			
		$medium = sanitizeMySQL($conn, $_POST['medium']);
        $subject = sanitizeMySQL($conn, $_POST['subject']);     
        $query = "INSERT INTO work VALUES (NULL, \"$artist_id\", \"$work_title\",\"$work_date\", \"$medium\",\"$subject\")";
        $result = $conn->query($query);
        
        
        if (!$result) {
            die ("Database access failed: " . $conn->error);
            } else {
            echo '<div class="addedwork">';
			echo "<p>Successfully added new work: $work_title!</p>";
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

<form method="post" action="addwork.php">
	<fieldset>
		<legend>Add a Work:</legend>
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
		<label for="work_title">Title:</label> 
		<input type="text" name="work_title" required><br>
		<label for="work_date">Date (Year):</label> 
		<input type="text" name="work_date" required><br>
		<label for="medium">Medium:</label> 
		<input type="text" name="medium" required><br>
        <label for="subject">Subject:</label> 
		<input type="text" name="subject" required><br>
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
echo "<a href=\"addexhibition.php\">Add an Exhibition</a>";
echo "<br>";
echo "<a href=\"addschool.php\">Add a School</a>";
echo '</div>';

require_once "includes/footer.php";
?>
    
