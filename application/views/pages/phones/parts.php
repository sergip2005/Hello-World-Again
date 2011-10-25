<?php
	foreach ($parts as  $key=>$row)
	{
		echo '<ul>';
		echo '<li>' . $row['parts'] . ', ' . $row['type'] . '</li>';
		echo '</ul>';
	}
 
