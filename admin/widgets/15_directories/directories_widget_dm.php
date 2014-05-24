<?php
	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<3; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
	require_once 'directories_widget_proc.php';
	require_once "$path/_config.php";
	require_once "{$_o['admin_common_proc_path']}/variables.php";
	require_once "{$_o['admin_common_proc_path']}/cms.php";
	require_once "{$_o['admin_common_proc_path']}/logs.php";
	if (file_exists("{$_o['admin_common_proc_path']}/user.php")) require_once "{$_o['admin_common_proc_path']}/user.php";
	require_once "{$_o['admin_common_proc_path']}/main.php";
	require_once "{$_o['admin_pmEngine_path']}/pmMain.php";
	require_once "{$_o['admin_pmEngine_path']}/pmAPI.php";

	importVars('section', false);
	if(!isset($section) || $section=='') exit;

	header("Content-Type: text/html; charset={$_o['html_charset']}");

	require_once "{$_o['admin_common_proc_path']}/db.php";
	require_once "{$_o['admin_proc_path']}/main.php";
	require_once "{$_o['admin_proc_path']}/common_design.php";

	if (!isset($section) || $section=='') exit;
	$link=connect_db();

//******************************************************************************
//
// Блок процедур для работы с новостями
//
//******************************************************************************
	switch ($section)
	{
		case 'dirsGetEditDirHtml':
			$id=pmImportVarsList('id', true);
			if (isset($id) && $id!='') echo dirs_get_edit_dir_html($id);
			break;
        case 'dirsDirDataSave':
			$vars=pmImportVarsList('id|name', true);
			if (isset($vars['id']) && $vars['id']!='' && isset($vars['name']) && $vars['name']!='')
			{
				$vars['name']=iconv ('utf-8', $_o['html_charset'], $vars['name']);
				echo dirs_save_dir_data($vars['id'], $vars['name']);
			}
			else
				echo serialize_data('error|dirs|dir_content', 'Ошибка добавления справочника', '', '');
			break;
		case 'dirsDirDelete':
			$dir_id=pmImportVarsList('dir_id', true);
			if (isset($dir_id) && $dir_id!='')
				echo dirs_dir_delete($dir_id);
			else
				echo serialize_data('error|dirs|dir_content', 'Ошибка добавления справочника', '', '');
			break;
		case 'dirsGetDirContent':
			$id=pmImportVarsList('id', true);
			if (isset($id)) echo dirs_get_dir_list_html($id);
			break;
		case 'dirsGetEditValueHtml':
			$vars=pmImportVarsList('dir_id|val_id', true);
			if (isset($vars['dir_id']) && $vars['dir_id']!='') echo dirs_get_edit_value_html($vars['dir_id'], $vars['val_id']);
			break;
		case 'dirsDirValueSave':
			$vars=pmImportVarsList('dir_id|val_id|val|menu_id', true);
			if (isset($vars['dir_id']) && $vars['dir_id']!=''
				&& isset($vars['val_id']) && $vars['val_id']!=''
				&& isset($vars['val']) && $vars['val']!='')
			{
				$vars['val']=iconv ('utf-8', $_o['html_charset'], $vars['val']);
				echo dirs_save_dir_value($vars['dir_id'], $vars['val_id'], $vars['val'], $vars['menu_id']);
			}
			else
				echo serialize_data('error|node', 'Ошибка записи значения', '');
			break;
		case 'dirsDirValueAddList':
			$vars=pmImportVarsList('dir_id|vals', true);
			if (isset($vars['dir_id']) && $vars['dir_id']!=''
				&& isset($vars['vals']) && $vars['vals']!='')
				dirs_add_items_list($vars['dir_id'], $vars['vals']);
			echo dirs_get_dir_list_html($vars['dir_id']);
			break;
		case 'dirsDirValueDelete':
			$val_id=pmImportVarsList('val_id', true);
			if (isset($val_id) && $val_id!='')
				query("delete from {$_o['cms_directories_data']} where id='$val_id'");
			break;
		// Generate the HTML code for adding new directory item for other modules
		case 'dirDirAddGetHTML':
			$vars=pmImportVarsList('dir_id|func', true);
			if (!isset($vars['dir_id']) || $vars['dir_id']=='') {
				echo 'Не указан ID справочника';
				return;
			}
			echo dirs_get_online_dir_value_add_html($vars['dir_id'], $vars['func']);
			break;
	}
?>
