regions_html_list
<script src="/assets/js/libs/underscore-min.js"></script>
<script src="/assets/js/app.js"></script>
<script src="/assets/js/script.js"></script>
<script src="/assets/js/plugins.js"></script>
<script src="/assets/js/apanel/regions.js"></script>
<div class="top-icons tar clearfix">
	<form class="fl" id="search-region">
		<input type="text" name="text" class="text" />
		<input type="submit" value="Искать" />
		<input type="button" id="cancel-search-region" value="Отменить" />
	</form>
	<a id="create-region" class="icon-container" href="#" title="Создать новый регион">
		<span class="ui-icon ui-icon-plusthick"></span>
	</a>
</div>
<div id="regions-list" class="v-expand">
	<ul id="regions">
	<?php foreach ($regions as $r) { ?>
		<li>
			<a href="#" id="c<?php echo $r['id'] ?>">
				<span class="name"><?php echo $r['name'] ?></span>
            </a>
		</li>
	<?php } ?>
	</ul>
</div>
<div class="bottom-icons tar">
	<a id="iregions-list" class="icon-container decrease-action" href="#" title="Уменьшить область отображения">
		<span class="ui-icon ui-icon-arrowstop-1-n"></span>
	</a>
	<a id="dregions-list" class="icon-container increase-action" href="#" title="Увеличить область отображения">
		<span class="ui-icon ui-icon-arrowstop-1-s"></span>
	</a>
</div>

<div id="selected-item-info"></div>