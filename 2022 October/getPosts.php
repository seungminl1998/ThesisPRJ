<?php
ob_start();

	include 'defines.php';
	session_start();
	$accessToken = $_SESSION['accessToken'];
	$pageId = $_SESSION['pageID'];
	$instagramAccountId = $_SESSION['instagramID'];
	$usernms = $_SESSION['username'];

	// instagram endpoint structure
	$endpointFormat = 'https://graph.facebook.com/v13.0/{ig-user-id}?fields=business_discovery.username({ig-username}){username,website,name,ig_id,id,profile_picture_url,biography,follows_count,followers_count,media_count,media{id,caption,like_count,comments_count,timestamp,username,media_product_type,media_type,owner,permalink,media_url,children{media_url}}}&access_token={access-token}';

	// instagram endpoint with actuall account id
	$endpoint =  'https://graph.facebook.com/v13.0/' . $instagramAccountId;

	// user to get
	$users = array();

	// get user info and posts
	$users[] = getUserInfoAndPosts( $usernms, $endpoint, $accessToken );

	function getUserInfoAndPosts( $username, $endpoint, $accessToken ) {
		// endpoint params
		$igParams = array(
			'fields' => 'business_discovery.username(' . $username . '){username,website,name,ig_id,id,profile_picture_url,biography,follows_count,followers_count,media_count,media{id,caption,like_count,comments_count,timestamp,username,media_product_type,media_type,owner,permalink,media_url,children{media_url}}}',
			'access_token' => $accessToken
		);

		// add params to endpoint
		$endpoint .= '?' . http_build_query( $igParams );
		// setup curl
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $endpoint );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

		// make call and get response
		$response = curl_exec( $ch );

		// close curl call
		curl_close( $ch );

		// return nice array
		return json_decode( $response, true );
	}
	function makeApiCall( $endpoint, $type, $params ) {
		$ch = curl_init();

		if ( 'POST' == $type ) {
			curl_setopt( $ch, CURLOPT_URL, $endpoint );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
			curl_setopt( $ch, CURLOPT_POST, 1 );
		} elseif ( 'GET' == $type ) {
			curl_setopt( $ch, CURLOPT_URL, $endpoint . '?' . http_build_query( $params ) );
		}

		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

		$response = curl_exec( $ch );
		curl_close( $ch );

		return json_decode( $response, true );
	}

?>
<!DOCTYPE html>
<html>
	<head>
	<script type="text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="css/postAll.css"/>
		<title>
			Home
		</title>
		<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0" />
	</head>
	<body>
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
                          unset($_SESSION['hashtag']);
                          $_SESSION['hashtagId'] = $hashtagSearch['data'][0]['id'];
                          $_SESSION['hashtag'] = $hashtag;
                          header("Location:hashtag4.php");
                          ob_end_flush(); 
                        }
                      }
            ?>
	<div id = "backblack">s</div>
	<?php $x = 0; $y = 0; $followers = 0; $likes = 0; $comments = 0; $impression = 0; $reached = 0; $interaction = 0; $saved = 0;?>
	<?php foreach ( $users as $userInfo ) : ?>
		<?php $followers =  $userInfo['business_discovery']['followers_count'];
	?>
	<?php endforeach; ?>
    <div id="top">
      <p id = "topDesc">SKKU Thesis Project</p>
	  <div id = "logout"><button id = "logut">Logout</button></div>

    </div>
	<?php foreach ( $users as $userInfo ) : ?>
  	<?php foreach ( $userInfo['business_discovery']['media']['data'] as $media ) : ?>
		<?php if ( 'VIDEO' == $media['media_type']) : continue;?>
		<?php else : ?>
			<div class = "moreInfo" name = "<?php echo $y; ?>">
	<div class = "theTop">----- Inseo -----</div>
	<div class = "outClose"><button class = "close" id = "b<?php echo $y++; ?>" onclick="clickHandlers(event)">x</button></div>
    <div class="pages-list-item">
		<img class="pages-media" src="<?php echo $media['media_url']; ?>"> </img>
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
			$htg = substr($matches[0][0],1);
			for($l = 0; $l < $count; $l++){
				echo $matches[0][$l];
				?> <?php
			}
			?>
			</div>
			<form  method = "post">
				<input value = "<?php echo $htg; ?>" name="name" style = "display: none;"></input>
				<button class = "suggest" type = "text" name = "text"> Check Suggested </button>
			</form>
			
		</div>
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

	  <div class = "calculations">
	 		<?php
				$like2follower = ($likes/$followers)*100;
				$commentlike = ($interaction/$reached)*100;
				$impressionrate = ($reached/$impression)*100;
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
				if($commenlike < 12){
					$rcount+=1;
					echo  $rcount . ". You should create more contents where users can interact with you in order to increase the number of interaction in your account.";?><br><?php
					echo "Increasing the interaction could also help your account get more attraction."; ?><br><?php
				}
				if($like2follower < 15){
					$rcount+=1;
					echo  $rcount . ". Maybe this post's topic is not appealing to your audience. You should make posts to attract your followers attention before others.";?><br><?php
					echo "Try changing your caption.";?><br><?php
				}
				if($impressionrate <= 80){
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
      <br />
		  </div>
		  </div>
		<?php endif;?>

 	 <?php endforeach; ?>
	<?php endforeach; ?>
    <div id = "container">
      <div id = "topleft">
        <p id = "insta">Instagram SEO</p>
      </div>
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
        </div>
      </div>
    </div>
    <div id="bottom">
      <h1 class = "title">InSEO</h1>
      <p class = "description">"Instagram Post SEO for Instagram Influencers"</p>
    </div>

	<?php endforeach; ?>
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
			const $logout = $("#logout");

$logout.click(function() {
	window.location.href = 'https://www.inseo.co.kr/inseo/index.php';
});

	</script>
	<script type = "text/javascript" src="js/postAll.js"></script>
	</body>
</html>