<?php if ($this->ion_auth->logged_in()) { ?>
	<?php if ($this->ion_auth->is_admin()) { ?>

<ul>
	<li>
		<a href=""><?php echo $this->session->userdata('email') ?></a>
		<ul>
			<li><a href="/apanel/">Админ панель</a></li>
			<li><a href="/logout/">Выйти</a></li>
			<li><a href="/change_password/">Изменить пароль</a></li>
			<li><a href="/reset_password/">Обнулить пароль</a></li>
			<li><a href="/activate/">Активировать</a></li>
		</ul>
	</li>
</ul>

	<?php } else { ?>

<ul>
	<li>
		<a href=""><?php echo $this->session->userdata('email') ?></a>
		<ul>
			<li><a href="/logout/">Выйти</a></li>
			<li><a href="/change_password/">Сменить пароль</a></li>
			<li><a href="/reset_password/">Обнулить пароль</a></li>
			<li><a href="/activate/">Активировать</a></li>
		</ul>
	</li>
</ul>

	<?php } ?>

<?php } else { ?>

<ul>
	<li><a href="/login/">Войти</a></li>
	<li><a href="/register/">Зарегистрироваться</a></li>
	<li><a href="/forgot_password/">Забыли пароль?</a></li>
</ul>

<?php } ?>