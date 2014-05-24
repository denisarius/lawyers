<?php
	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<3; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
	require_once 'banners_widget_proc.php';
	require_once "$path/_config.php";
	require_once "$_admin_common_proc_path/variables.php";
	require_once "$_admin_common_proc_path/cms.php";
	require_once "$_admin_common_proc_path/logs.php";
	if (file_exists("$_admin_common_proc_path/user.php")) require_once "$_admin_common_proc_path/user.php";
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
// Блок процедур для работы с баннерами
//
//******************************************************************************
	switch ($section)
	{
		// Генерация HTML кода списка изображений
		case 'bannersGetBannersListHtml':
			$vars=pmImportVarsList('language|type', true);
			if (!isset($vars['language']) || $vars['language']=='' || !isset($vars['type']) || $vars['type']=='') return;
			$html=banners_get_banners_list_html($vars['language'], $vars['type']);
			echo serialize_data('html|sx|sy|quality', $html, $_cms_banners_description[$vars['type']]['sx'], $_cms_banners_description[$vars['type']]['sy'], $_cms_banners_description[$vars['type']]['quality']);
			break;
		// Генерация HTML кода Для добавления изображения
		case 'bannersGetBannerAddHtml':
			$type=pmImportVarsList('type', false);
			echo banners_get_banner_add_html($type);
			break;
		// Обрезание изображения для баннера
		case 'bannersCropImage':
			$vars=pmImportVarsList('file|x|y|w|h|quality', false);
			if (!isset($vars['file']) || $vars['file']=='' || !isset($vars['x']) || $vars['x']=='' || !isset($vars['y']) || $vars['y']=='' || !isset($vars['w']) || $vars['w']=='' || !isset($vars['h']) || $vars['h']=='') return;
			echo banners_crop_banner_image("{$_SERVER['DOCUMENT_ROOT']}{$vars['file']}", $vars['x'], $vars['y'], $vars['w'], $vars['h'], $vars['quality']);
			break;
		// Сохранение нового баннера
		case 'bannersAddBannerSave':
			$vars=pmImportVarsList('file|language|type|menu_item|menu_link|text|url', false);
			if (isset($vars['file']) && $vars['file']!='' && isset($vars['language']) && $vars['language']!='')
			{
				$vars['text']=iconv ('utf-8', $html_charset, $vars['text']);
				banners_banner_add_save($vars['language'], $vars['file'], $vars['type'], $vars['menu_item'], $vars['text'], $vars['url'], $vars['menu_link']);
				echo banners_get_banners_list_html($vars['language'], $vars['type']);
			}
			else
				echo 'error';
			break;
		// Сохранение данных существующего баннера
		case 'bannersEditBannerSave':
			$vars=pmImportVarsList('id|file|language|type|menu_item|menu_link|text|url', false);
			if (isset($vars['id']) && $vars['id']!='' && isset($vars['file']) && $vars['file']!='' && isset($vars['language']) && $vars['language']!='')
			{
				$vars['text']=iconv ('utf-8', $html_charset, $vars['text']);
				banners_banner_edit_save($vars['id'], $vars['language'], $vars['file'], $vars['type'], $vars['menu_item'], $vars['text'], $vars['url'], $vars['menu_link']);
				echo banners_get_banners_list_html($vars['language'], $vars['type']);
			}
			else
				echo 'error';
			break;
		// Удаление баннера
		case 'bannersDeleteBanner':
			$vars=pmImportVarsList('id|type|language', true);
			if (isset($vars['id']) && $vars['id']!='')
			{
                $banner=get_data_array('*', $_cms_banners_table, "id='{$vars['id']}'");
				query("delete from $_cms_banners_table where id='{$vars['id']}'");
				@unlink("$_base_site_banners_images_path/{$banner['file']}");
			}
			echo banners_get_banners_list_html($vars['language'], $vars['type']);
			break;
		// Переключение флажка отображения новости на сайте
		case 'bannersSetNewsVisible':
			$vars=pmImportVarsList('id|visible', true);
			if (!isset($vars['id']) || $vars['id']=='' || !isset($vars['visible']) || $vars['visible']=='') return;
			query("update $_cms_banners_table set visible='{$vars['visible']}' where id='{$vars['id']}'");
			$vis=get_data('visible', $_cms_banners_table, "id='{$vars['id']}'");
			if ($vis!==false) echo $vis;
			break;
		// Генерация HTML кода для редактирования баннера
		case 'bannersGetEditHTML':
			$vars=pmImportVarsList('type|id', false);
			if (!isset($vars['id']) || $vars['id']=='' || !isset($vars['type']) || $vars['type']=='') return;
			echo banners_get_banner_edit_html($vars['type'], $vars['id']);
			break;
		case 'bannersEditCancel':
			$vars=pmImportVarsList('id|file', false);
			banner_edit_cancel($vars['id'], $vars['file']);
			break;
	}
	mysql_close($link);
?>