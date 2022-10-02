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
  <script src="jquery.tablesort.js"></script>

	<style>
		#theforms{
		grid-column:2/5;
		grid-row:1/2;
		display:grid;
		grid-template-columns:1fr 1fr 1fr 1fr;
		}
		#searchBar{
		grid-column: 2/5;
		grid-row:1;
		border: solid 2px black;
		background-color: white;
		color:black;
		text-align: center;
		font-family: "avenir";
		border-radius: 10px;
		}
		#srch{
		grid-column: 5;
		grid-row:1;
		border: none;
		background-color: #374785;
		color:white;
		font-family: "avenir";
		border-radius: 10px;
		width: 40px;
		margin-left: 10px;
		}
	#searchs{
		margin-top:10px;
		height:30px;
		grid-row:3;
		grid-column: 2/4;
		display:grid;
		grid-template-columns:1fr 1fr 1fr 1fr;
	}
	#tagBag{
		grid-column:1/2;
		grid-row:1;
  background-color: #fff;
  border:none;
  font-family: "avenirB";
  color: #374785;
  text-align: center;
    line-height: 25px;
    font-size: 13px;
}
.captions{
  font-family: "avenir";
  margin-top:5px;
  border-top:dotted 3px #374785;
  border-bottom:dotted 3px #374785;
  height:340px;
  grid-column: 1/5;
  grid-row: 3/30;
  overflow-y:scroll;
}
.captions::-webkit-scrollbar {
    display: none;
}
#tables{
  text-align: center;
  width: 100%;
  border-collapse: collapse;
  font-family: "avenir";
}
#tabless{
	grid-column:1/5;
	grid-row:2;
  text-align: center;
  border: solid 1px;
  width: 100%;
  border-collapse: collapse;
  font-family: "avenir";
  margin-top:3px;
}
#to{
	font-family: "avenirB";
	border: solid 1px white;
	border-radius:10px;
  background: #374785;
  color: white;
  height: 30px;
  width: 523px;  font-size: 15px;
}
.td{
	font-family: "avenirB";
  height: 30px;
  width:425px;
  font-size: 15px;

}
th{
	font-family: "avenirB";
	font-size: 15px;
  border: solid 1px white;
  background: #374785;
  color: white;
  height: 30px;
	border-radius:10px;
}
td{
  border-bottom: dotted 2px #374785;
  line-height: 30px;
}
#tags{
  z-index: 2;
      position: fixed;
    width: 688px;
        margin-left: 335px;
  grid-column: 2/4;
  grid-row: 1/3;
  border: solid 3px #374785;
  border-radius:10px;
  background-color: #fff;
  display: none;
  grid-template-columns: 1fr 1fr 1fr 1fr;
  filter: drop-shadow(0 0 0.75rem  #374785);
}
#cTittle{
  grid-row: 1/2;
  grid-column: 1/5;
  text-align: center;
  font-family: "avenirI";
  border: none;
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 35px 40px;
}
#titleIn{
  height:32px;
  width:560px;
  text-align: center;
  margin-top:3px;
  margin-left: 10px;
  border:none;
  grid-column:1/4;
}
#titleIn:focus{
  outline: none;
}
#add{
  border:none;
  border-radius: 3px;
  color:white;
  background-color: #29a659;
  font-family: "avenir";
  font-size: 14px;
  width:33px;
  height: 30px;
  font-weight:800;
  grid-column: 4;
    margin-top: 3px;
}
.closeds{
  border:none;
  border-radius: 3px;
  color:white;
  font-family: "avenir";
  font-size: 14px;
  width:33px;
  height: 30px;
  font-weight:800;
  grid-column: 5;
  margin-top: 3px;
  line-height:26px;

}
#close{
  border:none;
  border-radius:10px;
  background-color:#fff;
  font-family: "avenirB";
  float:right;
  grid-column: 4/5;
  grid-row: 1/2;
  text-align: right;
  margin-top:5px;
  height:20px;
}
#lists{
  grid-row:2/40;
  grid-column: 1/5;
  border-top:dotted 3px #374785;
  display: grid;
  grid-template-rows: 1fr;
}
#tagForm{
  width: inherit;
  display:grid;
  grid-template-rows: 1fr;
  overflow-x: scroll;
  grid-template-columns: 135px 135px 135px 135px 135px;

}
#tagForm::-webkit-scrollbar {
  display: none;
}
.items{
  grid-row:1;
  border:solid 1px #fff;
  color:white;
  text-align: center;
  font-size:12px;
  margin-top:9px;
  margin-left: 5px;
  margin-bottom: 5px;
  width:130px;
  background-color: #374785;
  border-radius: 7px;
  height:126px;
}
.tagsInfo{
	z-index: 2;
	position:absolute;
	border-radius:10px;
  grid-column: 1;
  grid-row: 1/4;
  width: 320px;
  height: 240px;
  border: solid 3px #374785;
  background-color: #fff;
  display: none;
  grid-template-columns: 1fr 1fr;
}
.exTitle{
  grid-row: 1;
  grid-column: 1/3;
  text-align: center;
  font-family: "avenirI";
  background:#374785;
  display:grid;
  grid-template-columns: 1fr 40px 40px;
  grid-template-rows:1fr;
}
.exDelete{
	border: none;
    border-radius: 10px;
    grid-row: 3;
    grid-column: 1/3;
    text-align: center;
    font-family: "avenirI";
    background: #db4047;
    margin-top: 8px;
    height: 40px;
    width: 320px;
    color: white;
    filter: drop-shadow(0 0 0.75rem grey);
}
.tagAd{
	grid-row: 2;
  height:30px;
  text-align: center;
  margin-top:3px;
  margin-bottom:3px;
  width:224px;
  border:none;
  color:white;
  background-color:#374785;
  grid-column:1;
  grid-row:1;
}
.tagAd:focus{
  outline: none;
  color:white;
}
.tagAd::placeholder{
  color:white;
  opacity:0.5;
}
.plus{
	border:none;
  border-radius: 3px;
  color:white;
  background-color: #29a659;
  font-family: "avenir";
  font-size: 14px;
  width:35px;
  height: 30px;
  font-weight:800;
  grid-column:2;
  grid-row:1;
  margin-top:3px;
}
.cerrar{
	border:none;
  border-radius: 3px;
  color:white;
  background-color: #db4047	;
  font-family: "avenir";
  font-size: 14px;
  width:35px;
  height: 30px;
  font-weight:800;
  grid-column:3;
  grid-row:1;
  margin-top:3px;
  line-height:29px;
}
th.sorted.ascending:after {
	content: "      \2191";
}

th.sorted.descending:after {
	content: " \2193";
}
	</style>
	<link rel="stylesheet" href="css/hashtag.css"/>
		<title>
			Hashtag Search
		</title>
		<meta charset="utf-8" />
	</head>
	<body>
    <?php
            $servername = "localhost";
            $username = "thesis";
            $password = "theskkuproject1!";
            $dbname = "thesis";
            $conn = new mysqli($servername, $username, $password, $dbname);?>
	<div id="top">
      <p id = "topDesc">SKKU Thesis Project</p>
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
            /*
            $sql = "SHOW TABLES FROM $dbname";
            $result = mysqli_query($conn, $sql);
            
            if (!$result) {
                echo "DB Error, could not list tables\n";
                echo 'MySQL Error: ' . mysql_error();
                exit;
            }
            while ($row = mysqli_fetch_row($result)) {
              echo "<form style = 'grid-row:1;' method = 'get' action = 'hashtag3.php'> <input type='text' style = 'display:none;' value = '".$row[0]."' name = 'nomTabla'></input><button class = 'items' name = 'chequear'><p class = 'tatgsTITLE' name = '".$row[0]."'>".$row[0]."</p></button></form>"; 
              //echo "var buttones = document.createElement('button'); var ps = document.createElement('p'); buttones.setAttribute('class','items'); buttones.setAttribute('name', '".$row[0]."'); 
                //ps.setAttribute('class','tagsTITLE'); ps.setAttribute('name','tagLists'); ps.append('".$row[0]."'); buttones.append(ps); tablesL.append(buttones);";
            }
            */
          ?>
		</div>
        </div>
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
        "url": "https://google-trend-api.p.rapidapi.com/dailyTrends?geo=US",
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

    $tableInput.keyup(function(){
	        if ($tableInput.val().length > 0){
            $addtable.removeAttr("disabled");
          } else{
            $addtable.attr("disabled", "disabled");
          }
          
    });
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
        //console.log(response['default']['trendingSearchesDays'][0]['trendingSearches'][0]['formattedTraffic']);
        //console.log(response['default']['trendingSearchesDays'][0]['trendingSearches'][0]['title']['query']);

      });
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
*/
				}


        
	</script>
			
</html>
