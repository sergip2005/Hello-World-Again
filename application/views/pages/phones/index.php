<?php
	/*if ($this->ion_auth->logged_in()) {
		echo '<pre>';
		print_r($this->ion_auth->get_user_array());
		echo '</pre>';
	} else {
		echo 'Вы не вошли на сайт';
	}*/

	echo '<ul>';
	foreach ($catalog as  $key => $model)
	{
		echo'<li>' . $key;
		if (count($model) > 0) {
			echo '<ul>';
			foreach ($model as $k => $m)
			{
				echo '<li><a href="phones/' .  strtolower($key) . '/' . strtolower(str_replace(' ', '_', $m)) . '">' . $m . '</a></li>';
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	echo '</ul>';
?>