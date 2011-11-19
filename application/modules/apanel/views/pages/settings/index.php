<form action="/apanel/settings/save/" method="post" autocomplete="false">
	<table class="settings">
		<tr>
			<td class="title">Курс евро:</td>
			<td class="value">
				<input class="text w50" type="text" value="<?php echo $config['currency_eur'] ?>" name="currency_eur" />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="controls"><input type="submit" value="Сохранить" /></td>
		</tr>
	</table>
</form>