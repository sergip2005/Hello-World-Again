<div id="main" class="parts-navigation">

	<div class="clearfix">
		<div class="vendors-list">
			<h3 class="title">Производители</h3>
			<ul id="vendors">
				<?php foreach ($vendors as $vendor) { ?>
					<li data-id="<?php echo $vendor['id'] ?>"><?php echo $vendor['name'] ?></li>
				<?php } ?>
			</ul>
		</div>

		<div class="models-list">
			<h3>Модели</h3>
			<ul id="models"></ul>
		</div>
	</div>
	<div id="parts" class="clearfix"></div>

</div>