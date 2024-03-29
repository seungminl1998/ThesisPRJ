<h1>June 2022, UPDATES</h1>
In this month's update, I made a lot of changes and added a lot of features. First of all, I made the Facebook OAUTH work with no problem. In the last version, there was no button for the faceobok login. There was only the function but it was not working well. In this version it is working. Moreover, we bought the instagram hashtag API from rapid API. This api would get us the related keywords. Moreover, users can see the data of the posts in an organized way. When the users click a post, they will see a pop up with the data. I will be explaining everything step by step in this readme file.

<h2>Amount of money used until now</h2>

| ITEM | COST |
|  :---: |  :---: |
| Domain  | ₩ 20,000  |
| SSL  | ₩ 48,000  |
| Hosting Service  | ₩ 160,000  |
| Hashtag API  | ₩ 5,000 per Month  |
| **TOTAL**  | **₩ 240,000**  |

<h3>Index Page</h3>
The index page is the page that the users will see first. It is where the users can click the button start with facebook in order to call the Facebook OAUTH. 

```php
<?php
    include 'defines.php';
    require_once __DIR__ . '/vendor/autoload.php';

    $creds = array(
        'app_id' => FACEBOOK_APP_ID,
        'app_secret' => FACEBOOK_APP_SECRET,
        'default_graph_version' => 'v13.0',
        'persistent_data_handler' => 'session'
    );

    $facebook = new Facebook\Facebook( $creds );
    $helper = $facebook->getRedirectLoginHelper();
    $oAuth2Client = $facebook->getOAuth2Client();

    if ( isset( $_GET['code'] ) ) {
        try {
            $accessToken = $helper->getAccessToken();
        } catch ( Facebook\Exceptions\FacebookResponseException $e ) {
          header("Location: https://www.inseo.co.kr/inseo/getPosts.php");
          exit();
        } catch ( Facebook\Exceptions\FacebookSDKException $e ) {
          header("Location: https://www.inseo.co.kr/inseo/getPosts.php");
          exit();
        }

        if ( !$accessToken->isLongLived() ) {
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken( $accessToken );
            } catch ( Facebook\Exceptions\FacebookSDKException $e ) {
                echo 'Error getting long lived access token ' . $e->getMessage();
            }
        }

        echo '<pre>';
        var_dump( $accessToken );

        $accessToken = (string) $accessToken;
    } else {
        $permissions = [
            'public_profile',
            'instagram_basic',
            'pages_show_list',
            'instagram_manage_comments',
            'instagram_manage_insights',
            'instagram_content_publish',
            'instagram_manage_messages',
            'pages_read_engagement'
        ];
        $loginUrl = $helper->getLoginUrl( FACEBOOK_REDIRECT_URI, $permissions );

    }
    ?>
```
First, as it can be seen, we are also calling the vendor directory. This was not in the last version but this is a key factor when calling the Facebook OAUTH. Moreover, as it can be seen, the permissions got reduced. This is because there were permissions that were not needed. I thought we needed all those permissions but it turns out that it was not. Moreover, it can be seen that the echo creating the <a> tag is not there. The <a> tag was converted into a button in this version.

```html
      <button class="learn-more" onclick = "location.href=<?php echo "'" . $loginUrl. "'";?>">
        <span class="circle" aria-hidden="true">
          <span class="icon arrow"></span>
        </span>
        <span class="button-text"><b>Start</b> with Facebook</span>
      </button>
```
The code above shows the button that takes the user to the Facebook OAUTH. It is a button with an animation. This was made with CSS. The animation moves the arrow to the right when the user hovers the mouse over the button. When the user clicks the button, it will right away take the user to the facebook login screen. If it is the first time for the user, it will also ask for the permissions. However, if it is not their first time logging in to the application, then, it will right away ask if to continue with the current login state and if the user agrees, it will take them to the getPosts page which is the page that shows the user's posts.

<h4>Problem</h4>

```php
<?php 
	$accessToken = 'EAAtumlbc53YBAJ9oL77KVpS1F6JmnwCjCXkvBTEiQDI2LE2aaEnjuCFqHB4FEXwoE15UlQZAhZBYhwiG7k3HqiQCOIYqlkGJZBRvnoaUjqGuLIVaursKkcufDeSo3oMiHSUDFrbIPkyDPdGBHnmsYC3riiaZBi5JZBN0SoXhL6Kdb5iPPHVjB';

	// page id
	$pageId = '103296775690978';

	// instagram business account id
	$instagramAccountId = '17841447930157103';
?>
```
In order for this to work, I need to pre-register my access token, the page id and the instagram business account id in the defines file. This is needed because the application is not public for the users. Meaning that only I can use the application. If any other user tries to log in with their Facebook account, they won't be able to get the posts nor any other data because they do not have their acces token and any other data. Hence is the reason why I am the only tester for now. I am currently finding a way so that many users can register and use the app.

<h3>Posts Page</h3>
The posts page is the page where the users will be able to see all their posts and also its data. This page can be only reached when the user is authenticated. The users will see the general information of their instagram account. They will seeing the following datas above:

- instagram ID
- number of followers 
- number of followings
- number of posts<br>

```html
	  <div id = "topContainer">
	  <?php foreach ( $users as $userInfo ) : ?>
        <div id ="theinfo">
          <div id = "left"><b>@<?php echo $userInfo['business_discovery']['username']; ?></b> </div>
          <div id = "right">
            <div id = "data1"><b>Number of Followers: <?php $followers =  $userInfo['business_discovery']['followers_count']; echo $userInfo['business_discovery']['followers_count']; ?></b>  </div>
            <div id = "data2"><b>Number of Following: <?php echo $userInfo['business_discovery']['follows_count']; ?></b></div>
            <div id = "data3"><b>Number of Posts: <?php echo $userInfo['business_discovery']['media_count']; ?></b></div>
          </div>
        </div>
		<div id = "menu">
          <div id ="hme"><button id = "home">HOME</button></div>
          <div class = "dash">|</div>
          <div id = "hash"><button id = "hashtag">HASHTAG SEARCH</button></div>
          <div class = "dash">|</div>
          <div id = "caption"><button id = "captionB">CAPTION BANK</button></div>
        </div>
	  </div>
```
This is the code how I fetched the logged in user's data. As it can be seen in the code, using PHP, I run a for loop. This for loop is to get through the children of a JSON value. We will go through the values inside the JSON variable userInfo which contains the user's Instagram Username, followers, followings and number of posts.
Then, in the middle there will be a menu that the users will use to navigate. This can be also seen in the code. The name of the <div> containing the menu has id = "menu". Then below it, the body of the page will be present. The body will have the posts of the users.
> One important point to remember here is that the Instagram API will only fetch 20 posts. This is the hard limit of the API. However it does not matter because posts that are old, even though they are optimized, it has a low chance of it being picked up by the instagram algorithm.

In the past version we could see how the media datas were brought as a JSON. The below code shows how the JSON is used to show the user the data.

```html
<div id = "bodyContainer">
	<div id = "posts">
		<?php foreach ( $userInfo['business_discovery']['media']['data'] as $media ) : ?>
			<?php if ( 'VIDEO' == $media['media_type']) : continue;?>
			<?php else : ?>
				<div class = "thep" onclick="clickHandler(event)">
					<?php if ( 'IMAGE' == $media['media_type'] || 'CAROUSEL_ALBUM' == $media['media_type']) : ?>
						<img class="imgs" id = "<?php echo $x++; ?>" src="<?php echo $media['media_url']; ?>" />
					<?php endif; ?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
</div>
```
The code above was explained in the previous version. However, in this version there are several changes. There is a onclick event in each div containing one post. Moreover, the <img> tag created in each loop will have an unique id. Each round of loop, the value of $x will increase and that will be the id of the div. This is because when the post is clicked, a pop-up will show containing all the meta data of the clicked post. A div tag will be containing the <img> tag and each <img> tag will be showing a post. When the user clicks the post they want to analyze, they will be clicking the <div> and it will activate the onclick event which will go to the clickHandler(event) function. The code below shows the function. 

```html
<script>
			const divs = $(".moreInfo");
			const grey = $("#backblack");
			divs.hide();
			var name = "n";
			function clickHandler(event){
				name = event.target.getAttribute("id");
				$('div[name="'+name+'"]').fadeIn(400);
				grey.fadeIn(400);
			}
			function clickHandlers(event){
				$('div[name="'+name+'"]').fadeOut(400);
				grey.fadeOut(400);
			}
</script>
```
The function is made using Javascript, JQUERY. When the page loads, it will hide the divs with class = "moreInfo". These are the divs that contains the meta-data and analyzation of all the fetched posts. This is why the page loads slowly. All the process is done when the page loads and it hides it until the user clicks a post. Then it can be seen that there is a grey constant. This is the grey background that will show when the pop-up comes up. Then below it can be seen that a variable is initialized. This variable is used to contain the id of the clicked div which is the name of the div of the pop-up that the function needs to show. This will be better understood if we look at th function. When the user clicks the div, the clickHandler function will be called. Then, inside the function, the variable name will contain the ID of the clicked div. Then, the div with name value equal to the clicked div ID or the name variable will fade in as well as the grey background.
The function that is below the clickHandler function, the clickHandlers function, is called when the user clicks the exit button of the pop-up box. It will fade out the current div box and also the grey background will fade out. This is the only Javascript used in the index page. Next we will move on to how the pop-up boxes are created.
> One important point to remember here is that the pop-up boxes are ALL hidden on page load.

Now, I will be explaining how the pop-up boxes were created and how I made them work. Since the code is too long, I am chopping them into pieces in order for a better understanding.

```php
<?php $x = 0; $y = 0; $followers = 0; $likes = 0; $comments = 0; $impression = 0; $reached = 0; $interaction = 0; $saved = 0;?>
	<?php foreach ( $users as $userInfo ) : ?>
		<?php $followers =  $userInfo['business_discovery']['followers_count'];
	?>
	<?php endforeach; ?>
    <div id="top">
      <p id = "topDesc">SKKU Thesis Project</p>
    </div>
	<?php foreach ( $users as $userInfo ) : ?>
  	<?php foreach ( $userInfo['business_discovery']['media']['data'] as $media ) : ?>
	<div class = "moreInfo" name = "<?php echo $y; ?>">
	<div class = "theTop">----- Inseo -----</div>
	<div class = "outClose"><button class = "close" id = "b<?php echo $y++; ?>" onclick="clickHandlers(event)">x</button></div>
```
The code above is the first chunk. As it can be seen, in the first line of the PHP code, there are many variables. 
- $x variable is used to mark down the actual posts div that the users see when the page loads.
- $y variable is used to mark down the pop up boxes.
- $followers variable is used to contain the number of followers.
- $comments variable is used to contain the number of comments of a post.
- $impression variable is used to contain the number of impressions of a post.
- $reached variable is used to contain the number of reached of a post.
- $interaction variable is used to contain the number of interactions of a post.
- $saved variable is used to contain the number of people who saved the corresponding post.
		
The first two variables are going to be used to make the post div and the pop-up div point at each other. As it can be seen, the pop-up div has class moreInfo and its name will be the $y variable. Hence, everytiime a post div is clicked, the JQuery will check the clicked box ID which will be $x and will find the popup box with the same value $y. This way, the popup box corresponding to the clicked div will open. Moreover, in the end, we can see a close button. The close button is used to close the popup. It will also have an id of $y because we need to know which box to close. When the button is clicked, the clickHandlers function of JQuery explain above will be called. 

```php
    <div class="pages-list-item">
		<?php if ( 'VIDEO' == $media['media_type']) : continue;?>
		<?php else : ?>
        <img class="pages-media" src="<?php echo $media['media_url']; ?>" />
      <?php endif; ?>
	  	<div class = "lc">
		  <h2>Likes: </h2> <b class = "cl"><?php $likes = $media['like_count']; echo $media['like_count']; ?></b>
		  <h2>Comments: </h2> <b class = "cl"><?php $comments = $media['comments_count']; echo $media['comments_count']; ?></b>
		</div>
      	<div class = "caption">
        	<?php
			$string = $media['caption'];
			$string = preg_replace('/#\w+/', '<b>$0</b>', $string, -1, $count);
			echo nl2br($string);
			?>
		</div>
		<div class = "caps" >
			<h1> Hashtags </h1>
			<h4 class = "nums"> NUMBER OF HASHTAGS: <?php echo $count?> </h4>
			<div class = "hashes">
			<?php
			preg_match_all('/#\w+/',$string,$matches);
			for($l = 0; $l < $count; $l++){
				echo $matches[0][$l];
				?> <?php
			}
			?>
			</div>
		</div>
```
The code above shows the first few elements inside the popup box. The code above manages the post image, like, comments, caption and hashtags of the clicked post. We get the like count and the comment count using the JSON produced above and we move on to captions. The most important part here is the captions part because we also need to separate the caption with the hashtags. Since InSEO also shows the user how many hashtags it has, I use the function preg_replace to check how many strings there are which has a "#" on it. In Instagram, in order to set a hashtag, we use the "#" symbol followed by the keyword. Hence, we check all the strings containing "#" at start. Since "#" is always at the start of the hashtag, we check the -1 position of the string. After the count, we put the number of hashtags into the $count variable. <br><br>
After we get the number of hashtags we print this in the hashtag section. Inside the hashtag section, instead of printing all the caption, we just want to print out the hashtags. For this, we use the preg_match_all function. By using this function, we can get all the strings that start with an "#" and put it inside an array. The array is called $matches. We open a for loop which will end when the value reaches the $count value. Each time we loop, we print it inside the section using echo.

```html
		<button class = "linkss" onclick="window.open('<?php echo $media['permalink']; ?>')">
			<h4 class = "titles"> Link To Post </h4>
		</button>
      <br />
      <div class = "mediaType" > Media Type: <?php echo $media['media_type'];  ?></div>
	  <div class = "sep"></div>
	  <div class = "sep1"></div>
	  <div class = "sep2"></div>
	 <?php 
	 	  	  $mediaObject = array( // media post we are working with
				'id' => $media['id'],
				);
				$mediaInsightsEndpoingFormat = ENDPOINT_BASE . '{ig-media-id}/insights?metric=engagement,impressions,reach,saved&access_token={access-token}';
				$mediaInsightsEndpoint = ENDPOINT_BASE . $mediaObject['id'] . '/insights';
				$mediaInsightParams = array(
					'metric' => 'engagement,impressions,reach,saved',
					'access_token' => $accessToken
				);
				$mediaInsights = makeApiCall( $mediaInsightsEndpoint, 'GET', $mediaInsightParams );
				$inst = 0;
				?>
				<div class = "theinsight">
				<?php foreach ( $mediaInsights['data'] as $insight ) : ?>
					<?php if($inst == 3){
						continue;
					}?>
					<div class = "reach">
						<div class = "titless">
							<b><?php 
							if($inst == 0){
								echo "Interactions";
							}else if($inst == 1){
								echo "Impression";
							}else if($inst == 2){
								echo "Reach";
							}			
							?></b>
						</div>
						<div class = "vals">
							<?php foreach ( $insight['values'] as $value ) : ?>
								<div><?php
									if($inst == 0){
										$interaction = $value['value'];
										echo $value['value'];
										$inst+=1;
									}else if($inst == 1){
										$impression = $value['value'];
										echo $value['value'];
										$inst+=1;
									}else if($inst == 2){
										$reached = $value['value'];
										echo $value['value'];
										$inst+=1;
									}else if($inst == 3){
										$saved = $value['value'];
										$inst+=1;
									}	
									
									?>
								</div>
							<?php endforeach; ?>
						</div>
								</div>
				<?php endforeach; ?>
			</div>
```
The code above shows how the insights of the clicked element inside the pop-up box is fetched and shown to the user. The insight includes the following:
	    
- Interactions
- Impression
- Reach

In order to get those values of a post, we need to reach for the $insights[value] JSON variable. Inside it there will be the values of the insights mentioned above. As it can be seen in the code above, we open a for loop to get through the JSON variables inside the $insights[value] JSON. Then, each value will be assign to the variable that was mentioned in the first chunk of code. These values will be used later on to calculate the user's post grade which is shown in the code below. 

```html
	  <div class = "calculations">
	 		<?php
				$like2follower = $likes/$followers;
				$commentlike = $interaction/$reached;
				$impressionrate = $reached/$impression;
			?>
			<div class = "reach">
				<div class = "titless">Like Follower Ratio</div>
				<b class = "vals"><?php echo number_format((float)$like2follower, 2, '.', '');?></b>
			</div>
			<div class = "reach">
				<div class = "titless">Reach Interaction Ratio </div>
				<b class = "vals"><?php echo number_format((float)$commentlike, 2, '.', '');?></b>
			</div>
			<div class = "reach">
				<div class = "titless">Impression Rate </div>
				<b class = "vals"><?php echo number_format((float)$impressionrate, 2, '.', '');?></b>
			</div>
		</div>
		<div class = "recommendation">
			<div class = "recom">RECOMMENDATIONS </div>
			<div class = "recomss">
			<?php 
				$rcount = 0;

				if($count < 15) {
					$rcount+=1;
					echo $rcount . ". Your current hashtag count is " . $count . ". You should add ". (15 - $count) . " hashtag(s) more.";?><br><?php
				}
				if($commenlike < 0.12){
					$rcount+=1;
					echo  $rcount . ". You should create more contents where users can interact with you in order to increase the number of interaction in your account.";?><br><?php
					echo "Increasing the interaction could also help your account get more attraction."; ?><br><?php
				}
				if($like2follower < 0.15){
					$rcount+=1;
					echo  $rcount . ". Maybe this post's topic is not appealing to your audience. You should make posts to attract your followers attention before others.";?><br><?php
					echo "Try changing your caption.";?><br><?php
				}
				if($impressionrate <= 0.88){
					$rcount+=1;
					echo  $rcount . ". Your photo is not getting clicked when shown in search.";?><br><?php
					echo "Try changing your thumbnail.";?><br><?php
				}
				if($rcount == 0){
					echo "This post is optimized 100%. ";
				}
			?>
			</div>
		</div>
```
The code above shows what does InSEO calculates and how it is giving out the recommendations. We will be calculating the like to follower ratio, comment to like ratio and the impression rate. This is the first thing we do in this chunk of code. The variables used here were initialized above and the values were given when getting the insight values. After calculating this we initialize another variable $rcount. $rcount is the variable that will be used to check how optmized the post is. The lower its number is, the post will get a better grade. We compare the values calculated with the standard that we pre-defined. If they are lower, we increase the $rcount by one and give out the user a recommendation that would help them optmized their post. If not, we do nothing. 
	    
I did a research of many instagram influencer accounts. From the ones that were doing well to those that were normal. By watching them, I got an average value of the like to follower ratio, comment to like ratio and the impression rate. This became the standard of InSEO when calculating the post grade.

```html
		<div class = "contentG">
			<div class = "recom">Content Grade </div>
			<div class = "grade">
				<?php
					if($rcount == 0){
						echo "A+";
					}else if($rcount == 1){
						echo "A";
					}
					else if($rcount == 2){
						echo "B";
					}else if($rcount == 3){
						echo "C";
					}else if($rcount == 4){
						echo "D";
					}
					
				?>
			</div>
		</div>
      <?php if ( 'CAROUSEL_ALBUM' == $media['media_type'] ) : ?>
        <div>
          <?php foreach ( $media['children']['data'] as $child ) : ?>
            <img class="child-media" src="<?php echo $child['media_url']; ?>" />
          <?php endforeach; ?>
        </div>x	
      <?php endif; ?>
      <br />
		  </div>
		  </div>
 	 <?php endforeach; ?>
	<?php endforeach; ?>
```
The code above shows how the grades are shown to the user. In the chunk of code before this, the $rcount variable was defined and a value was assigned according to the calculations. We said that the lower the $rcount value was, the higher the post grade would be. As it can be seen in the code, there are if-else statements checking the $rcount value and according to its value the grade would be shown to the user. In the end, we also get the childrent images if the post is a carousel album. A carousel album is a post with various images. However this is not working at this moment and I still do not know the problem. 

<h3>Hashtag Page</h3>
The hashtag page is the page where the users will be able to save their hashtags in their "Tag Bag" and also do their hashtag research. The hashtag page is composed of three different PHP files. Each of them will open when an action is performed because some divs are hidden on page load. When an update to database is done, in order to show the users the update right away, a refresh is needed. However, if I refresh and stay in the same page, the divs would hide on page load. Hence, I made another hashtag page that would not close the div on page load. Since for the tag bag , there are 3 functions, I made three different pages. The first page would have all the pop-up divs closed. The second page would have the tag group list pop-up div shown and the thrid page would have the tags list of the tag group pop-up div shown as well as the tag group list div.

```html
        <form action = "hashtag2.php" id = "cTittle" method = "get">
          <input type="text" placeholder="Write the Title of the List" id = "titleIn" name = "tableN"></input>
          <button id = "add" disabled = "disabled">+</button>
		     <div class = "closeds" style="background:#db4047;">x</div>
        </form>
```
The code above shows the first form which is used when the user tries to create a new tag group. The user will write the tag group title and will add the add button to create a table in the database which will later on contain the hashtags. The code below will show how we are managing the database.

```php
    $addtable.click(function(){
      <?php
        $servername = "localhost";
        $username = "thesis";
        $password = "theskkuproject1!";
        $dbname = "test";
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "CREATE TABLE " . $_GET['tableN'] ." (
          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          hashtag VARCHAR(30) NOT NULL
          )";
        $conn->query($sql)
      ?>
    })
```
When the user clicks the add button, an action will be triggered using JQuery. Inside the triggered action function, there will be a PHP that will be used to connect with the database and update or create tables. The servername, username, password, and the database name is needed in order to connect to the database. Then we establish a connection using mysqli. Then, we make a query which in this case would be to create a table with the name written in the input box. Since the input box is inside the form tag, and the form tag is of type GET, we can get the input value using $_GET variable. This is how we connect to the database and create a new table.

```php
            <?php
            $sql = "SHOW TABLES FROM $dbname";
            $result = mysqli_query($conn, $sql);
            
            if (!$result) {
                echo "DB Error, could not list tables\n";
                echo 'MySQL Error: ' . mysql_error();
                exit;
            }
            
            while ($row = mysqli_fetch_row($result)) {
              echo "<form style = 'grid-row:1;' method = 'get' action = 'hashtag3.php'> <input type='text' style = 'display:none;' value = '".$row[0]."' name = 'nomTabla'></input><button class = 'items' name = 'chequear'><p class = 'tatgsTITLE' name = '".$row[0]."'>".$row[0]."</p></button></form>"; 
            }
          ?>
```
The code above shows how the tag groups are shown in the user interface. First we connect to the database and create a query. Since this time we need to fetch the data from the database we use the SHOW TABLES query. We check if the connection was established correctly and if it was we open a while statement to get through all the elements in the table. For each element, we create a form. As it can be seen in the code, we are creating a whole HTML code using echo of PHP. For each element, we create a form with an input that will be hidden. This will be explained later on. Moreover, a button is created and it will show the user the name of the table. Each element will be a clickable button because the users must be able to open the tag group to edit the hashtag list. The reason why there is a hidden input box is because the input box will contain the name of the table which, later on, will be used to fetch and open the corresponding table containing the hashtag list.

```html
<script>
function clickHandler(event){
			var value = $thehomes.val();
		const settings = {
				"async": true,
				"crossDomain": true,
				"url": "https://instagram-hashtags.p.rapidapi.com/?keyword="+value,
				"method": "GET",
				"headers": {
					"X-RapidAPI-Key": "c0f97e33dcmshe9fcb3dc0d40f21p116ac0jsn66914a52595f",
					"X-RapidAPI-Host": "instagram-hashtags.p.rapidapi.com"
				}
			};

			$.ajax(settings).done(function (response) {
				var thecount = response['length'];
				for(var i = 0; i<thecount; i++){
					var tr = document.createElement("tr");
					var td = document.createElement("td");
					var td1 = document.createElement("td");
					var buttons = document.createElement("button");
					var b = document.createElement("b");
					buttons.setAttribute("class","cops");
					var txt1 = response[i]['keyword'];
					var txt2 = response[i]['post_last_hr'];
					if(txt2 > 1000){
						b.setAttribute("style","color:#29a659");
					}else if(txt2 > 1800){
						b.setAttribute("style","color:#db4047");
					}
					buttons.append(b);
					buttons.append(txt1);
					td1.append(txt2);
					td.append(buttons);
					tr.append(td,td1);
					$("#tables").append(tr);
				}
			});
		}
</script>
```
In the hashtag page, there will be a search bar in which the user will be able to type a keyword they want to do research. This is where the API bought from Rapid API will be used. This API will produce the suggested keywords for the user. For instance, if we type in coding, then the API will get us suggested keywords such as "codingisfun" "codinglife" and etc. Moreover, it will also give us how many posts there were in the last hour. This is a number needed because we want to see how popular the hashtag is. <br><br>
The variable value is the variable that will contain the typed value. This variable will be appended in the API call. Then the API will get us the response in JSON format. We count the response length to open a loop to go through all the JSON values. Everything inside the loop shows how I decorated the table. Moreover, the suggested keywords are created as buttons so that the users can click the keyword to easily copy it to their clipboard. I append everything created in the loop inside the table created in the body container of the HTML code. 
