<div class="regions top-icons tar">
	<a id="create-region" class="icon-container" href="/apanel/content/editor" title="Создать новую страницу">
		<span class="ui-icon ui-icon-plusthick"></span>
	</a>
</div>
<div id="pages-list">
	<ul id="pages">
	<?php foreach ($pages as $p) { ?>
		<li>
			<?php if($p['type'] == 0) { ?>
			<a href="/<?php echo $p['uri']; ?>" target="blank" class="fr" title="Перейти на страницу <?php echo $p['uri']; ?>">
				<span style="display:block" class="ui-icon  ui-icon-arrowbold-e"></span>
			</a>
			<?php } ?>
			<a href="/apanel/content/editor/<?php echo $p['id'] ?>" id="p<?php echo $p['id'] ?>">
				<span class="name <?php echo $p['type'] == 0 ? 'b' : ''; ?>"><?php echo $p['title']; ?></span>
			</a>
		</li>
	<?php } ?>
	</ul>
</div>

 
