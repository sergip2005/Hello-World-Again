<?php if ($this->ion_auth->logged_in()) { ?>
	<?php if ($this->ion_auth->is_admin()) { ?>

<ul>
	<li>
		<a href=""><?php echo $this->session->userdata('email') ?></a>
		<ul>
			<li><a href="/apanel/">админ панель</a></li>
			<li><a href="/logout">выйти</a></li>
			<li><a href="/change_password">изменить пароль</a></li>
			<li><a href="/reset_password">обнулить пароль</a></li>
			<li><a href="/activate">активировать</a></li>
		</ul>
	</li>
</ul>

	<?php } else { ?>

<ul>
	<li>
		<a href=""><?php echo $this->session->userdata('email') ?></a>
		<ul>
			<li><a href="/logout">logout</a></li>
			<li><a href="/logout">выйти</a></li>
			<li><a href="/change_password">изменить пароль</a></li>
			<li><a href="/reset_password">обнулить пароль</a></li>
			<li><a href="/activate">активировать</a></li>
		</ul>
	</li>
</ul>

	<?php } ?>

<?php } else { ?>

<ul>
	<li><a href="/login">login</a></li>
	<li><a href="/register">register</a></li>
	<li><a href="/forgot_password">forgot_password</a></li>
</ul>

<?php } ?>