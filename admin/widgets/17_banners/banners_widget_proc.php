<?php
// -----------------------------------------------------------------------------
// Creating the HTML code for banner type selector
// If banners description array contains less that 2 elements the procedure will generate hidden input control.
// Else will generate the select control.
function banners_get_banner_type_selector_html()
{
	global $_cms_banners_description;

	$html='<div class="banners_type_selector_container">';
	$keys=array_keys($_cms_banners_description);
	if (count($_cms_banners_description)<2)
	{
		$html.=<<<stop
<input type="text" id="banners_banner_type_id" value="{$keys[0]}" />
stop;
	}
	else
    {
    	$html.='Тип баннера: <select id="banners_banner_type_id" onChange="banners_banner_type_changed()">';
		foreach($_cms_banners_description as $key => $desc)
			$html.=<<<stop
<option value="{$key}">{$desc['name']}</option>
stop;
        $html.='</select>';
    }
	$html.=<<<stop
<input type="hidden" id="banners_banner_sx" value="{$_cms_banners_description[$keys[0]]['sx']}" />
<input type="hidden" id="banners_banner_sy" value="{$_cms_banners_description[$keys[0]]['sy']}" />
<input type="hidden" id="banners_banner_quality" value="{$_cms_banners_description[$keys[0]]['quality']}" />
</div>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
function banners_get_single_banner_html($r)
{
	global $_cms_banners_description, $_base_site_banners_images_path, $_base_site_banners_images_url, $_cms_menus_items_table;

	$desc=$_cms_banners_description[$r['type']];
	$sz=pmGetBoundedImageSize("$_base_site_banners_images_path/{$r['file']}", 700, 200);
	if ($desc['menu_item'])
	{
		$menu_item_name=get_data('name', $_cms_menus_items_table, "id='{$r['menu_item']}'");
		$menu_item="<p><b>Раздел:</b> $menu_item_name</p>";
	}
	else $menu_item='';

	if ($desc['url']) $url="<p><b>URL:</b> {$r['url']}</p>";
	else $url='';

	if ($desc['link'])
	{
		$menu_item_name=get_data('name', $_cms_menus_items_table, "id='{$r['link']}'");
		$link="<p><b>Ссылка на раздел:</b> $menu_item_name</p>";
	}
	else $link='';

	if ($r['visible']==1) $ch=' checked="checked"';
	else $ch='';

	$rnd=mt_rand();
	$html= <<<stop
<div class="banners_banner_list_node">
	<div class="banners_banner_list_node_description">
		<div>$menu_item</div>
		<div>$url</div>
		<div>$link</div>
	</div>
	<img src="$_base_site_banners_images_url/{$r['file']}?$rnd" style="width: {$sz['x']}px; height: {$sz['y']}px;" />
	<div style="text-align: left;">
		<input type="checkbox" id="banners_visible_{$r['id']}" onClick="banners_toggle_visible({$r['id']})" $ch> Отображать на сайте
		<hr>
		<input type="button" class="admin_tool_button" value="Изменить" onClick="banners_banner_edit({$r['id']})"/>
		<input type="button" class="_right admin_tool_button" value="Удалить" onClick="banners_banner_delete({$r['id']})"/>
		<br>
	</div>
</div>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
// Creating of list of banners by language ID and banner type ID
// language	- ID of language ('en', 'ru', etc.)
// type		- ID of banner type from array $_cms_banners_description (from configs/config_banners.php)
function banners_get_banners_list_html($language, $type)
{
	global $_cms_banners_table;

	$res=query("select * from $_cms_banners_table where language='$language' and type='$type' order by menu_item, sort desc, id desc");
	$html='';
	while($r=mysql_fetch_assoc($res))
		$html.=banners_get_single_banner_html($r);
	mysql_free_result($res);
	return $html;
}
// -----------------------------------------------------------------------------
function banners_get_banner_add_html($type)
{
	return banners_get_banner_edit_html($type, -1);
}
// -----------------------------------------------------------------------------
function banners_get_banner_edit_html($type, $id)
{
	global $_cms_banners_table, $_cms_banners_description, $_cms_menus_items_table, $_base_site_banners_images_path;
	global $_admin_uploader_path, $_admin_uploader_url;

	$desc=$_cms_banners_description[$type];

	// Add banner variable init
	$menu_item_init=$menu_item_name_init=$text_init=$url_init=$link_init=$link_text_init=$file_init='';
	$img_init='images/space.gif';

	if ($id!=-1) {
		// Edit banner variable init
		$banner=get_data_array('*', $_cms_banners_table, "id='$id'");
		if ($banner!==false) {
			$menu_item_init=$banner['menu_item'];
			if ($menu_item_init!=0 && $menu_item_init!='') {
				$menu_item_name_init=get_data('name', $_cms_menus_items_table, "id='$menu_item_init'");
			}
			$text_init=$banner['text'];
			$url_init=$banner['url'];
			$link_init=$banner['link'];
			if ($link_init!=0 && $link_init!='') {
				$link_text_init=get_data('name', $_cms_menus_items_table, "id='$link_init'");
			}
			$file_init=$banner['file'];
			if ($file_init!='')
			{
				$rnd=mt_rand();
				@copy("$_base_site_banners_images_path/$file_init", "$_admin_uploader_path/temp/$file_init");
				$img_init="$_admin_uploader_url/temp/$file_init?$rnd";
			}
			else $img_init='images/space.gif';
		}
	}

// parent menu_item selector
	if (isset($desc['menu_item']) && $desc['menu_item']==true)
		$menu_item_html=<<<stop
<div class="banner_edit_data_row">
	<div class="hdr">Раздел</div>
	<div class="field">
		<input type="button" class="admin_tool_button" id="banner_edit_menu_item_btn" value="Выбрать раздел" onClick="banners_edit_menu_item_select()"/>
		<input type="hidden" id="banner_edit_menu_item_id" value="$menu_item_init"/>
		<div class="link_block" id="banner_edit_menu_item_block">
			<span id="banner_edit_menu_item_name">$menu_item_name_init</span>
			<span class="delete" onClick="banners_edit_delete_link('banner_edit_menu_item_')"></span>
		</div>
	</div>
</div>
stop;
	else
		$menu_item_html='<input type="hidden" id="banner_edit_menu_item_id" value="0"/>';

// banner text controls
	if (isset($desc['text']) && $desc['text']==true)
		$text_html=<<<stop
<div class="banner_edit_data_row">
	<div class="hdr">Текст баннера</div>
	<div class="field"><input type="text" id="banner_edit_text" value="$text_init"/></div>
</div>
stop;
	else
		$text_html='<input type="hidden" id="banner_edit_text" value="$text_init"/>';

// banner url controls
	if (isset($desc['url']) && $desc['url']==true)
		$url_html=<<<stop
<div class="banner_edit_data_row">
	<div class="hdr">URL</div>
	<div class="field"><input type="text" id="banner_edit_url" value="$url_init" /></div>
</div>
stop;
	else
		$url_html='<input type="hidden" id="banner_edit_url" value="$url_init"/>';

// menu_item link controls
	if (isset($desc['link']) && $desc['link']==true)
		$link_html=<<<stop
<div class="banner_edit_data_row">
	<div class="hdr">Ссылка на раздел</div>
	<div class="field">
		<input type="button" class="admin_tool_button" id="banner_edit_link_btn" value="Выбрать раздел для ссылки" onClick="banners_edit_menu_link_select()"/>
		<input type="hidden" id="banner_edit_link_id" value="$link_init"/>
		<div class="link_block" id="banner_edit_link_block">
			<span id="banner_edit_link_name">$link_text_init</span>
			<span class="delete" onClick="banners_edit_delete_link('banner_edit_link_')"></span>
		</div>
	</div>
</div>
stop;
	else
		$link_html='<input type="hidden" id="banner_edit_menu_item_id" value="0"/>';

	$html= <<<stop
<input type="hidden" id="banner_edit_banner_id" value="$id">
<input type="hidden" id="banners_add_banner_image_file" value="$file_init"/>
$menu_item_html
$text_html
$url_html
$link_html
<div class="banners_add_banner_image_container">
<img src="$img_init" id="banners_add_banner_image" /><br>
<input type="button" value="Загрузить изображение" onClick="banners_banner_add_image_load()"/>
</div>
<input type="button" value="Сохранить баннер" onClick="banners_banner_edit_save()" style="margin-right: 20px;"/>
<input type="button" value="Отмена" onClick="banners_banner_edit_cancel()"/>
<script type="text/javascript">
	$("#banners_add_banner_image").load(function(){
		admin_info_center();
	});
</script>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
// Edit procedure cancel
// Move banner file to banners directory if id!=-1
function banner_edit_cancel($id, $new_file)
{
	global $_cms_banners_table, $_base_site_banners_images_path, $_admin_uploader_path;

	if ($id==-1) return;
	$banner=get_data_array('*', $_cms_banners_table, "id='$id'");
	if ($banner===false) return;
	@rename("$_admin_uploader_path/temp/{$banner['file']}", "$_base_site_banners_images_path/{$banner['file']}");
	if ($new_file!='') @unlink("$_admin_uploader_path/temp/$new_file");
}
// -----------------------------------------------------------------------------
function banners_crop_banner_image($file, $x, $y, $w, $h, $quality)
{
	global $_admin_uploader_path, $_admin_uploader_url;

	$pp=pathinfo($file);
	switch (strtolower($pp['extension']))
	{
		case 'jpg':
		case 'jpeg':
			$im=imagecreatefromjpeg($file);
			break;
		case 'png':
			$im=imagecreatefrompng($file);
			break;
	}
	$dest=imagecreatetruecolor($w, $h);
	imagecopy($dest, $im, 0, 0, $x, $y, $w, $h);
	imagedestroy($im);
	$dfile=create_unique_file_name("$_admin_uploader_path/temp", $file);
	switch (strtolower($pp['extension']))
	{
		case 'jpg':
		case 'jpeg':
			imagejpeg($dest, $dfile, $quality);
			break;
		case 'png':
			imagepng($dest, $dfile);
			break;
	}
	imagedestroy($dest);
	$pp=pathinfo($dfile);
	$dfile=$pp['basename'];
	query("insert into _temp_files (file, created) values ('$dfile', CURDATE())");
	@unlink($file);
	return serialize_data('img|file', "$_admin_uploader_url/temp/$dfile", $dfile);
}
// -----------------------------------------------------------------------------
// language		- ID of language of banner ('ru', 'en' etc.)
// file			- path to uploaded and cropped image of banner
// type			- ID of banner type from array $_cms_banners_description (from configs/config_banners.php)
// menu_item	- ID of menu item wich contains banner
// text			- text of banner
// url			- url assigned to banner
function banners_banner_add_save($language, $file, $type, $menu_item, $text, $url, $menu_link)
{
	global $_admin_uploader_path, $_base_site_banners_images_path, $_cms_banners_table;

	if ($menu_link=='') $menu_link=0;
	if ($menu_item=='') $menu_item=0;
	@rename("$_admin_uploader_path/temp/$file", "$_base_site_banners_images_path/$file");
	$cnt=get_data('max(id)', $_cms_banners_table)+1;
    query("insert into $_cms_banners_table (language, file, sort, visible, type, menu_item, text, url, link) values ('$language', '$file', '$cnt', 1, '$type', '$menu_item', '$text', '$url', '$menu_link')");
}
// -----------------------------------------------------------------------------
// id           - ID of banner which save changes
// language		- ID of language of banner ('ru', 'en' etc.)
// file			- path to uploaded and cropped image of banner
// type			- ID of banner type from array $_cms_banners_description (from configs/config_banners.php)
// menu_item	- ID of menu item wich contains banner
// text			- text of banner
// url			- url assigned to banner
	function banners_banner_edit_save($id, $language, $file, $type, $menu_item, $text, $url, $menu_link)
	{
		global $_admin_uploader_path, $_base_site_banners_images_path, $_cms_banners_table;

		if ($menu_link=='') $menu_link=0;
		if ($menu_item=='') $menu_item=0;
		$old_file=get_data('file', $_cms_banners_table, "id='$id'");
		@rename("$_admin_uploader_path/temp/$file", "$_base_site_banners_images_path/$old_file");
		query("update $_cms_banners_table set language='$language', menu_item='$menu_item', text='$text', url='$url', link='$menu_link'");
		@unlink("$_admin_uploader_path/temp/$old_file");
	}
// -----------------------------------------------------------------------------
?>