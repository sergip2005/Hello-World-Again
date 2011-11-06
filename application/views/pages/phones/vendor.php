<div class="models-menu">
<ul>
	<?php foreach ($catalog as  $key => $model) { ?>
	<li><a href="/phones/<?php echo $key ?>"><?php echo $key ?></a>
	<?php if (count($model) > 0) { ?>
		<ul>
		<?php foreach ($model as $k => $m) { ?>
			<li><a href="phones/<?php echo strtolower($key) ?>/<?php echo strtolower(str_replace(' ', '_', $m)) ?>"><?php echo $m ?></a></li>
		<?php } ?>
		</ul>
	<?php } ?>
	</li>
	<?php } ?>
</ul>
</div>
<div class="models-menu">
	<ul>
	<?php foreach ($models as $m) {
		echo $m;
	 } ?>
</div>
 
