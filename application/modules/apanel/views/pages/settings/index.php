<form action="/apanel/settings/save/" method="post" autocomplete="false">
	<table class="settings">
		<tbody>
			<tr><td><h3>Общие настройки:</h3></td></tr>
			<tr>
				<td class="title">Курс евро:</td>
				<td class="value">
					<input class="text w50" type="text" value="<?php echo isset($config['currency_eur']) ? floatval($config['currency_eur']) : 10.8 ?>" name="currency_eur" />
				</td>
			</tr>
			<tr>
				<td class="title">Разрешить кеширование<br>(0 - запретить,<br>1 - разрешить):</td>
				<td class="value">
					<input class="text w50" type="text" value="<?php echo isset($config['cache_enabled']) ? intval($config['cache_enabled']) : 1 ?>" name="cache_enabled" />
				</td>
			</tr>
			<tr>
				<td class="title">Время жизни кеша, секунд:</td>
				<td class="value">
					<input class="text w50" type="text" value="<?php echo isset($config['cache_live_time']) ? intval($config['cache_live_time']) : 300 ?>" name="cache_live_time" />
				</td>
			</tr>
			<tr>
				<td class="title">Кол-во элементов на странице:</td>
				<td class="value">
					<input class="text w50" type="text" value="<?php echo isset($config['per_page']) ? intval($config['per_page']) : 200 ?>" name="per_page" />
				</td>
			</tr>
		</tbody>

		<tbody>
			<tr><td><h3>Настройки импорта:</h3></td></tr>
			<tr>
				<td class="title">Кол-во столбцов для импорта данных:</td>
				<td class="value">
					<input class="text w50" type="text" value="<?php echo isset($config['import_max_cols_num']) ? intval($config['import_max_cols_num']) : 20 ?>" name="import_max_cols_num" />
				</td>
			</tr>
			<tr>
				<td class="title">Кол-во строк для предварительного просмотра данных в листе при импорте:</td>
				<td class="value">
					<input class="text w50" type="text" value="<?php echo isset($config['import_demo_rows_num']) ? intval($config['import_demo_rows_num']) : 3 ?>" name="import_demo_rows_num" />
				</td>
			</tr>
		</tbody>

		<tfoot>
			<tr>
				<td colspan="2" class="controls"><input type="submit" value="Сохранить" /></td>
			</tr>
		</tfoot>
	</table>
</form>