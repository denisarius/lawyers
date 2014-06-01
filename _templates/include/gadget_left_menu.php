<?php
require_once pmIncludePath('/components/LeftMenu.php');
$menu = new LeftMenu(get_menu_id());
echo $menu->getHtml();
