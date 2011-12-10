<form action="/apanel/settings/save/" method="post" autocomplete="false">
	<table class="settings">
		<tr>
			<td class="title">Курс евро:</td>
			<td class="value">
				<input class="text w50" type="text" value="<?php echo floatval($config['currency_eur']) ?>" name="currency_eur" />
			</td>
		</tr>
		<tr>
			<td class="title">Разрешить кеширование<br>(0 - запретить,<br>1 - разрешить):</td>
			<td class="value">
				<input class="text w50" type="text" value="<?php echo intval($config['cache_enabled']) ?>" name="cache_enabled" />
			</td>
		</tr>
		<tr>
			<td class="title">Время жизни кеша, секунд:</td>
			<td class="value">
				<input class="text w50" type="text" value="<?php echo intval($config['cache_live_time']) ?>" name="cache_live_time" />
			</td>
		</tr>
		<tr>
			<td class="title">Кол-во элементов на странице:</td>
			<td class="value">
				<input class="text w50" type="text" value="<?php echo floatval($config['per_page']) ?>" name="per_page" />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="controls"><input type="submit" value="Сохранить" /></td>
		</tr>
	</table>
</form>