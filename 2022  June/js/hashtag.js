const $home = $("#home");
const $hash = $("#hashtag");
const $captionB = $("#captionB");
const $tagb = $("#tagBag");
const $tagPOP = $("#tags");
const $close = $(".closeds");
const $cerrar = $(".cerrar");
const $taginf = $(".tagsInfo");
const $items= $(".items");

$home.click(function(){
  return window.location.assign('getPosts.php');
})
$hash.click(function(){
  return window.location.assign('hashtag.php');
})
$captionB.click(function(){
  return window.location.assign('caption.php');
})
$tagb.click(function(){
  $tagPOP.fadeIn(100);
  $tagPOP.css("display", "grid");
})
$close.click(function(){
  $tagPOP.fadeOut(100);
})
$cerrar.click(function(){
  $taginf.fadeOut(200)
})

$items.click(function(event){
  $taginf.fadeOut(100);
  var thenombre = event.target.querySelector('p').getAttribute('name');
  $('div[id="'+thenombre+'"]').fadeIn(100);
  $('div[id="'+thenombre+'"]').css("display", "grid");
})
