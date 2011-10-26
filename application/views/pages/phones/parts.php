<script>
$(document).ready(function() {
$('.s').click(function() {
    $('.cabinet').show();
	$('.solder').hide();
	$('.c').removeClass("selected");
	$('.s').addClass("selected")
    return false;
  });
$('.c').click(function() {
    $('.solder').show();
	$('.cabinet').hide();
	$('.s').removeClass("selected");
	$('.c').addClass("selected")
    return false;
  });
});
</script>
<?php
if(count($parts) > 0){
	echo '<div id="parts">
		<span class="c selected">Паечные </span>
		<span class="s">Корпусные </span>
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
			<td colspan="5" class="helpHed">Корпусные элементы</td>
		</tr>
		<tr>
			<td class="helpHed">Код(parts.code)</td>
			<td class="helpHed">Испол.(phones_parts.num)</td>
			<td class="helpHed">Описание eng(parts.name)</td>
			<td class="helpHed">Описание рус(parts.name_rus)</td>
			<td class="helpHed">Цена(parts.price)</td>
		</tr>';

		foreach ($cabinet as $c)
		{
			echo '<tr>
			<td>' . $c['code'] . '</td>
			<td>' . $c['num'] . '</td>
			<td>' . $c['name'] . '</td>
			<td>' . $c['name_rus'] . '</td>
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
			<td colspan="5" class="helpHed">Паечные элементы</td>
		</tr>
		<tr>
			<td class="helpHed">Код(parts.code)</td>
			<td class="helpHed">Испол.(phones_parts.num)</td>
			<td class="helpHed">Описание eng(parts.name)</td>
			<td class="helpHed">Описание рус(parts.name_rus)</td>
			<td class="helpHed">Цена(parts.price)</td>
		</tr>';

		foreach ($solder as $s)
		{
			echo '<tr>
			<td>' . $s['code'] . '</td>
			<td>' . $s['num'] . '</td>
			<td>' . $s['name'] . '</td>
			<td>' . $s['name_rus'] . '</td>
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
 
