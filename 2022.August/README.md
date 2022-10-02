<h1>August 2022, UPDATES</h1>
In this month's update, I made the hashtag search more interactable with the user. Moreover, I managed to finish the caption page which I could not finish in the last update. The users can now add captions to their captions list. One important to remember here is that all the users will have their own captions bag and their own tag bag.
First I will start explaining the caption page.

```php
<?php
	if(isset($_POST['text'])){
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
			// collect value of input field
			$data = $_REQUEST['caption'];
			$sql = "INSERT INTO `".$usernms.".caption` (caption) VALUES ('$data')";
			$conn->query($sql);
		}
	}
	if(isset($_POST['delb'])){
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// collect value of input field
			$data = $_REQUEST['rid'];
			// sql to delete a record
			$sql = "DELETE FROM `".$usernms.".caption` WHERE id='$data'";
			$conn->query($sql);
		}
	}
	$sql = "SELECT id, caption FROM `".$usernms.".caption`";
	$result = $conn->query($sql);
			
	if ($result->num_rows > 0) {
		?><div class = "captions"><?php
		$id = 1;
		while($row = $result->fetch_assoc()) {
		?><?php echo "<div class = 'thecs' ><b>" .$id. ".</b> " . $row["caption"]. "</div><div class = 'bts'><form class = 'thform' method = 'post'><input style = 'display:none;' name = 'rid' value = ". $row["id"] . "></input><button class = 'delete' name = 'delb'                                       onclick='clickHandlers(event)'>Delete</button></form></div>";?><?php
		$id+=1;
		}?></div><?php
	} else {
	echo "0 results";
	} 				
?>
```
