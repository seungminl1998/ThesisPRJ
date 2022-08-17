<?php
    include 'defines.php';

    // load graph-sdk files
    require_once __DIR__ . '/vendor/autoload.php';

    // facebook credentials array
    $creds = array(
        'app_id' => FACEBOOK_APP_ID,
        'app_secret' => FACEBOOK_APP_SECRET,
        'default_graph_version' => 'v13.0',
        'persistent_data_handler' => 'session'
    );

    // create facebook object
    $facebook = new Facebook\Facebook( $creds );

    // helper
    $helper = $facebook->getRedirectLoginHelper();

    // oauth object
    $oAuth2Client = $facebook->getOAuth2Client();

    if ( isset( $_GET['code'] ) ) { // get access token
        try {
            $accessToken = $helper->getAccessToken();
        } catch ( Facebook\Exceptions\FacebookResponseException $e ) { // graph error
            echo 'Graph returned an error ' . $e->getMessage;
        } catch ( Facebook\Exceptions\FacebookSDKException $e ) { // validation error
            echo 'Facebook SDK returned an error ' . $e->getMessage;
        }

        if ( !$accessToken->isLongLived() ) { // exchange short for long
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken( $accessToken );
            } catch ( Facebook\Exceptions\FacebookSDKException $e ) {
                echo 'Error getting long lived access token ' . $e->getMessage();
            }
        }

        echo '<pre>';
        var_dump( $accessToken );

        $accessToken = (string) $accessToken;
        echo '<h1>Long Lived Access Token</h1>';
        print_r( $accessToken );
    } else { // display login url
        $permissions = [
            'public_profile',
            'instagram_basic',
            'pages_show_list',
            'instagram_manage_insights', 
            'instagram_manage_comments', 
            'manage_pages',
            'ads_management', 
            'business_management', 
            'instagram_content_publish', 
            'pages_read_engagement'
        ];
        $loginUrl = $helper->getLoginUrl( FACEBOOK_REDIRECT_URI, $permissions );

        echo '<a href="' . $loginUrl . '">
            Login With Facebook
        </a>';
    }
    ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <script type="text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/home.css"/>
    <title></title>
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
      <?php echo '<a id = "button" href="' . $loginUrl . '">
            START
        </a>';?>
      <button type="button" name="button" id = "button">ddddd</button>
    </div>
    <div id="bottom">
      <h1 class = "title">InSEO</h1>
      <p class = "description">"Instagram Post SEO for Instagram Influencers"</p>
    </div>
  </body>
  <script type = "text/javascript" src="js/home.js"></script>
</html>
