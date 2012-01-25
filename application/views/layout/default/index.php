<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">

		<title><?php echo $title; ?></title>
		
		<meta name="description" content="<?php echo $description ? $description : '' ; ?>">
		<meta name="keywords" content="<?php echo $keywords ? $keywords : ''; ?>">
		<meta name="author" content="">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<!-- Mobile viewport optimized: j.mp/bplateviewport -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="/favicon.ico">
		<link rel="apple-touch-icon" href="/apple-touch-icon.png">

		<link rel="stylesheet" href="/assets/css/style.css?v=2">
		<?php
			if (isset($css) && count($css) > 0) {
				foreach ($css as $name) {
					echo '<link rel="stylesheet" href="/assets/css/' . $name . '?v=' . $this->config->item('css_version') . '">';
				}
			}
		?>

		<!-- Uncomment if you are specifically targeting less enabled mobile browsers
		<link rel="stylesheet" media="handheld" href="css/handheld.css?v=2">  -->

		<script src="/assets/js/libs/jquery.1.6.4.min.js"></script>
		<script src="/assets/js/site/script.js"></script>
		<?php
			if (isset($js) && count($js) > 0) {
				foreach ($js as $name) {
					echo '<script src="/assets/js/' . $name . '?v=' . $this->config->item('js_version') . '"></script>';
				}
			}
		?>
	</head>
	<body>
		<div class="container">
			<div class="header clearfix"><div class="wrapper clearfix"></div></div>

			<div class="main-menu clearfix"><div class="wrapper clearfix">
				<?php echo $top_menu ?>
			</div></div>
			<div class="search clearfix"><div class="wrapper clearfix">
				<?php echo $search ?>
			</div></div>
			<div class="basket">			
				<div id="basket">
				<?php if($count>0):?>
				<a href="/basket">
					Товаров в корзине <span><?php echo $count?></span>
				</a>
				<?php else:?>
					Корзина пуста
				<?php endif;?>				
				</div>
			</div>
			<div class="user-menu clearfix"><div class="wrapper clearfix">
				<?php echo $user_menu ?>
			</div></div>
			<div class="models-menu clearfix"><div class="wrapper clearfix"><?php echo $models_menu ?></div></div>

			<div class="content"><div class="wrapper"><?php echo $body; ?></div></div>

			<div class="footer clearfix"><div class="wrapper clearfix">
				<div class="bottom-menu">
					<?php echo $bottom_menu ?>
				</div>
			</div></div>
			<div class="copyright"><div class="wrapper">
				<p>Все права защищены www.originalspareparts.com</p>
			</div></div>
		</div>
		<!-- JavaScript at the bottom for fast page loading -->

		<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if necessary
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.js"></script>
		<script>window.jQuery || document.write('<script src="/assets/js/libs/jquery-1.6.1.min.js">\x3C/script>')</script> -->

		<!-- scripts concatenated and minified via ant build script
		<script src="js/plugins.js"></script>
		<script src="js/script.js"></script>
		end scripts-->

		<!--[if lt IE 7 ]>
		<script src="/assets/js/libs/dd_belatedpng.js"></script>
		<script>DD_belatedPNG.fix('img, .png_bg');</script>
		<![endif]-->
		<!-- mathiasbynens.be/notes/async-analytics-snippet Change UA-XXXXX-X to be your site's ID
		<script>
		var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
		g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g,s)}(document,'script'));
		</script>
		-->
		<div id="message"<?php echo !empty($flashmessage) ? ' style="display:block"' : '' ?>>
			<img title="Закрыть" class="close" src="/assets/images/icons/close.png">
			<div id="message-content"><?php // echo flash message, if any
				if (!empty($flashmessage)) {
					echo $flashmessage;
				}
			?></div>
		</div>
		<noscript>
			<div id="noscript-warning"><?php echo lang('noscript', array('vars' => array('domain' => $this->config->item('domain')))); ?></div>
		</noscript>
	</body>
</html>