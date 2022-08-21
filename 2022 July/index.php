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
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <script type="text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/home.css"/>
    <title></title>
    <style>
      * {
  box-sizing: border-box;
}
*::before, *::after {
  box-sizing: border-box;
}

button {
  position: relative;
  display: inline-block;
  cursor: pointer;
  outline: none;
  border: 0;
  vertical-align: middle;
  text-decoration: none;
  background: transparent;
  padding: 0;
  font-size: inherit;
  font-family: inherit;
}
button.learn-more {
  width: 222px;
  height: auto;
}
button.learn-more .circle {
  transition: all 0.45s cubic-bezier(0.65, 0, 0.076, 1);
  position: relative;
  display: block;
  margin: 0;
  width: 2rem;
  height: 2rem;
  background: #374785;
  border-radius: 1.625rem;
}
button.learn-more .circle .icon {
  transition: all 0.45s cubic-bezier(0.65, 0, 0.076, 1);
  position: absolute;
  top: 0;
  bottom: 0;
  margin: auto;
  background: #fff;
}
button.learn-more .circle .icon.arrow {
  transition: all 0.45s cubic-bezier(0.65, 0, 0.076, 1);
  left: 0.125rem;
  width: 1.125rem;
  height: 0.125rem;
  background: none;
}
button.learn-more .circle .icon.arrow::before {
  position: absolute;
  content: "";
  top: -0.25rem;
  right: 0.0625rem;
  width: 0.625rem;
  height: 0.625rem;
  border-top: 0.125rem solid #fff;
  border-right: 0.125rem solid #fff;
  transform: rotate(45deg);
}
button.learn-more .button-text {
  transition: all 0.45s cubic-bezier(0.65, 0, 0.076, 1);
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  padding: 0.75rem 0;
  margin-left: 19px;
  color: #374785;
  font-weight: 700;
  line-height: 0.65;
  text-align: center;
}
button:hover .circle {
  width: 100%;
}
button:hover .circle .icon.arrow {
  background: #fff;
  transform: translate(1rem, 0);
}
button:hover .button-text {
  color: #fff;
}

@supports (display: grid) {
  #containers {
    grid-area: main;
    align-self: center;
    justify-self: center;
    font-family: "Avenir";
    margin-top: 10px;
    margin-left: 17px;
  }
}
#top{
  background-color: #374785;
    width: 100%;
    height: 40px;
    position: fixed;
    top: 0;
    left: 0;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
}
#topDesc{
  font-family: "avenirB";
    color: #fff;
    text-align: right;
    margin-top: 10px;
    width: 764px;
    grid-column: 1/6;
    grid-row: 1;
}
#regss{
  grid-column: 6/7;
    grid-row: 1;
    height: 40px;
    width: 210px;
    margin-top: 1px;
    display: flex;
    justify-content: space-evenly;
}
#regs{
  text-align: center;
    font-family: "Avenir";
    font-size: 13px;
    color: white;
    float: right;
    height: 24px;
    width: 93px;
    margin-top: 6px;
    border: solid;
    border-radius: 10px;
    box-shadow: 2px 2px 4px black;
  }
  #backblack{
				opacity: 0.5;
				width: 100%;
				height: 100%;
				background: black;
				overflow:hidden;
				position:fixed;
				top:0;
				left:0;
				z-index: 99;
				display:none;
			}
      .moreInfo{
        font-family: "avenir";
    background-color: white;
    border-color: #374785;
    width: 456px;
    height: 247px;
    position: fixed;
    margin-left: 429px;
    border-radius: 10px;
    z-index: 100;
    animation: fadeIn 0.5s;
    display: grid;
    grid-template-rows: 1fr 1fr 1fr 1fr;
    box-shadow: 10px 10px 10px black;
			}

      .close{
				margin-right: 5px;
				background:#374785;
				border:none;
				color: white;
				float:right;
			}
			.outClose{
				width:fill;
				background:#374785;
				position:sticky;
				top:0;
				grid-row: 1;
        height:24px;
			}
      .cost{
        text-align: center;
    font-size: 30px;
    font-family: 'avenirB';
      }
      .costD{
        text-align: center;
    font-size: 13px;
    font-family: 'avenirI';
    color: grey;
    margin-top: -19px;
      }
      #ems{
        text-align: center;
    border-radius: 4px;
    width: 282px;
    height: 41px;
    border: solid 2px #374785;
    margin-right: 4px;
      }
      #ems:focus{
        outline:none;
      }
      #cnt{
        border: solid 2px #374785;
    border-radius: 4px;
    width: 44px;
    height: 41px;
    background: #374785;
    font-size: 24px;
    color: white;
    
    }
      .pay{
        justify-content: center;
    display: flex;
    margin-top: -18px;
      }
      .moreinfo1{
        font-family: "avenir";
    background-color: white;
    border-color: #374785;
    width: 380px;
    height: 330px;
    margin-top: -66px;
    position: fixed;
    margin-left: 488px;
    border-radius: 10px;
    z-index: 100;
    animation: fadeIn 0.5s;
    display: grid;
    grid-template-rows: 1fr 1fr 1fr 1fr;
    box-shadow: 10px 10px 10px black;
			}
      #acs{
        grid-row: 1;
        grid-column: 3;
    width: 274px;
    margin-bottom: 2px;
    height: 39px;
    text-align: center;
    border: solid 2px #374785;
    border-radius: 4px;

      }
            #acs:focus{
        outline:none;
      }
      #pgid{
        grid-row:2;
        grid-column: 3;
    width: 274px;
    margin-bottom: 2px;
    height: 39px;
    text-align: center;
    border: solid 2px #374785;
    border-radius: 4px;

      }
      #pgid:focus{
        outline:none;
      }
      #inst{
        grid-row: 3;
        grid-column: 3;
    width: 274px;
    margin-bottom: 2px;
    height: 39px;
    border: solid 2px #374785;
    border-radius: 4px;

    text-align: center;
      }
      #inst:focus{
        outline:none;
      }
      #ids{
        border-radius: 4px;
    margin-bottom: 2px;
    height: 39px;
    border: solid 2px #374785;
    text-align: center;
    width: 150px;
      }
      #ids:focus{
        outline:none;
      }
      #idss{
        border-radius: 4px;
    margin-bottom: 2px;
    height: 39px;
    border: solid 2px #374785;
    text-align: center;
    width: 121px;
      }
      #idss:focus{
        outline:none;
      }
      #sumb{
        border: solid 2px #374785;
    border-radius: 4px;
    height: 39px;
    background: #374785;
    font-size: 14px;
    color: white;
    border: solid 2px #374785;
    grid-row: 5;
        grid-column: 3;
      }
      .pays{
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
    display: grid;
    margin-top: 3px;
    height: 206px;
    margin-bottom: 10px;
      }
      .costs{
        text-align: center;
    font-size: 30px;
    font-family: 'avenirB';
    margin-top: -5px;
    margin-bottom: 3px;
      }
      .costDD{
        text-align: center;
    font-size: 13px;
    font-family: 'avenirI';
    color: grey;
      }
      #btms{
        grid-row: 4;
        grid-column: 3;
        display: flex;
      justify-content: space-between;
      }
      #btms1{
        display: flex;
    margin-bottom: 7px;
    justify-content: space-between;
    width: 275px;
      }
      #ids1{
        border-radius: 4px;
    margin-bottom: 2px;
    height: 31px;
    border: solid 2px #374785;
    text-align: center;
    width: 222px;
      }
      #ids1:focus{
        outline:none;
      }
      #idss1{
        border-radius: 4px;
    margin-bottom: 2px;
    height: 31px;
    border: solid 2px #374785;
    text-align: center;
    width: 222px;
    margin-bottom:6px;
      }
      #idss1:focus{
        outline:none;
      }
      </style>
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
    <div class = "cost">$9.99 / Monthly</div>
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
            <?php
            if(isset($_GET['fblg'])){
              if ($_SERVER["REQUEST_METHOD"] == "GET") {
                // collect value of input field
                $usrn = $_REQUEST['usrn'];
                $pswd = $_REQUEST['pswd'];
                $select = mysqli_query($conn, "SELECT * FROM `users` WHERE `username` LIKE '".$usrn."' AND `pss` LIKE '".$pswd."'");
                    if(!mysqli_num_rows($select)) {
                      echo "<script>alert('Account does not exits')</script>";
                    }else{
                      $_SESSION['username'] = $usrn;
                      $_SESSION['pssword'] = $pswd;
                      $row = $select->fetch_assoc();
                      $_SESSION['instagramID'] = $row['instagramCode'];
                      $_SESSION['accessToken'] = $row['accesToken'];
                      $_SESSION['pageID'] = $row['pageID'];
                      header("Location:".$loginUrl);
                      ob_end_flush(); 
                    }
              }
            }
?>
          </div>
     
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
	</script>
</html>
