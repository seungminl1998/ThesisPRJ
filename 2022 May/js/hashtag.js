const $home = $("#home");
const $hash = $("#hashtag");
const $captionB = $("#captionB");
const $tagb = $("#tagBag");
const $tagPOP = $("#tags");
const $close = $("#close");
const $cerrar = $("#cerrar");
const $taginf = $("#tagsInfo");
const $items= $(".items");

$home.click(function(){
  return window.location.assign('postAll.html');
})
$hash.click(function(){
  return window.location.assign('hashtag.html');
})
$captionB.click(function(){
  return window.location.assign('captionB.html');
})
$tagb.click(function(){
  $tagPOP.fadeIn(100);
  $tagPOP.css("display", "grid");
})
$close.click(function(){
  $tagPOP.fadeOut(100);
})
$cerrar.click(function(){
  $taginf.fadeOut(100)
  $taginf.css("display", "grid");;
})

$items.click(function(){
  $taginf.fadeIn(100);
  $taginf.css("display", "grid");
})
