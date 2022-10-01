<h1>MAY 2022, UPDATES</h1>
I am making the front end and the back end of the <b>Application InSEO</b>.

>This month I bought the domain (inseo.co.kr) in order to make the OAUTH call to Facebook and make facebook login available.

<img width="503" alt="Screen Shot 2022-08-12 at 10 32 29 AM" src="https://user-images.githubusercontent.com/101083759/184268885-c6f799b1-e598-43ba-927e-1a51a88df645.png">

>The hosting service was registered using **WHOIS Hosting Service**.

I registered to a hosting service with linux light in order to get to use a database (MySQL) and also to use PHP to connect to the server. For a reminder in this project I am using the following languages:<br>
   - **HTML**<br>
   - **JAVASCRIPT (JQUERY)**<br>
   - **CSS**<br>
   - **PHP**<br>

Moroever, in order to be able to get the instagram post insights, I bought the SSL Security Certificate **Sectigo Basic DV Single**. This allows my web page to use "HTTPS" which is a must in order to use the Instagram Graph API which is the API needed to get the post insights. 
<img width="430" alt="Screen Shot 2022-08-13 at 8 18 42 AM" src="https://user-images.githubusercontent.com/101083759/184456058-1bb5c2fc-9291-4f6c-af84-657109159a7f.png">

<h3>Index Page</h3>
The Index Page is the first page that the users will see when the type in the domain in the search tab. In April's update, we had the Index File in a normal .html file. This was because I did not have the domain nor the SSL to be using the Facebook Login OAUTH. At first, my idea was to make the users register and login in the application itself. However, the professor gave me the idea of connecting it with Facebook and use the Facebook OAUTH. This way, we could get the instagram data all at once with just one click. This would make the users more comfortable because no registration will be needed. 
However, I am currently having some problems on making the Facebook Login work. It shows an error when the start button is clicked. I still could not find the problem hence I decided to continue working on other pages.

```php
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
 ```
The code above is the code use to establish the Facebook OAUTH. 

<h3>Posts Page</h3>
The Posts Page is the second page that the users will see after logging in using Facebook. In this page, I am using the Facebook Graph API in order to get the following datas:<br>

   - **Instagram ID**
   - **Number of Followers**
   - **Number of Followings**
   - **Insights of each Post**


This can be done after I grant access to the application to get data from my Instagram Account. This is done in the facebook developers page. Before this, in order to get the data correctly, I need to correctly fill in the data in the defines file. The explanation about the defines file will be done later on.
After getting all the data from Instagram and Facebook, I use the previously designed page and fill in the data. The next step for this page is to make the popup screen and make the user be able to check the insights of each post. 

<h3>Hashtags Page</h3>
The Hashtags Page is the third page that the users will see when the Hashtag menu is clicked which is in the top part of the web application. Inside the hashtags page, the users will be able to search for keywords. This is not yet implemented in this month's version. I will be using an API for this function too. When the tag bag button is clicked, which is next to the search bar, the users will see their hashtags saved and grouped according to its post. This bag will be used to help users plan their future posts. 
