<?php
require_once "includes/header.php";
require_once "includes/login.php";

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

echo '<div class="about">';
echo "<p>Welcome to Modern Women Artists: Africa, Asia, & Latin America! This site houses basic, biographical data about modern women artists from Africa, Asia, and Latin America. Within the field of art history, the term “modern” can typically be applied to art produced between the late 19th century and the 1970s. Modern art, which can be characterized by its rejection of academic art, encompasses numerous styles and movements. A general Google search for “modern artists” will mostly return the names of male artists such as Pablo Picasso, Jackson Pollock, Vincent van Gogh, and Salvador Dalí, to name a few. While the search “modern women artists” yields more results for women artists, the artists who are highlighted tend to be either European or American. The primary aim of this site is to challenge the male-dominated, Euro-American historiography of modern art. While the field of global feminist art history develops, users can add to the database as information about formerly unknown artists comes to light.
</p>";
echo '</div>';

$query = "SELECT artist_id, artist_name, geographic_region FROM artist ORDER BY geographic_region";
$result = $conn->query($query);
if (!$result) die ("Database access failed: " . $conn->error);

echo '<div class="maintable">';
echo "<table><tr><th>ID</th><th>Artist Name</th><th>Geographic Region</th></tr>";
while ($row = $result->fetch_assoc()) {
	echo "<tr>";
	echo "<td>".$row["artist_id"]."</td><td>".$row["artist_name"]."</td><td>".$row["geographic_region"]."</td>";
	echo "</tr>";
}
echo "</table>";
echo '</div>'; 
   

$conn->close();


echo '<div class="navigation_index">';
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


require_once "includes/footer.php";
?>


