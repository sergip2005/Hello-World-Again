<div id="search">
	<div class="select-and-input">
		<form name="search_parts_code">
			<div class="search_field search_code">
				<input class="text" placeholder="Парт-номер (6300314)" type="text" name="parts_code" value="<?php echo isset($search_params) ? ($search_params['parameter'] == 'part_code' ? $search_params['query'] : '') : '' ?>">
				<div class="button"></div>
				<input class="submit" type="submit" value="Sub">
				<input class="parameter" type="hidden" value="parts_code">
			</div>
		</form>
	</div>

	<div class="select-and-input">
		<form name="search_model_name">
			<div class="search_field search_model">
				<input class="text" type="text" placeholder="Модель (N8-00 RM-596)" name="model_name" value="<?php echo isset($search_params) ? ($search_params['parameter'] == 'models' ? $search_params['query'] : '') : '' ?>">
				<div class="button"></div>
				<input class="submit" type="submit" value="Sub">
				<input class="parameter" type="hidden" value="model_name">
			</div>
		</form>
	</div>

	<div class="select-and-input">
		<form name="search_parts_name">
		<div class="search_field search_name">
				<input class="text" type="text" placeholder="Название детали (Window Module)" name="parts_name" value="<?php echo isset($search_params) ? ($search_params['parameter'] == 'part_name' ? $search_params['query'] : '') : '' ?>">
				<div class="button"></div>
				<input class="submit" type="submit" value="Sub">
				<input class="parameter" type="hidden" value="parts_name">
			</div>
		</form>
	</div>
</div>