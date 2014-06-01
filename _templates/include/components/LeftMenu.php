<?php

require __DIR__.'/View.php';

class LeftMenu extends View
{

	public function __construct($menuId)
	{
		$this->_menuId = $menuId;
	}

	public function getHtml()
	{
		return $this->_renderTemplate(
			array('items' => $this->_getMenuHtml(1, 0)),
			$this->_template[0]['menu']
		);
	}

	private function _getMenuHtml($level, $parentId)
	{
		global $_o;
		$items = get_data_array_rs(
			'id, name',
			$_o['cms_menus_items_table'],
			'parent = '.$parentId.' and menu = '.$this->_menuId.' and visible = 1'
		);
		$itemsHtml = '';

		while ($item = $items->next())
		{
			$itemClasses = array();
			$itemActive = $item['id'] == get_menu_item_id($level);

			$submenu = $this->_getMenuHtml($level + 1, $item['id']);
			if ($submenu)
			{
				$itemClasses[] = 'drop';
				$submenuOpenedClass = $itemActive ? $this->_template[$level]['submenu_class_opened'] : '';
				$submenu = $this->_renderTemplate(
					array('items' => $submenu, 'class_opened' => $submenuOpenedClass),
					$this->_template[$level]['submenu']
				);
				$link = '#';
			}
			else
			{
				$link = self::getMenuItemUrl($item['id']);
			}

			// раскрываем/подсвечиваем активный пункт меню
			if ($itemActive)
				$itemClasses[] = $this->_template[$level]['active_class'];

			$class = implode(' ', $itemClasses);
			$class = $class ? 'class="'.$class.'"' : '';
			$itemsHtml .= $this->_renderTemplate(
				array('name' => $item['name'], 'class' => $class, 'link' => $link, 'submenu' => $submenu),
				$this->_template[$level]['item']
			);
		}
		return $itemsHtml;
	}

	private $_template = array(
		0 => array(
			'menu' => '<ul class="left_menu_accordion">@items@</ul>'
		),
		1 => array(
			'item' => '<li @class@>
				<a href="@link@">@name@</a>
				@submenu@
			</li>',
			'active_class' => 'opened',
			'submenu' => '<ul class="submenu_accordion @class_opened@">@items@</ul><div></div>',
			'submenu_class_opened' => 'init_opened'
		),
		2 => array(
			'item' => '<li @class@><a href="@link@">@name@</a></li>',
			'active_class' => 'active'
		)
	);

	public static function getMenuItemUrl($itemId)
	{
		return "/service/$itemId/0.html";
	}

	private $_menuId;
}