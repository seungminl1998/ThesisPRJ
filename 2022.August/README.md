<h1>August 2022, UPDATES</h1>
In this month's update, I made the hashtag search more interactable with the user. Moreover, I managed to finish the caption page which I could not finish in the last update. The users can now add captions to their captions list. One important to remember here is that all the users will have their own captions bag and their own tag bag.

<h2>Amount of money used until now</h2>

| ITEM | COST |
|  :---: |  :---: |
| Domain  | ₩ 20,000  |
| SSL  | ₩ 48,000  |
| Hosting Service  | ₩ 160,000  |
| Google Trends API  | ₩ 7,000 per Month  |
| **TOTAL**  | **₩ 242,000**  |

	I cancelled the previous API and subscribed to a new Hashtag API

<h3>Captions Page</h3>
The captions page is the last page I made where the users will be able to see their saved captions. In this page the users will be able to add and delete their caption ideas to be added in their future posts.

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
the list could become too large hence is the reason why we added a delete button. Each time a the user adds a caption, the caption page list will add a row of caption with a delete button by its side. As it can be seen in the code, we make a query to select all the captions in the database to show it in the user interface. 

```php
	$sql = "SELECT id, caption FROM `".$usernms.".caption`";
	$result = $conn->query($sql);
```
With the result that we got from the query we run a while loop to create the divs and the buttons to be shown to the user. The reason why we make a while loop is because we need to go through all the resulting rows from the database query. For each row first we create the div which will contain the number of iteration and the caption itself. Then, another div is going to be created which will contain a form with an invisible input box with the ID of the current database row. The input box will be followed by a delete button which will call the if statement with "isset($_POST['delb'])" in it. When the delete button is clicked, it will take the value inside the input field which is the row ID. After getting the input field value, it will call a datanase query to delete the row with ID from input field. This is how I made the delete and add captions button in the captions page.

<h3>Hashtag Page</h3>
The hashtag page is the page where the users will be able to save their hashtag ideas in their tag bag and also do hashtag researches. The update that I made was that I changed the Hashtag API that I was using. Moreover, I wanted to add more features to this page which is going to be explained later. The professor gave me the idea of providing the users with a list of the most popular keywords typed each day. The reason behind this was because the it would help the users get a peek of what is trending and what is not. This would help the users create trending content which is everything in social media. Hence, instead of just showing the users the suggested keywords regarding their post only, I decided to show them a daily trending keyword list with its search volume. In order to do this I needed to change the API I was using.

	I changed the API from Instagram Hashtag API to Google Trend API.
	The Instagram Hashtag API would have cost me ₩5,000 per month but now the Google Trend API costs me ₩7,000 per month. 

```html
<script>
const settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://google-trend-api.p.rapidapi.com/dailyTrends?geo=US",
        "method": "GET",
        "headers": {
          "X-RapidAPI-Key": "c0f97e33dcmshe9fcb3dc0d40f21p116ac0jsn66914a52595f",
          "X-RapidAPI-Host": "google-trend-api.p.rapidapi.com"
        }
      };
	$.ajax(settings).done(function (response) {
            var tbody = document.createElement("tbody");
		for(var i = 0; i<20; i++){
		var tr = document.createElement("tr");
		var td = document.createElement("td");
		var td1 = document.createElement("td");
		var buttons = document.createElement("button");
		var b = document.createElement("b");
		buttons.setAttribute("class","cops");
		var txt1 = response['default']['trendingSearchesDays'][0]['trendingSearches'][i]['title']['query'];
		var txt2 = response['default']['trendingSearchesDays'][0]['trendingSearches'][i]['formattedTraffic'];
		if(txt2 == "1M+"){
                	td1.append("1000K+");
		}else{
                	td1.append(txt2);
		}
		buttons.append(b);
		buttons.append(txt1);
		td.append(buttons);
		tr.append(td,td1);
              	tbody.append(tr);
		$("#tables").append(tbody);
	}
	});
</script>
```
The code above is a jQuery code which is used to get the data from the Google Trend API. The API would give us the result in JSON format so I use the data fetched from the JSON and create table rows and columns to show it to the user in the user interface. In order to use the Google Trend API, I do not have to put any values as in the Instagram Hashtag API. This is because the Instagram Hashtag API returns the suggested or related keyword to the keyword typed by the user. However, the Google Trend API crawls the most searched keyword from google and gives us the data in JSON format. Hence, we do not have to specify any values but we can specify the location and also the date. If we do not specify the location and date, the API would return us the most trending keyword in the United States with today's date. Since we want the API to return the most popular keyword today, we do not specify the date nor the location.<br><br>
With the returned result, we get the length of it and run a for loop to go through the entire JSON file. For each result line, we create a table row and table columns. Moreover, we also create a button because we want the users to be able to copy the hashtag with one tap. For each result line, we get its keyword and the traffic for that specific keyword. These two values will be set in txt1 and txt2 variable. We append all these into the pre-built table and we move on to the next result line until we finish. 

So now, by making the Google Trends table, the search function is useless. I also wanted to make the users be able to do keyword researches about a keyword that they were interested. Hence, in the next version I am going to update the hashtag page once again to make it more useful for the instagram influencers.
