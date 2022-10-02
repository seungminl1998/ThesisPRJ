<h1>September 2022, UPDATES</h1>
In this month's update, I made many changes to the hashtag page. In the last version I wrote down that I wanted to add more features to the hashtag page because the search bar became useless when I changed to Google Trends API. I also wanted to make the users be able to do research about the keyword that they were interested too. So I decided to re-subscribe to the Hashtag API used before and also now, InSEO can get the most popular posts to the related keyword from Instagram. For instance, if the users type "coding" in the search bar and click the search button, they will get two pop-ups. One pop-up will be containing the most popular posts related to "coding" in Instagram and the other pop-up will show the suggested or related keywords with the number of posts.

<h2>Amount of money used until now</h2>

| ITEM | COST |
|  :---: |  :---: |
| Domain  | ₩ 20,000  |
| SSL  | ₩ 48,000  |
| Hosting Service  | ₩ 160,000  |
| Google Trends API  | ₩ 7,000 per Month  |
| Hashtag API  | ₩ 5,000 per Month  |
| **TOTAL**  | **₩ 247,000**  |

	I re-subscribed to the Hashtag API

<h3>Hashtag Page</h3>
When explaining the changes made in the Hashtag Page, I will separate the code in chunks because the code is too long.

```php
            <?php
                      if(isset($_POST['text'])){
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                          // collect value of input field
                          $hashtag = $_REQUEST['name'];
                          $hashtagSearchEndpoingFormat = ENDPOINT_BASE . 'ig_hashtag_search?user_id={user-id}&q={hashtag-name}&fields=id,name';
            
                          // get hashtag by name
                          $hashtagSearchEndpoint = ENDPOINT_BASE . 'ig_hashtag_search';
                          $hashtagSearchParams = array(
                              'user_id' => $instagramAccountId,
                              'fields' => 'id,name',
                              'q' => $hashtag,
                              'access_token' => $accessToken
                          );
                          $hashtagSearch = makeApiCall( $hashtagSearchEndpoint, 'GET', $hashtagSearchParams );
                          unset($_SESSION['hashtagId']);
                          $_SESSION['hashtagId'] = $hashtagSearch['data'][0]['id'];
                          header("Location:hashtag4.php");
                          ob_end_flush(); 
                        }
                      }
            ?>
```
The code above is used to get the most popular posts regarding the hashtag that the user searched. First we check if the search button was clicked or not. The if statement will activate if the search button is clicked. When the button is clicked, the $hashtag variable will get the value that is inside the input box. Then, using the variable, we set the parameters of the endpoint used to call the Facebook Graph API. As it can be seen in the array of $hashtagSearchParams variable, there are 4 fields. User ID, Fields, q, and access token.

- User ID: it is the user ID of the authenticated user and the value comes from the $_SESSION established when the page loaded.
- Fields: This is the fields that the API will be getting.
- q: this is the hashtag value that the user is searching for.
- Access Token: this is the access token of the authenticated user and the value comes from the $_SESSION established on page load.

By calling this api, we will be getting the ID of the hashtag searched. As it can be seen in the code, we are saving another $_SESSION variable for "hashtagID". We need the hashtag ID because in order to get the top related post, instagram api does not search for the hashtag with the exact spelling, but it uses hashtag IDs. Instagram sets an ID for each hashtag created. This is a sign that instagram really cares about the hashtags because they are not just normal strings, each of them have an ID. After we assign the session variable for the hashtag ID, we move to the hashtag 4 page.

```php
<?php
  session_start();
	$accessToken = $_SESSION['accessToken'];
	$pageId = $_SESSION['pageID'];
	$instagramAccountId = $_SESSION['instagramID'];
	$usernms = $_SESSION['username'];
    	$hashtagId = $_SESSION['hashtagId'];
    	$hash = $_SESSION['hashtag'];
?>
```
At the top of the hashtag4.php file, we can see that there are more session variables. This is because this page is in charge of showing the users a pop-up box of regarding the keyword that the user searched for. Now we have 2 new variables which is $hashtagID and $hash. These variables are going to be used just in the hashtag4.php file.

```php
<?php
                $hashtagTopMediaEndpointFormat = ENDPOINT_BASE . '{ig-hashtag-id}/top_media?user_id={user-id}&fields=id,caption,comments_count,like_count,media_type{IMAGE},media_url,permalink';
                // top media for hashtag
                $hashtagId = $_SESSION['hashtagId'];
                $hashtagTopMediaEndpoint = ENDPOINT_BASE . $hashtagId . '/top_media';
                $hashtagTopMediaParams = array(
                    'user_id' => $instagramAccountId,
                    'fields' => 'id,caption,children,comments_count,like_count,media_type{IMAGE},media_url,permalink',
                    'access_token' => $accessToken
                );
                $hashtagTopMedia = makeApiCall( $hashtagTopMediaEndpoint, 'GET', $hashtagTopMediaParams );
?>
```
The code above is used to call the top media with the hashtag that the user searched for. We use the hashtagID and append it to the endpoint. Then, we assign the required parameters which are the user ID, access token of the authenticated user and the fields that we need. We need the ID of the post, caption, the children images, comment count, like count, the media type and the media link. After we assign the parameters, we make the API call. The API will return the result in JSON format.

```html
        <div class = "moreInfo">
            <div class = "theTop">----- <b>TOP</b> Related Posts -----</div>
            <div class = "outClose"><button class = "close" onclick="clickHandlers(event)">x</button></div>
            <div class="pages-list-item">
                <?php $x = 0; ?>
            	<?php for($x = 0; $x < 14; $x++){?>
                	<?php if ('IMAGE' ==  $hashtagTopMedia['data'][$x]['media_type']) : ?>
                    		<div class = "tps<?php echo $x?>">
					<img style="margin-bottom: 10px; margin-left: 20px; height:320px" src="<?php echo  $hashtagTopMedia['data'][$x]['media_url']; ?>" />
                    		<div id = "tpss<?php echo $x?>"><?php $string = $hashtagTopMedia['data'][$x]['caption']; ?>
                        	<?php
                        	$string = preg_replace('/#\w+/', '<b>$0</b>', $string, -1, $count);
                        	echo ($string);
                        	?><br>
                                <b>Hashtag Count: </b><?php echo $count?><br><br>
                    		<b>Comments Count: </b><?php echo  $hashtagTopMedia['data'][$x]['comments_count']; ?><br><br>
                    		<b>Like Count: </b><?php echo  $hashtagTopMedia['data'][$x]['like_count']; ?>
                   		</div>
                   	 </div>
			<?php endif; }?>
             </div>
        </div>
```
The code above shows how am I using the JSON result file to show it in the user interface. The moreInfo div is the container div of the pop-up for the top related posts. As all other pop-ups in the application, it will have a close button. First, we will get the top related post that are images. The reason why we are just getting images is because if we start get caroussels and videos too, the page load would be too slow. Hence, in the current version of InSEO videos and caroussels will not be managed. We get the image and then we get the caption. After we get the caption we make the hashtags bold. The code below is how I made it.

```php
   <?php
     $string = preg_replace('/#\w+/', '<b>$0</b>', $string, -1, $count);
     echo ($string);
   ?>
```
We use the preg_replace function and search for strings with an "#" at the start. Then, all the words that start with an hashtag "#" will be made bold. We also use this to count how many hashtags the post have. The counted value will be assigned to $count. After we bold all the hashtag, we show the user how many hashtag it has using the $count variable and then we show the comment count and like count which is in the JSON result.

```html
<script>
	const settingss = {
		"async": true,
		"crossDomain": true,
		"url": "https://instagram-hashtags.p.rapidapi.com/?keyword="+value,
		"method": "GET",
		"headers": {
			"X-RapidAPI-Key": "c0f97e33dcmshe9fcb3dc0d40f21p116ac0jsn66914a52595f",
			"X-RapidAPI-Host": "instagram-hashtags.p.rapidapi.com"
		}
	};
	$.ajax(settingss).done(function (response) {
		var thecount = response['length'];
           	var tbodys = document.createElement("tbody");

		for(var i = 0; i<thecount; i++){
			var trs = document.createElement("tr");
			var tds = document.createElement("td");
			var td1s = document.createElement("td");
			var buttonss = document.createElement("button");
			var bs = document.createElement("b");
			buttonss.setAttribute("class","cops");
			var txt1s = response[i]['keyword'];
			var txt2s = response[i]['post_last_hr'];
          		var x = parseInt(txt2s);
			if(txt2s > 1000){
				bs.setAttribute("style","color:#29a659");
			}else if(txt2s > 1800){
				bs.setAttribute("style","color:#db4047");
			}
			buttonss.append(bs);
			buttonss.append(txt1s);
			td1s.append(txt2s);
             		td1s.setAttribute("data-sort-value",x);
			tds.append(buttonss);
             		trs.append(tds,td1s);
           		tbodys.append(trs);
			$("#thetable").append(tbodys);
		}
	});
</script>
```
The code above shows how the we get the results from the hashtag API. Here we use the hashtag keyword typed in by the user. As it was previously explained, we saved the typed in hashtag value and hashtag id in session variables. The hashtag value which will be appended in the endpoint to make the API work will be fetched from the session variable hashtag. Then using the JSON result, we are going to make it look good in table form. This table created will be inside another pop-up div meaning that in this hashtag page, there will be 2 pop-ups.

In this update, I also added a table sorting system. The table sort javascript was brought from an open source library in github. When the user clicks the table, it will sort alphabetically and also numerically.

	The Table Sort Javascript was made by KyleFox
