<div class='mainInfo'>

	<div class="pageTitle">Войти</div>
	<div class="pageTitleBorder"></div>
	<p>Введите ваш адрес электронной почты и пароль в поля ниже.</p>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
	<?php echo form_open("auth/login");?>
	<input type="hidden" name="basket" value="1"/>
	<p>
		<label for="email">Email:</label>
		<?php echo form_input($email);?>
	</p>

	<p>
		<label for="password">Пароль:</label>
		<?php echo form_input($password);?>
	</p>

	<p>
		<label for="remember">Запомнить меня:</label>
		<?php echo form_checkbox('remember', '1', FALSE);?>
	</p>

	<p><?php echo form_submit('submit', 'Войти');?></p>

	<?php echo form_close();?>

</div>