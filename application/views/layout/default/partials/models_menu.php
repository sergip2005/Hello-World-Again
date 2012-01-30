<div id="menu-vendors">
<ul class="vendors">
<?php foreach ($catalog as  $vendor => $models) { ?>
	<li class="vendor <?php echo $vendor == $vendor_obj['name'] ? ' active' : '' ?>"><a href="/phones/<?php echo url_title($vendor, 'underscore', TRUE) ?>"><?php echo $vendor ?></a>
	<?php if (count($models) > 0) { ?>
		<?php
			$curr_group = '';
			$prev_group = '';
		?>
		<ul class="groups">
		<?php foreach ($models as $k => $m) { ?>
				<?php if ($m[0] != $curr_group) { ?>

					<?php if ($curr_group !== '') { ?>
						</ul>
					</li>
					<?php } ?>

					<?php $curr_group = $m[0]; ?>

					<li class="group<?php echo !empty($model_obj['model']) && $m[0] == $model_obj['model'][0] ? ' active' : '' ?>">
						<a href="#"><?php echo $m[0] ?></a>
						<ul class="models">
				<?php } ?>
						<li class="model<?php echo $model_obj['model'] == $m ? ' active' : '' ?>"><a href="/phones/<?php echo url_title($vendor, 'underscore', TRUE) ?>/<?php echo url_title($m, 'underscore', TRUE) ?>"><?php echo $m ?></a></li>
		<?php } ?>
				</ul>
			</li>
			<li class="group unsorted<?php echo empty($model_obj['model']) ? ' active' : '' ?>"><a href="/phones/<?php echo url_title($vendor, 'underscore', TRUE) ?>/none">Несортированные</a></li>
		</ul>
	<?php } ?>
	</li>
<?php } ?>
</ul>
</div>
<div id="menu-groups"></div>
<div id="menu-models"></div>