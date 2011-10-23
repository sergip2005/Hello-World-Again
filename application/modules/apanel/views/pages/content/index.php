<div class="regions top-icons tar">
	<a id="create-region" class="icon-container" href="/apanel/content/editor" title="Создать новую страницу">
		<span class="ui-icon ui-icon-plusthick"></span>
	</a>
</div>
<div id="pages-list">
	<ul id="pages">
	<?php foreach ($pages as $p) { ?>
		<li>
			<a href="/apanel/content/editor/<?php echo $p['id'] ?>" id="p<?php echo $p['id'] ?>">
				<span class="name"><?php echo $p['title']; ?></span>
			</a>
		</li>
	<?php } ?>
	</ul>
</div>

 
