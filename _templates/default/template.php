<?php
global $_o, $_languages, $language, $pagePath, $html_charset;

require_once pmIncludePath('design.php');

$language = $_languages[0];

echo <<<stop
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<meta http-equiv="Content-Type" content="text/html; charset={$html_charset}">
		<script type="text/javascript" src="{$_o['base_site_js_url']}/jquery-1.10.2.js"></script>
		<script type="text/javascript" src="{$_o['base_site_js_url']}/main.js"></script>
	</head>
	<body>
stop;

switch ($pagePath[0])
{
	case '':
		echo '<@gadget_index>';
		break;
	default:
		echo '<@gadget_top_menu><@content_stub>';
		break;
}

echo <<<stop
<div class="bottom_container">
			<div class="triangle_red_white_top"></div>
			<ul class="bottom_container_menu">
				<li><a href="#">О компании</a></li>
				<li><a href="#">Юридические услуги</a></li>
				<li><a href="#">Бухгалтерские услуги</a></li>
				<li><a href="#">Контакты</a></li>
			</ul>
			<div class="bottom_contacts">
				<div class="phone">
					<div>
						<p>Юрист</p>
						<span>77-87-67</span>
					</div>
					<div style="padding-left: 25px;">
						<p>Бухгалтер</p>
						<span>77-87-67</span>
					</div>
					<br>
				</div>
			</div>
		</div>
	</body>
</html>
stop;
//------------------------------------------------------------------------------
?>
