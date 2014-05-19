<?php
global $_o, $_languages, $language, $pagePath, $html_charset;

require_once pmIncludePath('design.php');

$language = $_languages[0];

echo <<<stop
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset={$html_charset}">
		<script type="text/javascript" src="{$_o['base_site_js_url']}/core.js"></script>
	</head>
	<body>
stop;

switch ($pagePath[0])
{
	// текстовый раздел
	default:
		echo '<@gadget_content>';
		break;
}

echo <<<stop
</body>
</html>
stop;
//------------------------------------------------------------------------------
?>
