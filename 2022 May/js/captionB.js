const $home = $("#home");
const $hash = $("#hashtag");
const $captionB = $("#captionB");

$home.click(function(){
  return window.location.assign('getPosts.php');
})
$hash.click(function(){
  return window.location.assign('hashtag.html');
})
$captionB.click(function(){
  return window.location.assign('caption.php');
})
