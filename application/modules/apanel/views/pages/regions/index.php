regions_html_list
<script src="/assets/js/libs/underscore-min.js"></script>
<script src="/assets/js/app.js"></script>
<script src="/assets/js/script.js"></script>
<script src="/assets/js/plugins.js"></script>
<script src="/assets/js/apanel/regions.js"></script>
<div class="regions top-icons tar">
	<a id="create-region" class="icon-container" href="#" title="Создать новый регион">
		<span class="ui-icon ui-icon-plusthick"></span>
	</a>
</div>

<div id="regions-list">
	<ul id="regions">
		<?php foreach ($regions as $region) { ?>
		<li>
			<span id="v<?php echo $region['id']; ?>">
				<span class="remove-item icon-container fr" title="Удалить регион '<?php echo $region['name']; ?>'">
					<span class="ui-icon ui-icon-close"></span>
				</span>
				<span class="edit-item icon-container fr" title="Редактировать регион '<?php echo $region['name']; ?>'">
					<span class="ui-icon ui-icon-pencil"></span>
				</span>
				<span class="name"><?php echo $region['name']; ?>
				</span>
				(по умолчанию
                <input type="radio" class="region-default" name="default" value="<?php echo $region['id']; ?>"<?php echo $region['default'] == 1 ?  'checked' : "" ?>>)
			</span>
		</li>
		<?php } ?>
	</ul>
</div>