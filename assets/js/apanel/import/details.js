$(document).ready(function () {
	var m_sel = $('select[name="model_select"]');

	$('select[name="vendors"]').bind({
		change: function(){
			var s = $(this),
				sv = s.val();
			if (sv > 0) {
				$.getJSON(app.urls.getVendorModels + sv, function(resp){
					console.log(m_sel);
					m_sel.html('<option value="0" selected="selected"> - </option>' + resp.join());
				});
			}
		}
	});
});