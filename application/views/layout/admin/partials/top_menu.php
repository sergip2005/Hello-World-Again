<?php
	$c = $this->router->class;
	$m = $this->router->method;
?>
<ul>
	<li<?php echo $c == 'apanel' && $m == 'index' ? ' class="active"' : '' ?>><a href="/apanel/">Главная</a></li>
	<li<?php echo $c == 'import' && $m == 'index' ? ' class="active"' : '' ?>><a href="/apanel/import">Импорт</a></li>
	<li<?php echo $c == 'vendors' && $m == 'index' ? ' class="active"' : '' ?>><a href="/apanel/vendors">Вендоры</a></li>
	<li<?php echo $c == 'regions' && $m == 'index' ? ' class="active"' : '' ?>><a href="/apanel/regions">Регионы</a></li>
	<li<?php echo $c == 'content' ? ' class="active"' : '' ?>><a href="/apanel/content">Наполнение</a></li>
</ul>