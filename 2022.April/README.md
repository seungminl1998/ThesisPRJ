<h1>April 2022, UPDATES</h1>
I am making the front end of the <b>Application InSEO</b>.
InSEO is a service that will be made for instagram influencers however for this project, it will just be used by the creator, myself, because
an upgraded version of the API which is paid. However, the alogrithm that will calculate the post's grade will be there which can be used by other users
if they provide the creator the token and user ID.

The image below is an image of the home page of the service:

![Page1](https://user-images.githubusercontent.com/101083759/163997074-8aac57c6-31dc-4cb7-aa29-6c7f35e67442.PNG)

For this project I am using HTML, CSS, JQUERY, JSP, and MySQL.

**CHANGES IN PLAN**<br/>
In week#1, I tried to make the front end for my website.
I was wondering how I was going to finish this project and also started studying how to use the Facebook API.
Which then I learned that it would be more comfortable to use PHP for this project instead of JSP.
Hence, I decided to use PHP. <br/> 
Hence, I am going to use **HTML, CSS, JQUERY, PHP, and MySQL** in order to build this application. 

>The Fonts Used in this Project:
> 1. Avenir (Avenir Black, Avenir Italic, Avenir Roman, Avenir Book) 2. Stein

**The Index Page**<br/>
First I made the login page using PHP by using the design made previously. For the log-in, we are going to use the OAUTH from Facebook because instagram is from Facebook, currently called Meta. The next few days, I am going to be studying of how to make the log in connection correctly by using Facebook. In order to study this I am using the following link: <br/>
>https://developers.facebook.com/docs/instagram-basic-display-api/reference/media/

The index page starts with a header which contains the text "SKKU THESIS PROJECT". Then, it has the body which contains the name of the project and a short description with a start button. Lastly, it has a footer with the name of the project. When the user clicks start, the page will go to the posts page. The following code is used to make this happen:
```PHP
      <?php echo '<a id = "button" href="' . $loginUrl . '">
            START
        </a>';?>
 ```
I projected a link tag from html using PHP and added css on it so that it could look like a button. The link is pointing to the login URL which was predefined.

**The Caption Page**<br/>
The next page that I made was the caption page where the users will be able to save their captions. The reason why I made this was because people save their captions in their notes. In this page, users will be able to save and delete their captions before and after using it. This way, they will be able to have their captions, hashtags and posts insights in one page which wil give them more organization.
```HTML
      <form id = "bodyContainer">
        <input id = "mycap" placeholder="Write Caption" name = "caption"></input>
        <button id = "addCap">ADD CAPTION</button>
        <div class = "captions">1. Hello my name is seungmin and this is my thesis project</div>
      </form>
```
This is the most important code of the caption page. As it can be seen, I put the input and button tags inside a form tag. The next thing I am going to create is the PHP function to update the database so that it adds the inputed caption into the captions database. Moreover, the php will also call the elements from the database and add it into the div with the class captions. The reason why I made a form was because in order for the PHP contact the server, it needs a submit form.
Since I am in the design stage, I just made the form without the PHP.

**The Hashtag Page**<br/>
As mentioned above, the goal for this project is to make instagram influencers have a better organization of their current posts and also their future posts. In this page, the users will be able to search hashtags and also store their hashtags in their "hashtag bag". This will make the instagram influencer's life more comfortable because they will be able to **get every information they want to insert in their posts by just opening our application.**
```HTML
        <div id = "lists">
          <form id = "tagForm">
            <button class = "items" ><p class="tagsTITLE" name = "tagList">My Post Tag Number 1</p></button>
            <div class = "items">1</div>
            <div class = "items">1</div>
            <div class = "items">1</div>
            <div class = "items">1</div>
            <div class = "items">1</div>
          </form>
        </div>
```
This is the skeleton code that will be used to show the users the tag lists that they have saved in their account. I filled in the divs with 1 because this is just a designing stage. In the future stages, the PHP code will bring the elements from the database and replace the 1 to the actual tag list name. When the div is clicked, it will open another list which is in the code below this. 

```HTML
 <div id = "tagsInfo">
        <div id = "exTitle">
          <input type="text" placeholder="Write the Tag to add" id = "tagAd"></input>
          <button id = "plus" disabled = "disabled">add</button>
          <button id = "cerrar">close</button>
        </div>
        <div id = "tageo">
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#dfsdfS</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
          <p class = "oneMore">#ello</p>
        </div>
      </div>
```
As mentioned above, the user will click the group of tags that they want to open. When the div is clicked, the code above will start to show. The reason why it is filled of a random text is because this is just a skeleton code. In the future, the values will be brought from the database. Moreover, as it can be seen above, there is a form once again. This form will be use to update the database for hashtags saved. The users will be able to add hashtags in each hashtag group. The hashtag that is saved will be saved and will be presented to the user using a <p> tag with class oneMore. 

**The Posts Page**<br/>
The next page that I made was the post page where the users get to see their posts and the insights. The service is able to get the post data and insights using the Instagram Graph API. Moreover, the app also checks the insights and calculates the post's grade in order for the user to upgrade their post for more reach. Instagram influencers will then, be able to see the problems of their post more straight resulting to a better post optimization. 
```HTML
      <div id = "bodyContainer">
        <div id = "posts">
          <div class = "thep">
            <img class="imgs" src = "assets/test1.jpg">
            <div class = "hov">
              <p class = "thegradeP">Post Grade:</p>
              <p class = "pGrade">B</p>
            </div>
          </div>
        </div>
      </div>
```
The code above is where the user's posts will be shown with the corresponding grade. However, it is still not complete. I need to see how the Facebook Graph API works in order to finish the design.

Users will be able to move to each page by using the common menu in the top of the page. The menu will be composed of three pages.
  1. Posts Page
  2. Hashtag Page
  3. Caption Page
The code below shows the code for the menu.
```HTML
        <div id = "menu">
          <div id ="hme"><button id = "home">HOME</button></div>
          <div class = "dash">|</div>
          <div id = "hash"><button id = "hashtag">HASHTAG SEARCH</button></div>
          <div class = "dash">|</div>
          <div id = "caption"><button id = "captionB">CAPTION BANK</button></div>
        </div>
```
