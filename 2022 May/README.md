<h1>MAY 2022, UPDATES</h1>
I am making the front end and the back end of the <b>Application InSEO</b>.

>This month I bought the domain (inseo.co.kr) in order to make the OAUTH call to Facebook and make facebook login available.

<img width="503" alt="Screen Shot 2022-08-12 at 10 32 29 AM" src="https://user-images.githubusercontent.com/101083759/184268885-c6f799b1-e598-43ba-927e-1a51a88df645.png">

>The hosting service was registered using **WHOIS Hosting Service**.

<h2>Amount of money used until now</h2>

| ITEM | COST |
|  :---: |  :---: |
| Domain  | ₩ 20,000  |
| SSL  | ₩ 48,000  |
| Hosting Service  | ₩ 160,000  |

I registered to a hosting service with linux light in order to get to use a database (MySQL) and also to use PHP to connect to the server. For a reminder in this project I am using the following languages:<br>
   - **HTML**<br>
   - **JAVASCRIPT (JQUERY)**<br>
   - **CSS**<br>
   - **PHP**<br>

Moroever, in order to be able to get the instagram post insights, I bought the SSL Security Certificate **Sectigo Basic DV Single**. This allows my web page to use "HTTPS" which is a must in order to use the Instagram Graph API which is the API needed to get the post insights. 
<img width="430" alt="Screen Shot 2022-08-13 at 8 18 42 AM" src="https://user-images.githubusercontent.com/101083759/184456058-1bb5c2fc-9291-4f6c-af84-657109159a7f.png">

<h3>Reason of Buying</h3>
The reason why I bought the SSL and the hosting service as well as the domain was because I wanted to make the Facebook OAUTH work. By making the Facebook OAUTH, it would be easier for the users to login. Moreover, since Facebook is connected to Instagram, it would be better for us and also for the users to login using Facebook.

<h3>Index Page</h3>
The Index Page is the first page that the users will see when the type in the domain in the search tab. In April's update, we had the Index File in a normal .html file. This was because I did not have the domain nor the SSL to be using the Facebook Login OAUTH. At first, my idea was to make the users register and login in the application itself. However, the professor gave me the idea of connecting it with Facebook and use the Facebook OAUTH. This way, we could get the instagram data all at once with just one click. This would make the users more comfortable because no registration will be needed. 
However, I am currently having some problems on making the Facebook Login work. It shows an error when the start button is clicked. I still could not find the problem hence I decided to continue working on other pages.

```php
<?php
    include 'defines.php';

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
The code above is the code use to establish the Facebook OAUTH. In order to make the OAUTH work, I got the access token and the extra data and put it in the defines file. The define file contains the extension data in order for the Facebook OAUTH work. Then, when it is the user's first time logging in with Facebook, we need to ask the permissionns. As it can be seen in the code above, there is a permission section. Those are the permissions needed in order for the Facebook API work. Without those permissions, InSEO won't be able to fetch the data and nothing would work.

```php
<?php
	session_start();

	define( 'FACEBOOK_APP_ID', '3217833905153910' );
	define( 'FACEBOOK_APP_SECRET', '●●●●●●●●' );
	define( 'FACEBOOK_REDIRECT_URI', 'https://inseo.co.kr/inseo/index.php' );
	define( 'ENDPOINT_BASE', 'https://graph.facebook.com/v13.0/' );

	// accessToken
	$accessToken = 'AAtumlbc53YBAHoJVo3uqVQiUIFAnXsgblNfTWTFHvdpejt7avZASJScr4x3OzONFyqrEBR2f3NI9dfAZCIpqzg39P4VFcAHUPHic49VXImPWi8EzfRzoMrF7KK8tTLHTMAuyNcCPqom9WO8AHxf64CsRlNWmkGK3CDhE8AJvyRNzaENyO
	';
	?>
```

The access token is found in the developers page. The access token from the developers page is then copied to the defines.php file which is going to be used throughout all the project. The Facebook ID is also needed which is given after I create a facebook developers account. This also goes the same for the app secret. The facebook redirect uri is where the Facebook OAUTH will be called. Since this is needed throughout all the project, I decided to create a defines page separately. 
As it could be seen in the code for the Facebook OAUTH, the variables above are used. 


 ```php
        echo '<a href="' . $loginUrl . '">
            Login With Facebook
        </a>';
 ```
 As it can be seen in the code above, by using PHP we produce an <a> tag which corresponds to links in html. We create a link directed to the login URL with a text "Login with Facebook". Meaning, that when we click login with Facebook, we will go to the Facebook Authentication. 
	
<h3>Posts Page</h3>
The Posts Page is the second page that the users will see after logging in using Facebook. In this page, I am using the Facebook Graph API in order to get the following datas:<br>

   - **Instagram ID**
   - **Number of Followers**
   - **Number of Followings**
   - **Insights of each Post**


This can be done after I grant access to the application to get data from my Instagram Account. This is done in the facebook developers page. Before this, in order to get the data correctly, I need to correctly fill in the data in the defines file. The explanation about the defines file will be done later on.
After getting all the data from Instagram and Facebook, I use the previously designed page and fill in the data. The next step for this page is to make the popup screen and make the user be able to check the insights of each post. 

```php
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
?>
```
The code above is the code used to get fetch the posts datas. Moreover, by using the code above we get the user's data. In the igParams, we can see a list of parameters that we are getting. There we call all the data including the users data and also their posts. This would return a JSON in the end. The JSON would be use to call the elements later on within the body in order to build the page and fill it with the logged in user data. 

```html
	<div id = "posts">
		<?php foreach ( $userInfo['business_discovery']['media']['data'] as $media ) : ?>
			<?php if ( 'VIDEO' == $media['media_type']) : continue;?>
			<?php else : ?>
				<div class = "thep">
					<?php if ( 'IMAGE' == $media['media_type'] || 'CAROUSEL_ALBUM' == $media['media_type']) : ?>
						<img class="imgs" src="<?php echo $media['media_url']; ?>" />
					<?php endif; ?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
```
The code above shows how we fetch the data from the JSON produced above. Since we are trying to get the data for the posts, we get the "media" JSON data. As it can be seen, the image source would be the media URL and it is called with the data that is within the JSON. In order to get all the images, we use the for loop. This way we can get all the datas for each post.
One important point to remember here is that we will not be getting the Video posts from instagram. As it can be seen we are checking the media type. If the media type is equal to Video, the program will not produce any <img> tag. In other words, it is jumping the videos. Moreover, if the post has various images in it, we will just get the first picture. I did this because if we try to get the videos as well as child images, the page would take too long to load which would be bad for the users. 
