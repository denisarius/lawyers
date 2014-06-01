<?php
global $_o, $_languages, $language, $pagePath, $html_charset;

require_once pmIncludePath('design.php');

$language = $_languages[0];

echo <<<stop
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset={$html_charset}">
		<script type="text/javascript" src="{$_o['base_site_js_url']}/jquery-1.10.2.js"></script>
		<script type="text/javascript" src="{$_o['base_site_js_url']}/main.js"></script>
	</head>
	<body>
stop;

switch ($pagePath[0])
{
	case 'service':
		echo '<@gadget_top_menu>
			<div class="content_container">
				<div class="left_column"><@gadget_left_menu><@gadget_feedback></div>
				<div class="right_column"><@gadget_appointment_form><@gadget_text_object></div>
				<br>
			</div>';
		break;
	// ��������� �������
	case 'about':
	case 'contacts':
		echo '<@gadget_top_menu>
		<div class="content_container">
			<div class="left_column"><!-- ��� ���� ����� --></div>
			<div class="right_column"><@gadget_appointment_form><@gadget_content></div>
			<br>
		</div>';
		break;
	// ��������� ��������
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
				<li><a href="#">� ��������</a></li>
				<li><a href="#">����������� ������</a></li>
				<li><a href="#">������������� ������</a></li>
				<li><a href="#">��������</a></li>
			</ul>
			<div class="bottom_contacts">
				<div class="phone">
					<div>
						<p>�����</p>
						<span>77-87-67</span>
					</div>
					<div style="padding-left: 25px;">
						<p>���������</p>
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
