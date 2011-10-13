// common actions to be fired on every page load
$(document).ready(function () {
	$('#loading').ajaxStart(function() {
			$(this).show();
		}).ajaxStop(function(){
			$(this).hide();
		});

	// init messages
	app.message = $('#message');
	app.messageContent = $('#message-content');
	// make flash messsage more handy
	app.message.contents('img.close').click(function () {
		app.message.fadeOut(500);
	});

	// init splash element
	app.splash = $('#splash').splash();
	app.popup = $('#popup');
	app.popupContent = $('#popup-content');
	app.popup.contents('img.close').click(function(){
		app.popup.add(app.splash).hide();
	});

	// increase\decrease windows actions
	$('.increase-action').click(function(e){
		e.preventDefault();
		$('#' + this.id.substr(1)).height('+=50px');
	});
	$('.decrease-action').click(function(e){
		e.preventDefault();
		$('#' + this.id.substr(1)).height('-=50px');
	});
});