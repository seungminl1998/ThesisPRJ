<h1>June 2022, UPDATES</h1>
In this month's update, I made a lot of changes and added a lot of features. First of all, I made the Facebook OAUTH work with no problem. In the last version, there was no button for the faceobok login. There was only the function but it was not working well. In this version it is working. Moreover, we bought the instagram hashtag API from rapid api. This api would get us the related keywords. Moreover, users can see the data of the posts in an organized way. When the users click a post, they will see a pop up with the data. I will be explaining everything step by step in this readme file.

<h3>Index Page</h3>
The index page is the page that the users will see first. It is where the users can click the button start with facebook in order to call the Facebook OAUTH. 

```php
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
```
First, as it can be seen, we are also calling the vendor directory. This was not in the last version but this is a key factor when calling the Facebook OAUTH. Moreover, as it can be seen, the permissions got reduced. This is because there were permissions that were not needed. I thought we needed all those permissions but it turns out that it was not. Moreover, it can be seen that the echo creating the <a> tag is not there. The <a> tag was converted into a button in this version. 
