<?php
echo '<ul style=" float: left; margin: 15px;">';
	foreach ($catalog as  $key => $model)
	{
		echo'<li>' . $key;
		if (count($model) > 0) {
			echo '<ul>';
			foreach ($model as $k => $m)
			{
				echo '<li><a href="/phones/' .  strtolower($key) . '/' . strtolower(str_replace(' ', '_', $m)) . '">' . $m . '</a></li>';
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	echo '</ul>';
echo '<a href="/assets/images/testimg/E75_RM-412_RM-413_Schematics_v0_1.png" id="demo" title="E75_RM-412_RM-413_Schematics_v0_1">
    <img src="/assets/images/testimg/small_E75_RM-412_RM-413_Schematics_v0_1.png" style="border: solid 1px #999;" title="E75_RM-412_RM-413_Schematics_v0_1">
</a>
<div class="clearfix"></div>';

if(count($parts) > 0){
	echo '<div id="parts">
		<span class="s selected">Корпусные </span>
		<span class="c">Паечные </span>
	</div>';

	foreach ($parts as $row)
	{
		 if($row['type'] == 's'){
			 $solder[] = $row;
		 }else{
			 $cabinet[] = $row;
		 }
	}

	if(count($cabinet) > 0){
		echo '<div class="cabinet" ><table class="sofT" cellspacing="0">
		<tr>
			<td colspan="8" class="helpHed">Корпусные элементы</td>
		</tr>
		<tr>
			<td class="helpHed">Позиция (phones_parts.cct_ref)</td>
			<td class="helpHed">Код (parts.code)</td>
			<td class="helpHed">Испол.(phones_parts.num)</td>
			<td class="helpHed">Описание eng(parts.name)</td>
			<td class="helpHed">Описание рус(parts.name_rus)</td>
			<td class="helpHed">Кол.</td>
			<td class="helpHed">Мин. кол.(phones_parts.min_num)</td>
			<td class="helpHed">Цена(parts.price)</td>
		</tr>';

		foreach ($cabinet as $c)
		{
			echo '<tr>
			<td>' . $c['cct_ref'] . '</td>
			<td>' . $c['code'] . '</td>
			<td>' . $c['num'] . '</td>
			<td>' . $c['name'] . '</td>
			<td>' . $c['name_rus'] . '</td>
			<td></td>
			<td>' . $c['min_num'] . '</td>
			<td>' . $c['price'] . '</td>
			</tr>';
		}

		echo '</table></div>';
	}else{
		echo 'Нет паечных запчастей';
	}

	if(count($solder) > 0){
		echo '<div class="solder"><table class="sofT" cellspacing="0">
		<tr>
			<td colspan="8" class="helpHed">Паечные элементы</td>
		</tr>
		<tr>
			<td class="helpHed">Позиция (phones_parts.cct_ref)</td>
			<td class="helpHed">Код (parts.code)</td>
			<td class="helpHed">Испол.(phones_parts.num)</td>
			<td class="helpHed">Описание eng(parts.name)</td>
			<td class="helpHed">Описание рус(parts.name_rus)</td>
			<td class="helpHed">Кол.</td>
			<td class="helpHed">Мин. кол.(phones_parts.min_num)</td>
			<td class="helpHed">Цена(parts.price)</td>
		</tr>';

		foreach ($solder as $s)
		{
			echo '<tr>
			<td>' . $s['cct_ref'] . '</td>
			<td>' . $s['code'] . '</td>
			<td>' . $s['num'] . '</td>
			<td>' . $s['name'] . '</td>
			<td>' . $s['name_rus'] . '</td>
			<td></td>
			<td>' . $s['min_num'] . '</td>
			<td>' . $s['price'] . '</td>
			</tr>';
		}

		echo '</table></div>';
	}else{
		echo 'Нет корпусных запчастей';
	}

}else{
	echo 'Нет запчастей';
}
 
