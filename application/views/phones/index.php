<?php
	if ($this->ion_auth->logged_in()) {
		echo '<pre>';
		print_r($this->ion_auth->get_user_array());
		echo '</pre>';
	} else {
		echo 'Вы не вошли на сайт';
	}

?>