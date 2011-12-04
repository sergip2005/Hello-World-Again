<div class="import-results">

	<?php if (count($parts['new']) > 0) { ?>
		Парт-номера деталей, добавленные в этой версии (всего <?php echo count($parts['new']) ?>): <br><br>
		<?php echo implode(', ', array_keys($parts['new'])) ?><br><br>
	<?php } ?>

	<?php if (count($parts['existing']) > 0) { ?>
		Парт-номера деталей, данные о которых обновлены в этой версии (всего <?php echo count($parts['existing']) ?>): <br><br>
		<?php echo implode(', ', array_keys($parts['existing'])); ?><br><br>
	<?php } ?>

	<?php if (count($parts['excluded']) > 0) { ?>
		Парт-номера деталей, которые не были найдены в этой версии (всего <?php echo count($parts['excluded']) ?>): <br><br>
		<?php echo implode(', ', array_keys($parts['excluded'])); ?>
	<?php } ?>
</div>