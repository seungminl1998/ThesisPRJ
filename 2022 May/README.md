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

<h3>Posts Page</h3>
The Posts Page is the second page that the users will see after logging in using Facebook. In this page, I am using the Facebook Graph API in order to get the following datas:

   - **Instagram ID**
   - **Number of Followers**
   - **Number of Followings**
   - **Insights of each Post**


This can be done after I grant access to the application to get data from my Instagram Account. This is done in the facebook developers page. Before this, in order to get the data correctly, I need to correctly fill in the data in the defines file. The explanation about the defines file will be done later on.
After getting all the data from Instagram and Facebook, I use the previously designed page and fill in the data. The next step for this page is to make the popup screen and make the user be able to check the insights of each post. 

<h3>Hashtags Page</h3>
The Hashtags Page is the third page that the users will see when the Hashtag menu is clicked which is in the top part of the.
 
