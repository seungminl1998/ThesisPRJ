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
```
The code above is the PHP code to connect with the database to interact with it to update the caption list of the authenticated user. We always put the input box and the button inside a form tag. This time we also created a form tag and put an input box and a button in it. When the user clicks the add caption button, the first if statement will run. By using the isset function we can know which button was clicked in the page. So when the add caption button is clicked, the first if statement will run and it will request the value of the input box using $_REQUEST. The user will write the caption idea inside the input box. After getting the value, a query will be made to the database which will insert the caption into the corresponding column. The $conn->query is the function useto execute the database query from PHP.

```php
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
After the user adds a caption, the users will also be able to delete it. The reason why I made a caption bag in the web application was because the project's goal is to make Instagram Influencer's planning process and analyzing process easier. In order to make the planning process easier or more comfortable the users must be able to do everything in just one application. Before users had to write their captions in the phone notes application. We wanted to make this available in the application so that users could enter and access the captions anywhere they were. If we do not add a delete function, 
