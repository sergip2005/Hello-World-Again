<?php
	$pages_html = generate_pagination_html($search_params['pagination'], '/apanel/users/%page%/');
?>
<div class='mainInfo'>

	<div class="pages top"><?php echo $pages_html ?></div>

	<table cellpadding=0 cellspacing=10 class="tablesorter">
		<thead>
			<tr>
				<th>Имя</th>
				<th>Фамилия</th>
				<th>Email</th>
				<th>Группа</th>
				<th>Статус</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($users as $user):?>
			<tr>
				<td><?php echo $user['first_name']?></td>
				<td><?php echo $user['last_name']?></td>
				<td><?php echo $user['email'];?></td>
				<td><?php echo $user['group_description'];?></td>
				<td><?php echo ($user['active']) ? anchor('apanel/users/deactivate/' . $user['id'], 'Активный') : anchor('apanel/users/activate/'. $user['id'], 'Неактивный'); ?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>

	<div class="pages bottom"><?php echo $pages_html ?></div>

	<p><a href="<?php echo site_url('apanel/users/create_user');?>">Создать нового пользователя</a></p>

</div>