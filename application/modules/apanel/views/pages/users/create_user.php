<div class='mainInfo'>

	<h1>Создание нового пользователя</h1>

	<p>Введите информацию в поля ниже:</p>

	<?php echo form_open("auth/create_user"); ?>
	<table>
		<tr>
			<td class="label">First Name:</td>
			<td class="value"><?php echo form_input($first_name);?></td>
		</tr>

		<tr>
			<td class="label">Last Name:</td>
			<td class="value"><?php echo form_input($last_name);?></td>
		</tr>

		<tr>
			<td class="label">Company Name:</td>
			<td class="value"><?php echo form_input($company);?></td>
		</tr>

		<tr>
			<td class="label">Email:</td>
			<td class="value"><?php echo form_input($email);?></td>
		</tr>

		<tr>
			<td class="label">Телефон:</td>
			<td class="value">
				<?php echo form_input($phone1);?><br /><br />
				<?php echo form_input($phone2);?><br /><br />
				<?php echo form_input($phone3);?>
			</td>
		</tr>

		<tr>
			<td class="label">Пароль:</td>
			<td class="value"><?php echo form_input($password);?></td>
		</tr>

		<tr>
			<td class="label">Подтверждение пароля:</td>
			<td class="value"><?php echo form_input($password_confirm);?></td>
		</tr>

		<tr>
			<td colspan="2"><?php echo form_submit('submit', 'Создать пользователя');?></td>
		</tr>

	</table>
	<?php echo form_close();?>

</div>
