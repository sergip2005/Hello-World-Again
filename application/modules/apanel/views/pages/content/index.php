<div class="regions top-icons tar">
	<a id="create-region" class="icon-container" href="/apanel/content/editor" title="Создать новую страницу">
		<span class="ui-icon ui-icon-plusthick"></span>
	</a>
</div>
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

 
