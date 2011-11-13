<div class="vendors top-icons tar">
	<a id="create-vendor" class="icon-container" href="#" title="Создать нового вендора">
		<span class="ui-icon ui-icon-plusthick"></span>
	</a>
</div>

<div id="vendors-list">
	<ul id="vendors">
		<?php foreach ($vendors as $vendor) { ?>
		<li>
			<span id="v<?php echo $vendor['id']; ?>">
				<span class="remove-item icon-container fr" title="Удалить вендора '<?php echo $vendor['name']; ?>'">
					<span class="ui-icon ui-icon-close"></span>
				</span>
				<span class="edit-item icon-container fr" title="Редактировать вендора '<?php echo $vendor['name']; ?>'">
					<span class="ui-icon ui-icon-pencil"></span>
				</span>
				<span class="name"><?php echo $vendor['name']; ?>
				</span>
				<span class = "vendors-activity"><?php echo $vendor['show'] == 1 ? ' (активно' : ' (не активно' ?>
                <input type="checkbox" class="vendor-show" name="show" value="<?php echo $vendor['show']; ?>"<?php echo $vendor['show'] == 1 ?  'checked' : "" ?>>)</span>
				<?php
				$pathfile = '/assets/images/vendors/' . $vendor['name'] . '.png';
				if (file_exists($_SERVER{'DOCUMENT_ROOT'} . $pathfile)) {
					echo '<img src="' . $pathfile . '">';
				} else {
					echo '<span>Файл ' . $vendor['name'] . '.png' . ' не существует</span>';
				}
				?>
			</span>
		</li>
		<?php } ?>
	</ul>
</div>

 
