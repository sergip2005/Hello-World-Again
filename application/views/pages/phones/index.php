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
		foreach ($model as $k => $m)
		{
			if($k  == 0) echo '<ul>';
			echo '<li><a href="">' . $m . '</a></li>';
			if($k + 1 == count($model)) echo '</ul>';
		}
		echo '</li>';
	}
	echo '</ul>';

?>