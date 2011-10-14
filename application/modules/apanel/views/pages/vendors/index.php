vendors_html_list
<script src="/assets/js/libs/underscore-min.js"></script>
<script src="/assets/js/app.js"></script>
<script src="/assets/js/script.js"></script>
<script src="/assets/js/plugins.js"></script>
<script src="/assets/js/apanel/vendors.js"></script>
<div class="top-icons tar clearfix">
	<form class="fl" id="search-vendor">
		<input type="text" name="text" class="text" />
		<input type="submit" value="Искать" />
		<input type="button" id="cancel-search-vendor" value="Отменить" />
	</form>
	<a id="create-vendor" class="icon-container" href="#" title="Создать нового поставщика">
		<span class="ui-icon ui-icon-plusthick"></span>
	</a>
</div>
<div id="vendors-list" class="v-expand">
	<ul id="vendors">
	<?php foreach ($vendors as $v) { ?>
		<li>
            <input type="checkbox" class="vendor-show fl" name="show" value="<?php echo $v['show']; ?>"<?php echo $v['show'] == 1 ?  'checked' : "" ?>>
			<a href="#" id="c<?php echo $v['id'] ?>">
				<span class="name"><?php echo $v['name'] ?></span>
            </a>
		</li>
	<?php } ?>
	</ul>
</div>
<div class="bottom-icons tar">
	<a id="ivendors-list" class="icon-container decrease-action" href="#" title="Уменьшить область отображения">
		<span class="ui-icon ui-icon-arrowstop-1-n"></span>
	</a>
	<a id="dvendors-list" class="icon-container increase-action" href="#" title="Увеличить область отображения">
		<span class="ui-icon ui-icon-arrowstop-1-s"></span>
	</a>
</div>

<div id="selected-item-info"></div>