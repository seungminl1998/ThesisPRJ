<?php
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
	<link rel="stylesheet" href="css/captionB.css"/>
		<title>
			Caption
		</title>
		<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
	<div id="top">
      <p id = "topDesc">SKKU Thesis Project</p>
	  <div id = "logout"><button id = "logut">Logout</button></div>
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
				?><?php echo "<div class = 'thecs' ><b>" .$id. ".</b> " . $row["caption"]. "</div><div class = 'bts'><form class = 'thform' method = 'post'><input style = 'display:none;' name = 'rid' value = ". $row["id"] . "></input><button class = 'delete' name = 'delb'  onclick='clickHandlers(event)'>Delete</button></form></div>";?><?php
				$id+=1;
				}?></div><?php
			} else {
			echo "0 results";
			} 				
			?>
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
		const $logout = $("#logout");

		$logout.click(function() {
			window.location.href = 'https://www.inseo.co.kr/inseo/index.php';
		});

	    $capAd.keyup(function(){
	        if ($capAd.val().length > 0){
            $addCap.removeAttr("disabled");
          } else{
            $addCap.attr("disabled", "disabled");
          }
          
    });
	</script>
</html>