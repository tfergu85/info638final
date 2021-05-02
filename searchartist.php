<?php
require_once "includes/header.php";
require_once "includes/login.php";

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if (isset($_GET['submit'])) { 
	if (empty($_GET['artistname'])) {
		echo "<p>Please fill out all of the form fields!</p>";
	} else {
 
		$artistname = sanitizeMySQL($conn, $_GET['artistname']);
		$query = "SELECT artist_id, artist_name, artist_role, geographic_region, artist_nationality, EXTRACT(YEAR FROM `birthdate`), birthplace FROM artist WHERE artist_name LIKE '%$artistname%'";
		$result = $conn->query($query);
		if (!$result) {
			die ("Database access failed: " . $conn->error);
		} else {
			$rows = $result->num_rows;
			if ($rows) {
				echo "<h2>Results:</h2><h3>Artist Info.</h3><table><tr><th>ID</th><th>Artist Name</th><th>Role</th><th>Geographic Region</th><th>Nationality</th><th>Born</th><th>Birthplace</th></tr>";
				while ($row = $result->fetch_assoc()) {
					echo "<tr>";
					echo "<td>".$row["artist_id"]."</td><td>".$row["artist_name"]."</td><td>".$row["artist_role"]."</td><td>".$row["geographic_region"]."</td><td>".$row["artist_nationality"]."</td><td>".$row["EXTRACT(YEAR FROM `birthdate`)"]."</td><td>".$row["birthplace"]."</td>";
					echo "</tr>";
				}
				echo "</table>";
                } else {
                echo '<div class="noartists">';
				echo "<p>No artists with that name. Please search again!</p>";
                echo '</div>';
			} 
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

<?php
if (isset($_GET['submit'])) { 

$artistname = sanitizeMySQL($conn, $_GET['artistname']);
$query2 = "SELECT work_title, EXTRACT(YEAR FROM `work_date`),
medium, subject FROM work NATURAL JOIN artist WHERE artist_name LIKE '%$artistname%'ORDER BY EXTRACT(YEAR FROM `work_date`) ASC";
$result = $conn->query($query2);
if (!$result) {
			die ("Database access failed: " . $conn->error);
		} else {
			$rows = $result->num_rows;
			if ($rows) {
               echo "<h3>Works</h3><table><tr><th>Work Title</th><th>Date</th><th>Medium</th><th>Subject</th></tr>";
               while ($row = $result->fetch_assoc()) {
	           echo "<tr>";
	           echo "<td>".$row["work_title"]."</td><td>".$row["EXTRACT(YEAR FROM `work_date`)"]."</td><td>".$row["medium"]."</td><td>".$row["subject"]."</td>";
	           echo "</tr>";
                }
                echo "</table>";
                } else {
                echo '<div class="noworks">';
                echo "<p>No works associated with that name.</p>";
                echo '</div>';
			 } 
		  }
	}  
?>

<?php
if (isset($_GET['submit'])) { 

$artistname = sanitizeMySQL($conn, $_GET['artistname']);
$query3 = "SELECT exhibition_name, EXTRACT(YEAR FROM `exhibition_date`), venue_name, venue_location FROM exhibition NATURAL JOIN artist WHERE artist_name LIKE '%$artistname%'ORDER BY EXTRACT(YEAR FROM `exhibition_date`)";
$result = $conn->query($query3);
if (!$result) {
			die ("Database access failed: " . $conn->error);
		} else {
			$rows = $result->num_rows;
			if ($rows) {
                echo "<h3>Exhibitions</h3><table><tr><th>Name</th><th>Date</th><th>Venue</th><th>Location</th></tr>";
                while ($row = $result->fetch_assoc()) {
	            echo "<tr>";
	            echo "<td>".$row["exhibition_name"]."</td><td>".$row["EXTRACT(YEAR FROM `exhibition_date`)"]."</td><td>".$row["venue_name"]."</td><td>".$row["venue_location"]."</td>";
			    echo "</tr>";
				}
				echo "</table>";
                } else {
				echo '<div class="noexhibits">';
                echo "<p>No exhibitions associated with that name.</p>";
                echo '</div>';
			 } 
		  }
    }
?>

<?php
if (isset($_GET['submit'])) { 

$artistname = sanitizeMySQL($conn, $_GET['artistname']);
$query4 = "SELECT school_name, school_location FROM school INNER JOIN artist_to_school ON school.school_id = artist_to_school.school_id INNER JOIN artist ON artist_to_school.artist_id = artist.artist_id WHERE artist_name LIKE '%$artistname%'";
$result = $conn->query($query4);
if (!$result) {
			die ("Database access failed: " . $conn->error);
		} else {
			$rows = $result->num_rows;
			if ($rows) {
                echo "<h3>Education</h3><table><tr><th>School</th><th>Location</th></tr>";
                while ($row = $result->fetch_assoc()) {
	            echo "<tr>";
	            echo "<td>".$row["school_name"]."</td><td>".$row["school_location"]."</td>";
			    echo "</tr>";
				}
				echo "</table>";
                } else {
                echo '<div class="noschools">';
				echo "<p>No schools associated with that name.</p>";
                echo '</div>';
			} 
		  }
    }
echo "<br>";
$conn->close();
?>

<form method="get" action="searchartist.php">
	<fieldset>
		<legend>Search for an Artist</legend>
		<label for="artistname">Artist Name:</label>
		<input type="text" name="artistname" required><br> 
		<input type="submit" name="submit">
	</fieldset>
    </form>

<?php
echo '<div class="navigation">';
echo "<a href=\"index.php\">Home</a>";
echo "<br>";
echo "<a href=\"addartist.php\">Add an Artist</a>";
echo "<br>";
echo "<a href=\"addwork.php\">Add a Work</a>";
echo "<br>";
echo "<a href=\"addexhibition.php\">Add an Exhibition</a>";
echo "<br>";
echo "<a href=\"addschool.php\">Add a School</a>";
echo '</div>';


require_once "includes/footer.php";
?>

