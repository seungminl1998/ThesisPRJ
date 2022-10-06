<?php
ob_start();

	include 'defines.php';
  session_start();
	$accessToken = $_SESSION['accessToken'];
	$pageId = $_SESSION['pageID'];
	$instagramAccountId = $_SESSION['instagramID'];
	$usernms = $_SESSION['username'];
  $hashtagId = $_SESSION['hashtagId'];


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
  <script src="jquery.tablesort.js"></script>
	<link rel="stylesheet" href="css/hashtag3.css"/>
		<title>
			Hashtag Search
		</title>
		<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
    <?php
            $servername = "localhost";
            $username = "thesis";
            $password = "theskkuproject1!";
            $dbname = "thesis";
            $conn = new mysqli($servername, $username, $password, $dbname);
    ?>
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
                <?php
    if(isset($_GET['borrar'])){
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            // collect value of input field
            $datos = $_REQUEST['tablaName'];
            // sql to delete a record
            $sqls = "DELETE FROM `".$usernms.".tags` WHERE tagName='$datos'";
            $conn->query($sqls);
            header("Location:hashtag2.php");
            ob_end_flush(); 
            //echo "<script>window.location.assign('hashtag2.php')</script>";
        }
    }
    ?>
	<div id="top">
      <p id = "topDesc">SKKU Thesis Project</p>
      <div id = "logout"><button id = "logut">Logout</button></div>

    </div>
    <div id = "container">
	<div id = "tags">
        <form action = "hashtag2.php" id = "cTittle" method = "post">
          <input type="text" placeholder="Write the Title of the List" id = "titleIn" name = "tableN"></input>
          <button id = "add" disabled = "disabled" name = "sumar">+</button>
		     <div class = "closeds" style="background:#db4047;">x</div>
        </form>
        <?php
            if(isset($_POST['sumar'])){
              if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // collect value of input field
                $data = $_REQUEST['tableN'];
                $sql = "INSERT INTO `".$usernms.".tags` (tagName) VALUES ('$data')";
                $conn->query($sql);

              }
            }

      ?>
        <div id = "lists">
          <div id = "tagForm">
            <?php
              if (!$conn) {
                echo 'Could not connect to mysql';
                exit;
            }

            $sql = "SELECT tagName FROM `".$usernms.".tags`";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                echo "<form style = 'grid-row:1;' method = 'get' action = 'hashtag3.php'> <input type='text' style = 'display:none;' value = '".$row['tagName']."' name = 'nomTabla'></input><button class = 'items' name = 'chequear'><p class = 'tatgsTITLE' name = '".$row['tagName']."'>".$row['tagName']."</p></button></form>"; 
              }
            } else {
            echo "0 results";
            } 	
          ?>
		</div>
      </div>
      <?php 
                if(isset($_GET['chequear'])){
                  if ($_SERVER["REQUEST_METHOD"] == "GET") {
            
                    // collect value of input field
                    $data = $_REQUEST['nomTabla'];
                    $sql1 = "SELECT tags FROM `".$usernms.".tags` WHERE tagName='$data'";
                    $result1 = $conn->query($sql1);
                    $result2 = $conn->query($sql1);
                  }
                }
    ?> 
    </div>

    <div class = 'tagsInfo' id = "<?php echo $data?>">
      <form action = 'hashtag3.php' class = 'exTitle' method = 'get'>
        <input type='text' style = 'display:none;' value ="<?php echo $data?>" name = 'tableName'></input>
        <input type='text' placeholder='Write the Tag to add' class = 'tagAd' name = 'hashIn'></input>
        <button class = 'plus' name = 'agregar' id = "<?php echo $data?>in">+</button>
        <div class = 'cerrar'>x</div>
        </form>
        <?php
            if(isset($_GET['agregar'])){
                if ($_SERVER["REQUEST_METHOD"] == "GET") {

                        // collect value of input field
                        $datas = $_REQUEST['hashIn'];
                        $tablas = $_REQUEST['tableName'];
                        $data = $tablas;
                        $sql2 = "SELECT tags FROM `".$usernms.".tags` WHERE tagName='$data'";
                        $result2 = $conn->query($sql2);
                        $rows = $result2->fetch_assoc();
                        if ($rows['tags'] != NULL) {
                          $sql = "UPDATE `".$usernms.".tags` SET `tags` = '#".$datas." ".$rows['tags']."' WHERE  `".$usernms.".tags`.`tagName` = '$tablas'";
                        } else {
                          $sql = "UPDATE `".$usernms.".tags` SET `tags` = '#".$datas."' WHERE  `".$usernms.".tags`.`tagName` = '$tablas'";
                        } 	
                        $conn->query($sql);
                        $sql1 = "SELECT tagName, tags FROM `".$usernms.".tags` WHERE tagName='$data'";
                        $result1 = $conn->query($sql1);
                      }
                    }
    ?>
      <div class = 'tageo' name = '<?php ".$tablas."?>s'>
      
        <?php       
        echo "<div class = 'tbg'>---- Tags in Bag <b>".$data." </b>----</div>";
        $rows = $result1->fetch_assoc();
         if ($rows['tags'] != NULL) {
            echo "<p class = 'oneMore'>".$rows['tags']."</p>";
        } else {
          echo "<br><br><p class = 'oneMore'>0 Results</p>";

        } 	?>
      </div>
      <form class = 'exDelete'  method = 'get'>
        <input type='text' style = 'display:none;' value ="<?php echo $data?>" name = 'tablaName'></input>
        <button class = 'exDeletes' name = "borrar">Delete</button>
    </form>

    </div>
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
	  <form id = "searchs" method = "post">
				<div id = "tagBag">Tag Bag</div>
				<input name="name" id = "searchBar" placeholder="#tag search" ></input>
				<button id = "srch" type = "text" name = "text"><img src="assets/whiteicon.png" id = "whiteicon"></button>
      </form>

		<div id = "bodyContainer">
		<table id = "tabless">

	  </table>
        <div class = "captions">
		<table id = "tables">
      <thead>
      <tr>
              <th id = "to">Keyword</th>
              <th class = "vol">Search Volume</th>
            </tr>
      </thead>
          </table>
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
	<script type = "text/javascript" src="js/hashtag.js"></script>
	</body>

	<script>
        $("#tables").tablesort();

          const settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://google-trend-api.p.rapidapi.com/dailyTrends?geo=US&date=2022-09-25",
        "method": "GET",
        "headers": {
          "X-RapidAPI-Key": "c0f97e33dcmshe9fcb3dc0d40f21p116ac0jsn66914a52595f",
          "X-RapidAPI-Host": "google-trend-api.p.rapidapi.com"
        }
      };
      /*
      $.ajax(settings).done(function (response) {
        //console.log(response['default']['trendingSearchesDays'][0]['trendingSearches'][0]['formattedTraffic']);
        //console.log(response['default']['trendingSearchesDays'][0]['trendingSearches'][0]['title']['query']);

      });*/
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
		const $thehomes = $("#searchBar");
    const $addtable = $("#add");
    const $tableInput = $("#titleIn");
    const $tables = $("#lists");
    const tablesL = $("#tagForm");
    const plust = $(".plus");
    const tagAd = $(".tagAd");

    $tableInput.keyup(function(){
	        if ($tableInput.val().length > 0){
            $addtable.removeAttr("disabled");
          } else{
            $addtable.attr("disabled", "disabled");
          }
          
    });

		const $logout = $("#logout");

		$logout.click(function() {
			window.location.href = 'https://www.inseo.co.kr/inseo/index.php';
		});
		function clickHandler(event){
			var value = $thehomes.val();

			/*
				const settings = {
				"async": true,
				"crossDomain": true,
				"url": "https://hashtagy-generate-hashtags.p.rapidapi.com/v1/insta/tags?keyword="+value+"&include_tags_info=true",
				"method": "GET",
				"headers": {
					"X-RapidAPI-Key": "c0f97e33dcmshe9fcb3dc0d40f21p116ac0jsn66914a52595f",
					"X-RapidAPI-Host": "hashtagy-generate-hashtags.p.rapidapi.com"
				}
				};

			$.ajax(settings).done(function (response) {
				console.log(response);
			});
			*/
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
			
</html>