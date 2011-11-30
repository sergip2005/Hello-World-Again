<?php if ($imports !== false && count($imports) > 0) { ?>
<div class="available-imports">
	<h3>Данные, не сохранённые с предыдущих импортов:</h3>
	<ul>
	<?php foreach ($imports as $import) { ?>
		<li><a href="/apanel/import/process_details/<?php echo $import['import_id'] ?>/"><?php echo $import['import_data']['file'] ?> ( <?php echo implode(' | ', $import['import_data']['sheets_names']) ?> ) </a></li>
	<?php } ?>
	</ul>
</div>
<?php } ?>

<form action="/apanel/import/do_xls_upload/" method="post" enctype="multipart/form-data">
	Выберите файл для импорта <br />
	(после загрузки вам будет предложено ввести детали): <br />
	<input type="file" name="file" /> <br />
	<input type="submit" value="Отправить" />
</form>