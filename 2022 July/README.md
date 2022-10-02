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
	
