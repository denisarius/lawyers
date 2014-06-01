<?php
require_once pmTemplatePath().'/../include/components/LeftMenu.php';
$menu = new LeftMenu(get_menu_id());
echo $menu->getHtml();
