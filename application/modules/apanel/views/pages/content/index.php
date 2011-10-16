content_html_list
<script type="text/javascript">
	$('#pages').delegate('a', 'click', function (e) {
		e.preventDefault();
		var  id = parseInt(this.id.substr(1), 10);
		$('input[name="page_id"]').val(id);
		$('#form').submit();
	});
</script>
<form method="post" id="form" action="/apanel/content/editor">
	<input type=hidden name="page_id" value="">
</form>
<div id="pages-list">
	<ul id="pages">
	<?php foreach ($pages as $p) { ?>
		<li>
			<a href="#" id="p<?php echo $p['id'] ?>">
				<span class="name"><?php echo $p['title']; ?></span>
			</a>
		</li>
	<?php } ?>
	</ul>
</div>

 
