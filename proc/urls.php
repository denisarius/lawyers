<?php
//------------------------------------------------------------------------------
// ������� ���������� ��� ���������������� ��������� ����������� � �������
function get_text_url($id, $title, $menu_item, $menu_url = '')
{
	global $_cms_texts_table;

//	echo "[$id][$title][$menu_item][$menu_url]";
	$menu = get_data('menu_item', $_cms_texts_table, "id='$id'");
	return get_menu_url($menu);
}

/**
 * ���������� ������������� url (���� � url) ��� ���������� ����.
 * ���������� ��� ����������� ��������� ����������� �����, ������������, ��������, � ��������� ����������� � �������.
 *
 * ������ ����:
 * 1. ��� ��������� ����������� (�� �������������, "�������" ���):  @pre{ /<menu_item_id>.html }.
 * 2. ��� �������� ����������� �����: @pre{ /<menu_item_id>/menu_item_url },
 *   ��� menu_item_url �� ������ ���� ������ � ������������� �����.
 * 3. ��� �������� ������������ �����: @pre{ /<menu_item_url>/<menu_item_id>/<�����>.html },
 *   ��� <menu_item_url> ����� ���� ������������ � ��������� ������������� ���������� ��� ������� �������,
 *   ��� ���� <�����> ����� ����� ������������ ����������� ������� (����� ��������, �� ������ � �.�.)
 *
 * ����� �������, ������� ����������, ��� �� ���������� ���� ������������� ���� � ����� "��������" ����, (<id>.html),
 * ���� �� ������������� �������� ���� �������������� ����.
 *
 * @param int $id ������������� ���������� ����
 *
 * @return string ������������� url
 */
function get_menu_url($id)
{
	global $_cms_menus_items_table;

	$menu = get_data_array('*', $_cms_menus_items_table, "id='$id'");
	if ($menu === false) return '/'; // �� ����� ����

	$menuUrl = ltrim($menu['url'], '/');
	if ($menu['url'] != '')
	{
		if ($menu['url'] === '/')
			return '/';
		if (strpos($menu['url'], 'html') === false)
			return "/$menuUrl/$id/0.html";
		else
			return "/$id/$menuUrl";
	}

	return "/$id.html";
}

/**
 * @todo ������� �� $_o
 * ���������� ���� � ���� �� ����� ���������� �� ��������� ���������� ����.
 * ���� �� ������ ���������  (�������) �������� $parent, ����� �������� �������������� ������ � ��.
 *
 * @param int $id �� ���������� ����
 * @param int $parent �� ��������
 *
 * @return array ����
 */
function get_menu_item_path($id, $parent = null)
{
	global $_cms_menus_items_table;

	// �������� �������� ����, �.�. �� �� ����������
	static $path;

	if (isset($path[$id]))
		return $path[$id];

	// ������� ������ �������� ���������� ��� ������� ����� ������ ������

	// ������ �� ��� ����������� ����������� ���������?
	if (is_null($parent))
		$menuItem = get_data_array('id, parent', $_cms_menus_items_table, "id = $id");
	else
	{
		// ����� ��� �������� �� ����������� ����������
		$menuItem = array('id' => $id, 'parent' => $parent);
	}

	// ������� �� ������ �����
	$path = array();
	while ($menuItem['parent'] != 0)
	{
		array_unshift($path, $menuItem['parent']);
		// ��������
		$menuItem = get_data_array('id, parent', $_cms_menus_items_table, "id = {$menuItem['parent']}");
	}

	return $path;
}


// ������ �������

function get_content()
{
	global $_o;
	$text_id = get_menu_item_id();
	return get_data_array('*', $_o['cms_texts_table'], "menu_item=$text_id");
}


/**
 *  ���������� �� �������� ���� (�.�. ������). ������������� ��� ������������� ��� ������ ����� � ������ ����������.
 *
 * @return int �� ���� (>0), ���� �������, ����� 0 (��������� ��������)
 */
function get_menu_id()
{
	global $_o;

	static $menuId;

	if (!isset($menuId))
	{
		$itemId = get_menu_item_id();
		$menuId = get_data('menu', $_o['cms_menus_items_table'], "id = $itemId");
	}

	return $menuId === false ? 0 : $menuId;
}

/**
 * ���������� �� ���������� ����, ������������ �� �������� ���� �� �������� �������.
 * �������� ���� - ��� ���� �� ����� �� ��������� ����������.
 *
 * @param int $level - ������� �������� ����������. ���� �� ������, �� ���� ��� ��������� ����������.
 * ������� 1 ������������ �� ����� ������ �������� ���� (��������� ����� $_cms_simple)
 *
 * @return int �� ���������� ���� (>0), ���� ������, ����� 0 (��������� ��������)
 */
function get_menu_item_id($level = 0)
{
	global $pagePath, $_o;

	// ���� ��������� ������� ���� - ����� >0, �� ��� �� ����
	$p = end($pagePath);
	if (is_numeric($p) and count($pagePath) == 1)
	{
		$menuId = (int)$p;
	}
	elseif ($pagePath[0] === '') // ������ ������ - ������ �����
	{
		$menuId = get_data('id', $_o['cms_menus_items_table'], 'menu = '.$_o['main_menu_id']." and url='/'");
		if ($menuId === false)
			return 0;
	}
	else
	{
		// ����� �� ���� ������ ���� � ������������� ��������
		$p = array_slice($pagePath, -2, 1);
		if (is_numeric($p[0]))
			$menuId = (int)$p[0];
		else
		{
			error_log('failed to parse menu id for url: '.$_SERVER['REQUEST_URI']);
			return 0;
		}
	}

	if (!$level)
		return $menuId;

	$path = get_menu_item_path($menuId);
	$path[] = $menuId;

	$maxLevel = count($path);
	if ($level <= $maxLevel)
		return (int)$path[$level - 1];
	else
		return 0;
}


/**
 * ���������, ��������� �� �� �������� ����� ���� �������� (���� 2-�� �������)
 */
function content_menu_exists()
{
	// �������� ���� �������� ����� ������ �������?
	if (get_menu_item_id(2))
		return true;

	// �� �� ������ ������, ������ �������� ���������, ���� �� � ��������� ���������� �������
	global $_cms_menus_items_table;
	return false !== get_data('id', $_cms_menus_items_table, 'parent = '.get_menu_item_id());
}


//------------------------------------------------------------------------------
function get_contents_url($section, $page)
{
	return "/contents/$section/$page.html";
}

//------------------------------------------------------------------------------
function get_info_url($id)
{
	return "/info/$id.html";
}

//------------------------------------------------------------------------------

?>