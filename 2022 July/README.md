<h1>July 2022, UPDATES</h1>
In this month's update, I fixed some bugs of the project and also I made a plan to make the users be able to register to the app. In the last version, I was the only person who could use the application. As a reminder, I will be attaching the code below.

```php
<?php 
	$accessToken = 'EAAtumlbc53YBAJ9oL77KVpS1F6JmnwCjCXkvBTEiQDI2LE2aaEnjuCFqHB4FEXwoE15UlQZAhZBYhwiG7k3HqiQCOIYqlkGJZBRvnoaUjqGuLIVaursKkcufDeSo3oMiHSUDFrbIPkyDPdGBHnmsYC3riiaZBi5JZBN0SoXhL6Kdb5iPPHVjB';

	// page id
	$pageId = '103296775690978';

	// instagram business account id
	$instagramAccountId = '17841447930157103';
?>
```
I had to write the access token, the page ID and the instagram account ID for the application to work. I had to pre-defined them in the defines file. The defines file is the file that is used throughout the entire project because it contains the endpoints needed to get the data from the Facebook Graph API. Hence is the reason why I was the only one who could use the application. However, I found a way to make other users be able to register and also make the application make some profit. In this version of the application I made a system so that user could buy the program for the access token, page id and the instagram business account id. In order for the users to get those variables, they need to be registered in the developers page by the developer. The users who wants to use the program would pay the developer and the developer would register them in the program. When the users get registered in the program, they will be able to get the access token, page ID and the instagram business ID. With this, I could build a business model also. The image below shows the business model of InSEO.
![Untitled-2](https://user-images.githubusercontent.com/101083759/193437613-724091bd-d8b8-483e-8d3c-3bcba56bb877.png)

	Until now, the user's access token, instagram business account ID and page ID was brought from the defines file.<br>
	But now, those information will be brought from the database.
	
Before being able to register, the users must send us an email saying that they want to use the application. Then, we will send them the information of where they need to send the money and what they need to do. The code below is the code for it.

```php
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
```
As always, there is a form with an input box and a submit button. The user must enter a valid email that we can contact. When the email is entered, it will be saved in the database and we will send them the email. 

```html
<script>
      function checkMail(names){
        var validate = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/); //Check if it is in email format.
        if(names.length <= 0){
        }else if(names.length > 0 && !validate.test(names)){
        }else{
          return true;
        }
      }
</script>
```
The code above is the jQuery to check if the email is written in the correct format. The submit button will activate if the email is written in the correct form. In order to make this work I used the regular expression to keep track of the string sequence. <br><br>
After the users receive the email and follow all the steps, they will be able to get the information to register. Then, they will start the registration process. The code below shows how the data is saved in the database. 

```php
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
```
The code above runs when the submit button is clicked. We can do that by using the isset funciton. We check if the submit button is clicked and if it was clicked we start inserting the data into the database. First we get all the data that were typed in each registration field. We can get all those data using the $_REQUEST function. After getting the values, we start a database query and start inserting the data into the corresponding field. Moreover, we create another 2 more queries that will create 2 tables each containing the tags and the captions of the user.

	One important point here is that the submit button to complete the registration will not be active until all fields are filled correctly.
