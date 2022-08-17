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
      </style>
  </head>
  <body>
    <div id="top">
      <p id = "topDesc">SKKU Thesis Project</p>
    </div>
    <div class = "container">
      <div id = "logo"><img id = "log" src = "assets/skku.png"></div>
      <div id = "linea"></div>
      <div id = "txt1">Welcome to <b>InSEO</b></div>
      <div id = "txt2">A service made for Instagram Influencers</div>
          <div id="containers">
      <button class="learn-more" onclick = "location.href=<?php echo "'" . $loginUrl. "'";?>">
        <span class="circle" aria-hidden="true">
          <span class="icon arrow"></span>
        </span>
        <span class="button-text"><b>Start</b> with Facebook</span>
      </button>
    </div>
      <!--<div id = "button" onclick = "location.href=<?php echo "'" . $loginUrl. "'";?>">Start with Facebook</div>-->
  </div>  
    <div id="bottom">
      <h1 class = "title">InSEO</h1>
      <p class = "description">"Instagram Post SEO for Instagram Influencers"</p>
    </div>
  </body>
</html>
