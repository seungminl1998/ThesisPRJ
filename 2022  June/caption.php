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
	<link rel="stylesheet" href="css/captionB.css"/>
		<title>
			Caption
		</title>
		<meta charset="utf-8" />
	</head>
	<style>
		#forms{
			grid-row:3;
			grid-column: 2/4;
			display:grid;
			grid-template-columns:1fr 1fr 1fr 1fr;
			height: 30px;
			margin-top:10px;
		}
		.captions{
			margin-left: 10px;
			margin-right: 10px;
			grid-column:1/5;
			display:grid;
			grid-template-columns:1fr 1fr 1fr 1fr;
			font-family:"avenirB";
		}
		.thecs{
			font-family: "avenir";
			grid-column:1/4;
			margin-top:10px;
		}
		.bts{
			grid-column:4/5;
			display:grid;
			grid-template-columns:1fr 1fr 1fr 1fr;
		}
		.delete{
			font-family: "avenir";
			grid-column:3/5;
			grid-row:1;
			border:solid 1px;
			margin-top:10px;
			margin-left: 3px;
			background:#bf2433;
			color:white;
			border-radius:10px;
		}
		.thform{
			grid-column:2/5;
			grid-row:1;
			display:grid;
			grid-template-columns:1fr 1fr 1fr 1fr;
		}
	</style>
	<body>
	<div id="top">
      <p id = "topDesc">SKKU Thesis Project</p>
    </div>
	<?php
	$servername = "localhost";
	$username = "thesis";
	$password = "theskkuproject1!";
	$dbName = "thesis";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbName);

	// Check connection
	if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
	}
	?>
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
      <form id = "forms" method="post">
        <input id = "mycap" placeholder="Write Caption" name = "caption"></input>
        <button id = "addCap" type = "text" name = "text" disabled = "disabled">ADD CAPTION</button>
      </form>
	  <div id = "bodyContainer">

	  	</div>

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
	<script type = "text/javascript" src="js/captionB.js"></script>
	<script>
		const $capAd = $("#mycap");
		const $addCap = $("#addCap");

	    $capAd.keyup(function(){
	        if ($capAd.val().length > 0){
            $addCap.removeAttr("disabled");
          } else{
            $addCap.attr("disabled", "disabled");
          }
          
    });
	</script>
</html>
