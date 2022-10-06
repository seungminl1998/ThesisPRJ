<?php
ob_start();
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
          header("Location: https://www.inseo.co.kr/inseo/loading.php");
          exit();
        } catch ( Facebook\Exceptions\FacebookSDKException $e ) {
          header("Location: https://www.inseo.co.kr/inseo/loading.php");
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
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <script type="text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css"/>
    <title></title>
  </head>
  <body>
  <?php
  ob_start();
  session_start();
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
  <div id = "backblack">s</div>
  <div class = "moreInfo">
	  <div class = "outClose"><button class = "close" onclick="clickHandlers(event)">x</button></div>
    <div class = "cost">$2.99 / Monthly</div>
    <div class = "costD">Pay to get access to InSEO <br>( Required in order to get Access Key )</div>
    <form class = "pay"  method="post">
      <input id = "ems" placeholder = "Email to be contacted" name = "mail"></input><br>
      <button id = "cnt" disabled = "disabled" name = "cnt">&#10513</button>
    </form>
    <?php
    if(isset($_POST['cnt'])){
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					// collect value of input field
					$data = $_REQUEST['mail'];
					$sql = "INSERT INTO emails (email) VALUES ('$data')";
					$conn->query($sql);
				}
			}
      ?>
  </div>
  <div class = "moreinfo1"> 
  <div class = "outClose"><button class = "close" onclick="clickHandlersss(event)">x</button></div>
  <div class = "costs">Register</div>
  <div class = "costDD">First <b> Buy </b> the product <br>to get the following information</div>
  <form class = "pays" method="post">
      <input id = "acs" placeholder = "Access Token" name = "acs"></input>
      <input id = "pgid" placeholder = "Page ID" name = "pgid"></input>
      <input id = "inst" placeholder = "Instagram Code" name ="inst"></input>
      <div id = "btms">
        <input id = "ids" placeholder = "Username" name = "ids"></input>
        <input id = "idss" type = "password" placeholder = "Password" name = "idss"></input>
      </div>
      <button id = "sumb" disabled = "disabled" name = "sumb">Submit</button>
    </form>
    <?php
      if(isset($_POST['sumb'])){
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // collect value of input field
            $acs = $_REQUEST['acs'];
            $pgid = $_REQUEST['pgid'];
            $inst = $_REQUEST['inst'];
            $ids = $_REQUEST['ids'];
            $idss = $_REQUEST['idss'];
            $sql = "INSERT INTO users (username, pss, accesToken, pageID, instagramCode) VALUES ('$ids', '$idss', '$acs','$pgid','$inst')";
            $sql1 = "CREATE TABLE `thesis`.`" . $ids .".caption` (
              id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              caption VARCHAR(300) NOT NULL
              )";
              $sql2 = "CREATE TABLE `thesis`.`" . $ids .".tags` (
                tagName VARCHAR(300) NOT NULL PRIMARY KEY,
                tags VARCHAR(300)
                )";
            $resutl = $conn->query($sql);
              if (!$resutl){
                echo "<script>alert('Username Not Available')</script>";
              }
            $conn->query($sql1);
            $conn->query($sql2);
          }
        }
      ?>
  </div>
    <div id="top">
      <p id = "topDesc">SKKU Thesis Project</p>
      <div id = "regss">
        <button id = "regs" onclick="clickHandler(event)">Buy</button>
        <button id = "regs" onclick="clickHandlerss(event)">Register</button>
      </div>
    </div>
    <div class = "container">
      <div id = "logo"><img id = "log" src = "assets/skku.png"></div>
      <div id = "linea"></div>
      <div id = "txt1">Welcome to <b>InSEO</b></div>
      <div id = "txt2">A service made for Instagram Influencers</div>
          <div id="containers">
          <div id = "btms1">
            <form method="get">
                  <input id = "ids1" placeholder = "Username" name = "usrn"></input>
                  <input id = "idss1" type = "password" placeholder = "Password" name = "pswd"></input>
                  <button class="learn-more" name = "fblg">
              <span class="circle" aria-hidden="true">
                <span class="icon arrow"></span>
              </span>
              <span class="button-text"><b>Start</b> with Facebook</span>
            </button>
            </form>
          </div>
          <?php
            if(isset($_GET['fblg'])){
              if ($_SERVER["REQUEST_METHOD"] == "GET") {
                // collect value of input field
                $usrn = $_REQUEST['usrn'];
                $pswd = $_REQUEST['pswd'];
                $select = mysqli_query($conn, "SELECT * FROM `users` WHERE `username` LIKE '".$usrn."' AND `pss` LIKE '".$pswd."'");
                    if(!mysqli_num_rows($select)) {
                      echo "<div id = 'account'>*Incorrect Password or Username</div>";
                    }else{
                      $_SESSION['username'] = $usrn;
                      $_SESSION['pssword'] = $pswd;
                      $row = $select->fetch_assoc();
                      $_SESSION['instagramID'] = $row['instagramCode'];
                      $_SESSION['accessToken'] = $row['accesToken'];
                      $_SESSION['pageID'] = $row['pageID'];
                      if($row['befre']){
                        header("Location:loading.php");
                        ob_end_flush(); 
                      }else{
                        $sql3 = "UPDATE `users` SET `befre` = '1' WHERE  `users`.`username` = '".$usrn."'";
                        $conn->query($sql3);
                        header("Location:".$loginUrl);
                        ob_end_flush(); 
                      }
                    }
              }
            }
?>
     
    </div>
      <!--<div id = "button" onclick = "location.href=<?php echo "'" . $loginUrl. "'";?>">Start with Facebook</div>-->
  </div>  
    <div id="bottom">
      <h1 class = "title">InSEO</h1>
      <p class = "description">"Instagram Post SEO for Instagram Influencers"</p>
    </div>
  </body>
  		<script>
			const divs = $(".moreInfo");
      const divs1 = $(".moreinfo1");
			const grey = $("#backblack");
      const ems = $("#ems");
      const cnt = $("#cnt");

      const acs = $("#acs");
      const pgid = $("#pgid");
      const inst = $("#inst");
      const sumb = $("#sumb");


      var first = 0;
      var second = 0;
      var third = 0;
      var fourth = 0;

      ems.keyup(function(){ //Email input.
        if(first == 1){
          cnt.removeAttr('disabled');
        }
          if(checkMail(ems.val())){
            first = 1;
            ems.css("border-color", "#374785")
          }else{
            first = 0;
            ems.css("border-color", "red")
            cnt.attr("disabled","disabled");
          }
        });
        acs.keyup(function(){ //AccessToken input.
        if(second == 1 && third == 1 && fourth == 1){
          sumb.removeAttr('disabled');
        }
          if(acs.val().length > 30){
            second = 1;
            acs.css("border-color", "#374785")
          }else{
            second = 0;
            acs.css("border-color", "red")
            sumb.attr("disabled","disabled");
          }
        });
        pgid.keyup(function(){ //AccessToken input.
        if(second == 1 && third == 1 && fourth == 1){
          sumb.removeAttr('disabled');
        }
          if(checkNum(pgid.val())){
            third = 1;
            pgid.css("border-color", "#374785")
          }else{
            third = 0;
            pgid.css("border-color", "red")
            sumb.attr("disabled","disabled");
          }
        });
        inst.keyup(function(){ //AccessToken input.
        if(second == 1 && third == 1 && fourth == 1){
          sumb.removeAttr('disabled');
        }
          if(checkNum(inst.val())){
            fourth = 1;
            inst.css("border-color", "#374785")
          }else{
            fourth = 0;
            inst.css("border-color", "red")
            sumb.attr("disabled","disabled");
          }
        });
			divs.hide();
      divs1.hide();
			var name = "n";
			function clickHandler(event){
			  divs.fadeIn(400);
				grey.fadeIn(400);
			}
			function clickHandlers(event){
				divs.fadeOut(400);
				grey.fadeOut(400);
			}
      function clickHandlerss(event){
			  divs1.fadeIn(400);
				grey.fadeIn(400);
			}
      function clickHandlersss(event){
				divs1.fadeOut(400);
				grey.fadeOut(400);
			}
      function checkMail(names){
        var validate = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/); //Check if it is in email format.
        if(names.length <= 0){
        }else if(names.length > 0 && !validate.test(names)){
        }else{
          return true;
        }
      }
      function checkNum(names){ //Check if the password has 1 cap 1 number and 1 special char.
        var nums = new RegExp(/^[0-9]*$/);
        if(nums.test(names)){
          return true;
        }
      }

  /*
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
      */
	</script>
</html>
