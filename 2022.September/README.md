<h1>September 2022, UPDATES</h1>
In this month's update, I made many changes to the hashtag page. In the last version I wrote down that I wanted to add more features to the hashtag page because the search bar became useless when I changed to Google Trends API. I also wanted to make the users be able to do research about the keyword that they were interested too. So I decided to re-subscribe to the Hashtag API used before and also now, InSEO can get the most popular posts to the related keyword from Instagram. For instance, if the users type "coding" in the search bar and click the search button, they will get two pop-ups. One pop-up will be containing the most popular posts related to "coding" in Instagram and the other pop-up will show the suggested or related keywords with the number of posts.

<h2>Amount of money used until now</h2>

| ITEM | COST |
|  :---: |  :---: |
| Domain  | ₩ 20,000  |
| SSL  | ₩ 48,000  |
| Hosting Service  | ₩ 160,000  |
| Google Trends API  | ₩ 7,000 per Month  |
| Hashtag API  | ₩ 5,000 per Month  |
| **TOTAL**  | **₩ 247,000**  |

	I re-subscribed to the Hashtag API

<h3>Hashtag Page</h3>
When explaining the changes made in the Hashtag Page, I will separate the code in chunks because the code is too long.

```php
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
                          $_SESSION['hashtagId'] = $hashtagSearch['data'][0]['id'];
                          header("Location:hashtag4.php");
                          ob_end_flush(); 
                        }
                      }
            ?>
```
The code above is used to get the most popular posts regarding the hashtag that the user searched.
