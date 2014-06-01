<?php
$langSettings = pmGetCurrentLanguage();
$isLawyerActive = get_menu_id() == $langSettings['lawyer_menu']['id'];
$lawyerMenuClass = $isLawyerActive ? 'class="current"' : '';
// ���� �������� �� ����, �������� �� ����������
$accountantMenuClass = !$isLawyerActive ? 'class="current"' : '';
echo <<<MENU
<div class="header_container">
	<div class="header_container_inner">
		<div class="logo">
			�����������<br>� �������������<br><span>����������</span>
			<div class="coner_logo"></div>
		</div>
		<div class="info_conteiner">��� ��� � ������ �������</div>
		<div class="index_header_contacts">
			<p style="margin-left: 55px;">���� �������� � �������������:</p>
			<div class="phone">
				<div>
					<span>77-87-67</span>
					<p>�����</p>
				</div>
				<div style="padding-left: 25px;">
					<span>77-87-67</span>
					<p>���������</p>
				</div>
				<br>
			</div>
		</div>
		<ul class="header_container_menu">
			<li><a href="/service/1/0.html" $lawyerMenuClass>����������� ������</a></li>
			<li><a href="/service/18/0.html" $accountantMenuClass>������������� ������</a></li>
			<li><a href="#">� ���</a></li>
			<li><a href="#">��������</a></li>
		</ul>
	</div>
	<div class="index_header_container_triangle"></div>
</div>
MENU;
