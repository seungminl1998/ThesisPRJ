<h1>June 2022, UPDATES</h1>
In this month's update, I made a lot of changes and added a lot of features. First of all, I made the Facebook OAUTH work with no problem. In the last version, there was no button for the faceobok login. There was only the function but it was not working well. In this version it is working. Moreover, we bought the instagram hashtag API from rapid API. This api would get us the related keywords. Moreover, users can see the data of the posts in an organized way. When the users click a post, they will see a pop up with the data. I will be explaining everything step by step in this readme file.

<h2>Amount of money used until now</h2>

| ITEM | COST |
|  :---: |  :---: |
| Domain  | ₩ 20,000  |
| SSL  | ₩ 48,000  |
| Hosting Service  | ₩ 160,000  |
| Hashtag API  | ₩ 7,000 per Month  |
| **TOTAL**  | **₩ 242,000**  |

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

```html
      <button class="learn-more" onclick = "location.href=<?php echo "'" . $loginUrl. "'";?>">
        <span class="circle" aria-hidden="true">
          <span class="icon arrow"></span>
        </span>
        <span class="button-text"><b>Start</b> with Facebook</span>
      </button>
```
The code above shows the button that takes the user to the Facebook OAUTH. It is a button with an animation. This was made with CSS. The animation moves the arrow to the right when the user hovers the mouse over the button. When the user clicks the button, it will right away take the user to the facebook login screen. If it is the first time for the user, it will also ask for the permissions. However, if it is not their first time logging in to the application, then, it will right away ask if to continue with the current login state and if the user agrees, it will take them to the getPosts page which is the page that shows the user's posts.

<h4>Problem</h4>

```php
<?php 
	$accessToken = 'EAAtumlbc53YBAJ9oL77KVpS1F6JmnwCjCXkvBTEiQDI2LE2aaEnjuCFqHB4FEXwoE15UlQZAhZBYhwiG7k3HqiQCOIYqlkGJZBRvnoaUjqGuLIVaursKkcufDeSo3oMiHSUDFrbIPkyDPdGBHnmsYC3riiaZBi5JZBN0SoXhL6Kdb5iPPHVjB';

	// page id
	$pageId = '103296775690978';

	// instagram business account id
	$instagramAccountId = '17841447930157103';
?>
```
In order for this to work, I need to pre-register my access token, the page id and the instagram business account id in the defines file. This is needed because the application is not public for the users. Meaning that only I can use the application. If any other user tries to log in with their Facebook account, they won't be able to get the posts nor any other data because they do not have their acces token and any other data. Hence is the reason why I am the only tester for now. I am currently finding a way so that many users can register and use the app.

<h3>Posts Page</h3>
The posts page is the page where the users will be able to see all their posts and also its data. This page can be only reached when the user is authenticated. The users will see the general information of their instagram account. Meaning that they will see their:
	
	- instagram ID
	- number of followers 
	- number of followings
	- number of posts
