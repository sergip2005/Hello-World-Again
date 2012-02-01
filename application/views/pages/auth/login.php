<div class='mainInfo'>

	<p>Введите ваш адрес электронной почты и пароль в поля ниже.</p>

	<div id="infoMessage"><?php echo $message;?></div>

	<?php echo form_open("auth/login");?>
	<input type="hidden" name="basket" value="1"/>
	<table>
		<tr>
			<td><label for="email">Email:</label></td>
			<td><?php echo form_input($email);?></td>
		</tr>

		<tr>
			<td><label for="password">Пароль:</label></td>
			<td><?php echo form_input($password);?></td>
		</tr>

		<tr>
			<td><label for="remember">Запомнить меня:</label></td>
			<td><?php echo form_checkbox('remember', '1', TRUE);?></td>
		</tr>

		<tr>
			<td colspan="2"><?php echo form_submit('submit', 'Войти');?></td>
		</tr>
	</table>

	<?php echo form_close();?>

</div>