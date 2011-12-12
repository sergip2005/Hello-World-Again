<ul>
<?php foreach ($catalog as  $vendor => $models) { ?>
	<li><a href="/phones/<?php echo url_title($vendor, 'underscore', TRUE) ?>"><?php echo $vendor ?></a>
	<?php if (count($models) > 0) { ?>
		<ul>
		<?php foreach ($models as $k => $m) { ?>
			<li><a href="/phones/<?php echo url_title($vendor, 'underscore', TRUE) ?>/<?php echo url_title($m, 'underscore', TRUE) ?>"><?php echo $m ?></a></li>
		<?php } ?>
			<li><a href="/phones/<?php echo url_title($vendor, 'underscore', TRUE) ?>/none">Несортированные</a></li>
		</ul>
	<?php } ?>
	</li>
<?php } ?>
</ul>