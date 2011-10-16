vendors_html_list
<script src="/assets/js/libs/underscore-min.js"></script>
<script src="/assets/js/app.js"></script>
<script src="/assets/js/script.js"></script>
<script src="/assets/js/plugins.js"></script>
<script src="/assets/js/apanel/vendors.js"></script>
<div class="vendors top-icons tar">
	<a id="create-vendor" class="icon-container" href="#" title="Создать нового поставщика">
		<span class="ui-icon ui-icon-plusthick"></span>
	</a>
</div>

<div id="vendors-list">
	<ul id="vendors">
		<?php foreach ($vendors as $vendor) { ?>
		<li>
			<span id="v<?php echo $vendor['id']; ?>">
				<span class="remove-item icon-container fr" title="Удалить поставщик '<?php echo $vendor['name']; ?>'">
					<span class="ui-icon ui-icon-close"></span>
				</span>
				<span class="edit-item icon-container fr" title="Редактировать поставщика '<?php echo $vendor['name']; ?>'">
					<span class="ui-icon ui-icon-pencil"></span>
				</span>
				<span class="name"><?php echo $vendor['name']; ?>
				</span>
				(активно
                <input type="checkbox" class="vendor-show" name="show" value="<?php echo $vendor['show']; ?>"<?php echo $vendor['show'] == 1 ?  'checked' : "" ?>>)
			</span>
		</li>
		<?php } ?>
	</ul>
</div>

 
