<form method="post" action="/apanel/content/save">
<table>
	<tr>
		<td>Название:</td><td><input type="text"  value="<?php echo isset($page['title']) ? $page['title'] : '' ?>" name="title" /></td>
		<td>Дата создания:</td><td><?php echo isset($page['created']) ? $page['created'] : '' ?></td>
	</tr>
	<tr>
		<td>URI:</td><td><input type="text" value="<?php echo isset($page['uri']) ? $page['uri'] : '' ?>" name="uri" /></td>
		<td>Дата последнего редактирования:</td><td><?php echo isset($page['last_edited']) ? $page['last_edited'] : ''  ?></td>
	</tr>
	<tr>
		<td>Тип страницы:</td>
		<td><select name="type">
		<option value="0"<?php echo $page['type'] == 0 ? 'selected=\'selected\'' : '' ?>>статическая</option>
		<option value="1"<?php echo $page['type'] == 1 ? 'selected=\'selected\'' : '' ?>>динамическая</option>
		</select></td>
		<td>Не действителен с:</td><td><?php echo isset($page['disabled']) ? $page['disabled'] : '' ?></td>
	</tr>
	<tr>
		<td>Ключевые слова:</td><td><textarea id="keywords" name="keywords" rows="3" cols="39" ><?php echo isset($page['keywords']) ? $page['keywords'] : '' ?></textarea></td>
		<td></td><td></td>
	</tr>
	<tr>
		<td>Описание:</td><td><textarea id="description" name="description" rows="3" cols="39" ><?php echo isset($page['description']) ? $page['description'] : '' ?></textarea></td>
		<td></td><td></td>
	</tr>
	<tr>
		<td></td>
		<td colspan = "4">
		<textarea id="body"class="body" name="body" rows="15" cols="80" style="width: 80%">
		<?php echo isset($page['body']) ? htmlspecialchars($page['body']) : '' ?>
		</textarea>
		</td>
	</tr>
	<tr>
		<td colsan = "4"><input type="submit" name="save" value="Submit" /><br /></a><input type="submit" name="cancel" value="Cancel" /></td>
	</tr>
</table>
<input type="hidden" name="id" value="<?php echo isset($page['id']) ? $page['id'] : '' ?>" />
</form>
