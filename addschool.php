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
	if ((empty($_POST['school_name'])) || (empty($_POST['school_location']))) {
		echo "<p>Please fill out all of the form fields!</p>";
	} else {
        
        if ($conn->connect_error) die($conn->connect_error);
        $school_name = sanitizeMySQL($conn, $_POST['school_name']);
		$school_location = sanitizeMySQL($conn, $_POST['school_location']);			      
        $query = "INSERT INTO school VALUES (NULL, \"$school_name\", \"$school_location\")";
        $result = $conn->query($query);
        
        
        if (!$result) {
            die ("Database access failed: " . $conn->error);
            } else {
            echo '<div class="addedschool">';
			echo "<p>Successfully added new school: $school_name!</p>";
            echo "<p>The School ID is: $conn->insert_id </p>";
            echo "<p>Please fill out the next form!</p>";
            echo '</div>';
		}
	}

}
if (isset($_POST['submit_2'])) { 
	if ((empty($_POST['artist_id'])) || (empty($_POST['school_id']))) {
		echo "<p>Please fill out all of the form fields!</p>";
	} else {
        
        if ($conn->connect_error) die($conn->connect_error);
        $artist_id = sanitizeMySQL($conn, $_POST['artist_id']);
		$school_id = sanitizeMySQL($conn, $_POST['school_id']);			      
        $query2 = "INSERT INTO artist_to_school VALUES (NULL, \"$artist_id\", \"$school_id\")";
        $result2 = $conn->query($query2);
        
        
        if (!$result2) {
            die ("Database access failed: " . $conn->error);
            } else {
            echo '<div class="addedschooltoartist">';
			echo "<p>Successfully added a school to an artist!</p>";
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
  
<form method="post" action="addschool.php">
	<fieldset>
		<legend>Add a School:</legend>
		<label for="school_name">School:</label>
		<input type="text" name="school_name" required><br> 
		<label for="school_location">Location:</label> 
		<input type="text" name="school_location" required><br>
		<input type="submit" name="submit">
	</fieldset>
</form>
<br>
<form method="post" action="addschool.php">
	<fieldset>
        <legend>Add a School to an Artist:</legend>
        <label for="artist_id">Artist ID:</label> 
		<select name="artist_id" id="artist_id">
        <?php
        $query3 = "SELECT artist_id, artist_name FROM artist";
        $result3 = $conn->query($query3);
        while ($rows = $result3->fetch_assoc()){
        $artist_id = $rows['artist_id'];
        $artist_name = $rows['artist_name'];
        echo "<option value ='$artist_id'>$artist_id $artist_name</option>";
        }
        ?>
        </select>
        <br>
        <label for="school_id">School ID:</label> 
		<select name="school_id" id="school_id">
        <?php
        $query4 = "SELECT school_id, school_name FROM school";
        $result4 = $conn->query($query4);
        while ($rows = $result4->fetch_assoc()){
        $school_id = $rows['school_id'];
        $school_name = $rows['school_name'];
        echo "<option value ='$school_id'>$school_id $school_name</option>";
        }
        $conn->close();    
        ?>
        </select>
        <br>
		<input type="submit" name="submit_2">
	</fieldset>
</form>


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
echo "<a href=\"addexhibition.php\">Add an Exhibition</a>";
echo '</div>';

require_once "includes/footer.php";
?>