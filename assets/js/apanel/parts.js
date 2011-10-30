$(document).ready(function() {
$('.s').click(function() {
    $('.cabinet').show();
	$('.solder').hide();
	$('.c').removeClass("selected");
	$('.s').addClass("selected")
    return false;
  });
$('.c').click(function() {
    $('.solder').show();
	$('.cabinet').hide();
	$('.s').removeClass("selected");
	$('.c').addClass("selected")
    return false;
  });
 $('a#demo').jqzoom({zoomType: 'drag'});
});
