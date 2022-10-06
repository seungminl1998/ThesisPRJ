<?php 	session_start();?>
<!DOCTYPE html>
<head>
<link rel="stylesheet" href="css/captionB.css"/>
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
        function disableBack() { window.history.forward(); }
        setTimeout("disableBack()", 0);
        window.onunload = function () { null };
</script>
    <style>
        #a{
            font-family: stein;
    text-align: center;
    font-size: 30px;
        }
        #b{
            font-family: 'avenir';
    text-align: center;
        }
        #c{
            width: 300px;
    height: 300px;
    margin-left: 532px;
    margin-top: 347px;
        }
        @media only screen and (max-width: 600px) {
            #a{
            font-family: stein;
    text-align: center;
    font-size: 30px;
        }
        #b{
            font-family: 'avenir';
    text-align: center;
        }
        #c{
            width: 300px;
    height: 300px;
    margin-left: 42px;
    margin-top: 293px;
        }
}
    </style>
</head>
<html>
    <body id = "e">
        <div id = "c">
            <div id = "a">Welcome to Inseo</div>
            <div id = "b">The Page is Loading...</div>
        </div>
    </body>
<script>
    const divs = $("#e");
    divs.click(function() {
	window.location.href = 'https://www.inseo.co.kr/inseo/getPosts.php';
});
    </script>
</html>