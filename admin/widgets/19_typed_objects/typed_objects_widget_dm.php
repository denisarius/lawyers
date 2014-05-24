<?php
	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<3; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
	require_once 'typed_objects_widget_proc.php';
	require_once "$path/_config.php";
	require_once "$_admin_common_proc_path/variables.php";
	require_once "$_admin_common_proc_path/cms.php";
	require_once "$_admin_common_proc_path/logs.php";
	if (file_exists("$_admin_common_proc_path/user.php")) require_once "$_admin_common_proc_path/user.php";
	require_once "$_admin_common_proc_path/main.php";
	require_once "$_admin_pmEngine_path/pmMain.php";
	require_once "$_admin_pmEngine_path/pmAPI.php";

	importVars('section', false);
	if(!isset($section) || $section=='') exit;

	header("Content-Type: text/html; charset={$html_charset}");

	require_once "$_admin_common_proc_path/db.php";
	require_once "$_admin_proc_path/main.php";
	require_once "$_admin_proc_path/common_design.php";

	if (!isset($section) || $section=='') exit;
	$link=connect_db();

//******************************************************************************
//
// Блок процедур для работы с объектами
//
//******************************************************************************
	switch ($section)
	{
		// Генерация списка объектов
		case 'typed_objectsGetObjectsList':
			$vars=pmImportVarsList('menu_item|obj_type|page', true);
			if (!isset($vars['menu_item']) || $vars['menu_item']=='') return;
			if (!isset($vars['obj_type'])) $vars['obj_type']='';
			if (!isset($vars['page']) || $vars['page']=='') $vars['page']=0;
			$list=typed_objects_get_objects_list($vars['menu_item'], $vars['obj_type'], $vars['page']);
			$list['html']=str_replace("\r\n", '',  $list['html']);
			$list['html']=str_replace("\n", '',  $list['html']);
			echo serialize_data('page|html', $list['page'], $list['html']);
			break;
		// Генерация блока HTML кода для редактирования объекта
		case 'typed_objectsGetObjectEditHtml':
			$vars=pmImportVarsList('id|type', true);
			if (!isset($vars['id']) || $vars['id']=='') $vars['id']=-1;
			if (!isset($vars['type']) || $vars['type']=='') return;
			echo typed_objects_get_edit_object_html($vars['id'], $vars['type']);
			break;
		// Проверка возможности записи данных объекта
		case 'typed_objectsEditCanBeSave':
			$vars=pmImportVarsList('id|name|menu_item|type', true);
			echo typed_objects_edit_can_be_save($vars['id'], $vars['name'], $vars['menu_item'], $vars['type']);
			break;
		// Запись данных объекта
		case 'typed_objectsSaveObjectData':
			$vars=pmImportVarsList('menu_item|id|type|name|note|img|gallery', true);
			$props=pmImportVarsList('props', false);
			$vars['name']=iconv ('utf-8', $html_charset, $vars['name']);
			$vars['note']=iconv ('utf-8', $html_charset, $vars['note']);
			$props=iconv ('utf-8', $html_charset, $props);
			if (isset($vars['id']) && $vars['id']!='' &&
				isset($vars['menu_item']) && $vars['menu_item']!='')
				typed_objects_save_object_data($vars['id'], $vars['type'], $vars['menu_item'], $vars['name'], $vars['note'], $vars['img'], $vars['gallery'], $props);
			break;
		// Удаление объекта
		case 'typed_objectsDeleteObject':
			$id=pmImportVarsList('id', true);
			if (isset($id) && $id!='') typed_objects_object_delete($id);
			break;
		// Переключение флажка отображения объекта на сайте
		case 'typed_objectSetObjectVisible':
			$vars=pmImportVarsList('id|visible', true);
			if (!isset($vars['id']) || $vars['id']=='' ||
				!isset($vars['visible']) || $vars['visible']=='') return;
			query("update $_cms_objects_table set visible='{$vars['visible']}' where id='{$vars['id']}'");
			$vis=get_data('visible', $_cms_objects_table, "id='{$vars['id']}'");
            if ($vis!==false) echo $vis;
			break;
		// Генерация HTML кода для выбора множественных вхождений из справочника
		case 'typed_objectsGetDirValuesHtml':
			$vars=pmImportVarsList('id|type|vals', true);
			if (!isset($vars['type']) || $vars['type']=='' ||
				!isset($vars['id']) || $vars['id']=='') return;
			echo typed_objects_get_dir_values_html($vars['type'], $vars['id'], $vars['vals']);
			break;
		// Сохранение данных выбранных из справочника
		case 'typed_objectsDirValuesSave':
			$vars=pmImportVarsList('id|props', true);
			if (!isset($vars['id']) || $vars['id']=='') return;
			echo typed_objects_dir_values_save($vars['id'], $vars['props']);
			break;
		// Генерация HTML кода для сортировки объектов
		case 'typed_objectsGetSortHtml':
			$menu_item=pmImportVarsList('menu_item', true);
			if (!isset($menu_item) || $menu_item=='') return;
			echo typed_objects_get_sort_html($menu_item);
			break;
		// Сохранение порядка сортировки объектов
		case 'typed_objectsSortSave':
			$vars=pmImportVarsList('menu_item|sort', true);
			if (!isset($vars['menu_item']) || $vars['menu_item']=='' ||
				!isset($vars['sort']) || $vars['sort']=='') return;
			$vars['sort']=substr($vars['sort'], 0, -1);
			$vars['sort']=explode('|', $vars['sort']);
			$s=0;
			foreach($vars['sort'] as $id)
			{
				query("update $_cms_objects_table set sort='$s' where id='$id'");
				$s++;
			}
			$list=typed_objects_get_objects_list($vars['menu_item'], 0, 0);
			echo serialize_data('page|html', $list['page'], $list['html']);
			break;
		// перемещение/копирование объекта в другой раздел
  		case 'typed_objectsMoveObject':
			$vars=pmImportVarsList('id|menu|copy', true);
			if (!isset($vars['menu']) || $vars['menu']=='' ||
				!isset($vars['id']) || $vars['id']=='') return;
			typed_objects_object_move($vars['id'], $vars['menu'], $vars['copy']);
			break;
		case 'typed_objectsStructuredTextEditGetHTML':
			$vars=pmImportVarsList('id|prop_type|obj_type', true);
			$prop_value=pmImportVarsList('prop_value', false);
			$prop_value=iconv ('utf-8', $html_charset, $prop_value);
			echo typed_objects_structured_text_edit_get_html($vars['id'], $vars['prop_type'], $prop_value, $vars['obj_type']);
			break;
		case 'typed_objectsStructuredTextEditFragmentGetHTML':
			importVars('id', true);
			echo typed_objects_structured_text_edit_get_fragment_html($id, '', '', '', '');
			break;
		case 'typed_objectsTableEditGetHTML':
			$vars=pmImportVarsList('id|prop_type|obj_type', true);
			$prop_value=pmImportVarsList('prop_value', false);
			$prop_value=iconv ('utf-8', "$html_charset//IGNORE//TRANSLIT", $prop_value);
			echo typed_objects_table_edit_get_html($vars['id'], $vars['prop_type'], $prop_value, $vars['obj_type']);
			break;
		case 'typed_objectsTableEditGetEmptyRowHTML':
			$vars=pmImportVarsList('row_id|prop_type|obj_type', true);
			$desc=typed_objects_get_table_description($vars['obj_type'], $vars['prop_type']);
			$prop=typed_objects_get_object_detail($vars['obj_type'], $vars['prop_type']);
			if (isset($prop['col_width'])) $col_width=explode('|', $prop['col_width']);
			else $col_width=array();
			echo typed_objects_table_edit_get_row_html($vars['row_id'], array_fill(0, $desc['columns_count'], ''), 0, $desc, $col_width, false);
			break;
		// Генерация HTML кода для добавления значения в справочник
//		case 'typed_objectsDirAddGetHTML':
//			$vars=pmImportVarsList('dir_id|prop_id');
//			if (!isset($vars['dir_id']) || $vars['dir_id']=='' || !isset($vars['prop_id']) || $vars['prop_id']=='') return;
//			echo typed_objects_dir_add_get_html($vars['dir_id'], $vars['prop_id']);
//			break;
		// Преобразхование HTML кода для сохранения в инпут
		case 'typed_objectsHtmlPropEncode':
			$html=pmImportVarsList('html');
			$html=iconv ('utf-8', "$html_charset", $html);
			echo htmlspecialchars($html, ENT_QUOTES, $html_charset);
			break;
		// Преобразование HTML перед редактированием
		case 'typed_objectsHtmlPropGetHTML':
			$html=pmImportVarsList('html');
			$html=iconv ('utf-8', "$html_charset", $html);
			echo htmlspecialchars_decode($html, ENT_QUOTES);
			break;
		// Генерайия HTML кода для select-а справочника
		case 'typed_objectsGetDirSelectItemsHTML':
			$vars=pmImportVarsList('dir_id|value_id');
			if (!isset($vars['dir_id']) || $vars['dir_id']=='') return;
			if (!isset($vars['value_id']) || $vars['value_id']=='') $vars['value_id']=-1;
			echo typed_objects_get_dir_select_items($vars['dir_id'], $vars['value_id']);
			break;
		// Генерация текста для свойства типа 'menu'
		case 'typed_objectsPropEditGetMenuPropText':
			$menu_id=pmImportVarsList('menu_id', true);
			echo typed_objects_get_menu_item_value_text($menu_id);
			break;
	}
?>