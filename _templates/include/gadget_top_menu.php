<?php
$langSettings = pmGetCurrentLanguage();
$isLawyerActive = get_menu_id() == $langSettings['lawyer_menu']['id'];
$lawyerMenuClass = $isLawyerActive ? 'class="current"' : '';
// пока третьего не дано, работаем от противного
$accountantMenuClass = !$isLawyerActive ? 'class="current"' : '';
echo <<<MENU
<div class="header_container">
	<div class="header_container_inner">
		<div class="logo">
			Юридический<br>и бухгалтерский<br><span>аутсорсинг</span>
			<div class="coner_logo"></div>
		</div>
		<div class="info_conteiner">для Вас и Вашего бизнеса</div>
		<div class="index_header_contacts">
			<p style="margin-left: 55px;">Наши телефоны в Петрозаводске:</p>
			<div class="phone">
				<div>
					<span>77-87-67</span>
					<p>Юрист</p>
				</div>
				<div style="padding-left: 25px;">
					<span>77-87-67</span>
					<p>Бухгалтер</p>
				</div>
				<br>
			</div>
		</div>
		<ul class="header_container_menu">
			<li><a href="/service/1/0.html" $lawyerMenuClass>Юридические услуги</a></li>
			<li><a href="/service/18/0.html" $accountantMenuClass>Бухгалтерские услуги</a></li>
			<li><a href="#">О нас</a></li>
			<li><a href="#">Контакты</a></li>
		</ul>
	</div>
	<div class="index_header_container_triangle"></div>
</div>
MENU;
