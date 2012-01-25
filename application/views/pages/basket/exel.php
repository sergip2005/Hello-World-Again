<?php
if (!$basket) exit;
// разкомментируйте строки ниже, если файл не будет загружаться
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");

//стандартный заголовок, которого обычно хватает
header('Content-Type: text/x-csv; charset=utf-8');
header("Content-Disposition: attachment;filename=".date("d-m-Y")."-export-price.xls");
header("Content-Transfer-Encoding: binary ");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="author" content="Andrey" />
<title>deamur zapishi.net</title>
</head>
<body>
<table border="1">
	<thead>		
		<tr>			
			<th>Тип</th>
			<th>Парт-<br>номер</th>			
			<th>Описание(eng)</th>
			<th>Описание(рус)</th>
			<th>Кол-во</th>
			<th>Цена, грн</th>
			
		</tr>
	</thead>
	<tbody>
		<?php foreach ($basket as $c):?>
		<tr>			
			<td><?php echo $c['ptype'] ?></td>
			<td><?php echo $c['code'] ?></td>			
			<td><?php echo $c['name'] ?></td>
			<td><?php echo $c['name_rus'] ?></td>
			<td><?php echo $c['min_num'] ?></td>
			<td> <?php echo str_replace('.',',',$c['price'])?></td>			
		</tr>
		<?php endforeach;?>
	<tbody>
</table>
</body>
</html>