<?php
	include 'defines.php';

	// instagram endpoint structure
	$endpointFormat = 'https://graph.facebook.com/v13.0/{ig-user-id}?fields=business_discovery.username({ig-username}){username,website,name,ig_id,id,profile_picture_url,biography,follows_count,followers_count,media_count,media{id,caption,like_count,comments_count,timestamp,username,media_product_type,media_type,owner,permalink,media_url,children{media_url}}}&access_token={access-token}';

	// instagram endpoint with actuall account id
	$endpoint =  'https://graph.facebook.com/v13.0/' . $instagramAccountId;

	// user to get
	$users = array();

	// get user info and posts
	$users[] = getUserInfoAndPosts( 'thealexleee', $endpoint, $accessToken );

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
?>
<!DOCTYPE html>
<html>
	<head>
	<link rel="stylesheet" href="css/postAll.css"/>
		<title>
			Getting a Users Instagram Info and Posts
		</title>
		<meta charset="utf-8" />
		<style>
			body {
				font-family: 'Helvetica';
			}

			.pages-list {
				display: block;
			}

			.pages-list-item {
				display: inline-block;
				vertical-align: top;
				margin-top: 10px;
				width: 250px;
				border: 1px solid #333;
				margin-left: 10px;
				padding: 10px;
				font-size: 9px;
			}

			.pages-media {
				width: 100%;
			}

			.raw-response {
				width: 100%;
				height: 600px;
			}

			.nav-container {
				margin-top: 20px;
				font-size: 20px;
				display: inline-block;
				width: 100%;
			}

			.nav-next {
				float: right;
			}

			.profile-container {
				display: inline-block;
				width: 100%;
			}

			.profile-image-container {
				float: left;
			}

			.profile-image-container img {
				width: 50px;
				border-radius: 50%;
			}

			.child-media {
				width: 70px;
			}

			textarea {
				width: 100%;
				height: 500px;
			}
		</style>
	</head>
	<body>
    <div id="top">
      <p id = "topDesc">SKKU Thesis Project</p>
    </div>
    <div id = "container">
      <div id = "topleft">
        <p id = "insta">Instagram SEO</p>
      </div>
	  <div id = "topContainer">
	  <?php foreach ( $users as $userInfo ) : ?>
        <div id ="theinfo">
          <div id = "left"><b>@<?php echo $userInfo['business_discovery']['username']; ?></b> </div>
          <div id = "right">
            <div id = "data1"><b>Number of Followers: <?php echo $userInfo['business_discovery']['followers_count']; ?></b>  </div>
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
						<div class = "thep">
							<?php if ( 'IMAGE' == $media['media_type'] || 'CAROUSEL_ALBUM' == $media['media_type']) : ?>
								<img class="imgs" src="<?php echo $media['media_url']; ?>" />
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
	</body>
</html>
