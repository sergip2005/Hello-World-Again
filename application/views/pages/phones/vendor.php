<?php if (is_array($models) && count($models) > 0) { ?>
<div class="models-menu">
	<ul>
	<?php foreach ($models as $model) {
		echo '<li id="' . $model['id'] . '"><a href="/phones/' . url_title($vendor, 'underscore', TRUE) . '/' . url_title($model['name'], 'underscore', TRUE) . '">' . $model['name'] . '</a></li>';
	 } ?>
	<li><a href="/phones/<?php echo url_title($vendor, 'underscore', TRUE) ?>/none">Несортированные</a></li>
	</ul>
</div>
<?php } ?>