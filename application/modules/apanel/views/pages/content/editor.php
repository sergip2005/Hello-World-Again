<div class="editor-form">
<form method="post" action="/apanel/content/save">
<table style="border-spacing: 8px;">
	<tr>
		<td class="label">Название:</td><td><input style="width: 85%;" type="text"  value="<?php echo isset($page['title']) ? $page['title'] : '' ?>" name="title" /></td>
		<td class="label2">Дата создания:</td><td class="label l"><?php echo isset($page['created']) ? $page['created'] : '' ?></td>
	</tr>
	<tr>
		<td class="label">URI:</td><td><input style="width: 85%;" type="text" value="<?php echo isset($page['uri']) ? $page['uri'] : '' ?>" name="uri" /></td>
		<td class="label2">Дата последнего редактирования:</td><td class="label l"><?php echo isset($page['last_edited']) ? $page['last_edited'] : ''  ?></td>
	</tr>
	<tr>
		<td class="label"><label for="status">Показывать страницу:</label></td>
		<td><input type="checkbox" id="status" name="status" value="1" <?php echo $page['status'] == 1 ? 'checked' : ''  ?>></td>
		<td class="label2">Не действителен с:</td><td class="label l"><?php echo isset($page['disabled']) ? $page['disabled'] : '' ?></td>
	</tr>
	<tr>
		<td class="label">Ключевые слова:</td><td colspan = "3"><textarea id="keywords" class="text" name="keywords" rows="2"><?php echo isset($page['keywords']) ? $page['keywords'] : '' ?></textarea></td>

	</tr>
	<tr>
		<td class="label">Описание:</td><td colspan = "3"><div><textarea id="description" class="text" name="description" rows="2"><?php echo isset($page['description']) ? $page['description'] : '' ?></textarea></div></td>

	</tr>
	<tr>

		<td colspan = "4">
		<textarea id="body"class="body" name="body" rows="15"  style="width: 100%">
		<?php echo isset($page['body']) ? htmlspecialchars($page['body']) : '' ?>
		</textarea>
		</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td><input style="margin-right:15px" type="submit" name="save" value="Сохранить" /><input type="submit" name="cancel" value="Отмена" /></td>
	</tr>
</table>
<input type="hidden" name="id" value="<?php echo isset($page['id']) ? $page['id'] : '' ?>" />
</form>
</div>