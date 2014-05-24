<?php
//------------------------------------------------------------------------------
function dirs_get_edit_dir_html($id)
{
	global $_o;

	$name='';
	if($id!=-1) $name=htmlentities(get_data('name', $_o['cms_directories'], "id='$id'"), ENT_QUOTES, $_o['html_charset']);
	$html=<<<stop
<b>Название справочника</b><br>
<input type="text" id="dirs_dir_name" value="$name" style="width: 98%;"/>
<br><br>
<input type="button" value="Сохранить" onClick="dirs_dir_data_save($id)" />
stop;
	return $html;
}
//------------------------------------------------------------------------------
function dirs_get_dirs_html($id)
{
	global $_o;

	$html='';
	$res=query("select * from {$_o['cms_directories']} order by name");
	while($r=mysql_fetch_assoc($res)) {
		if ($r['id']==$id) $sl=' selected="selected"';
		else $sl='';
		$html.=<<<stop
<option value="{$r['id']}" $sl>{$r['name']} [ID={$r['id']}]</option>
stop;
	}
	mysql_free_result($res);
	return $html;
}
//------------------------------------------------------------------------------
function dirs_get_dir_list_node_html($r)
{
	global $_o;

	if (!$r['linked']) $link='';
	else {
		$menu=get_data('name', $_o['cms_menus_items_table'], "id='{$r['linked']}'");
		$link="<span>==> '$menu'</span>";
	}
	$html=<<<stop
<div class="dirs_value_node" id="dirs_value_node_{$r['id']}">
	<div class="data">
		<div class="name" id="dirs_dir_value_{$r['id']}">{$r['content']}$link</div>
		<div class="id">[ID={$r['id']}]</div>
	</div>
	<div class="buttons">
		<img src="images/options_24.png" style="margin-right: 10px;" onClick="dirs_edit_value({$r['id']})" />
		<img src="images/delete_24.png"  onClick="dirs_delete_value({$r['id']})" />
	</div>
	<br>
</div>
stop;
	return $html;
}
//------------------------------------------------------------------------------
function dirs_get_dir_list_html($id)
{
	global $_o;

	$html=<<<stop
<hr style="margin-bottom: 10px;">
<input type="button" value="Добавить значение" onClick="dirs_edit_value(-1)" style="margin: 10px 20px 10px 0;"/>
<input type="button" value="Добавить значения списком" onClick="dirs_add_list_values()" style="margin: 10px 20px 10px 0;"/>
stop;
	$res=query("select * from {$_o['cms_directories_data']} where dir='$id' order by content");
	while($r=mysql_fetch_assoc($res))
		$html.=dirs_get_dir_list_node_html($r);
	mysql_free_result($res);
	return $html;
}
//------------------------------------------------------------------------------
function dirs_save_dir_data($id, $name)
{
	global $_o;

	if ($id==-1) {
		$i=get_data('id', $_o['cms_directories'], "name='$name'");
		if ($i===false) {
			query("insert into {$_o['cms_directories']} (name) values ('$name')");
			$id=mysql_insert_id();
			return serialize_data('error|dirs|dir_content', '', dirs_get_dirs_html($id), dirs_get_dir_list_html($id));
		}
		else
			return serialize_data('error|dirs|dir_content', 'Справочник с таким названием уже существует', '', '');
	}
	else {
		$i=get_data('id', $_o['cms_directories'], "name='$name' and id!='$id'");
		if ($i===false) {
			query("update {$_o['cms_directories']} set name='$name' where id='$id'");
			return serialize_data('error|dirs|dir_content', '', dirs_get_dirs_html($id), dirs_get_dir_list_html($id));
		}
		else
			return serialize_data('error|dirs|dir_content', 'Существует другой справочник с таким названием', '', '');
	}
}
//------------------------------------------------------------------------------
// Directory delete
// $dir_id  - ID of directory which need deleted
function dirs_dir_delete($dir_id)
{
	global $_o;

	query("delete from {$_o['cms_directories_data']} where dir='$dir_id'");
	query("delete from {$_o['cms_directories']} where id='$dir_id'");
}
//------------------------------------------------------------------------------
function dirs_get_edit_value_html($dir_id, $val_id)
{
	global $_o;

	$dir=get_data('name', $_o['cms_directories'], "id='$dir_id'");
	if ($val_id==-1) $val=array('content'=>'', 'linked'=>0);
	else $val=get_data_array('content, linked', $_o['cms_directories_data'], "dir='$dir_id' and id='$val_id'");
	if ($val['linked']==0) $link=array('name'=>'', 'id'=>0);
	else $link=get_data_array('id, name', $_o['cms_menus_items_table'], "id='{$val['linked']}'");
	$val['content']=pmAntiXSSVar($val['content'], $_o['html_charset']);
	$html=<<<stop
<b>Cправочник: $dir</b><br><br>
<b>Значение</b><br>
<input type="text" id="dirs_value_content" value="{$val['content']}" style="width: 98%;"/>
<div class="dirs_link_menu_block">
	<input type="hidden" id="dirs_link_to_menu_id" value="{$link['id']}" />
	<input type="button" class="admin_tool_button" value="Связать с разделом" onClick="dirs_link_to_menu()" />
	<div id="dirs_link_menu_name">{$link['name']}</div>
	<br>
</div>
<input type="button" value="Сохранить" onClick="dirs_dir_value_save($val_id)" />
stop;
	return $html;
}
//------------------------------------------------------------------------------
function dirs_save_dir_value($dir_id, $val_id, $val, $menu_id)
{
	global $_o;

	if ($val_id==-1) {
		$i=get_data('id', $_o['cms_directories_data'], "content='$val' and dir='$dir_id'");
		if ($i===false) {
			$note="[$val][$dir_id]";
			query("insert into {$_o['cms_directories_data']} (dir, content, linked) values ('$dir_id', '$val', '$menu_id')");
			$id=mysql_insert_id();
			$r=get_data_array('*', $_o['cms_directories_data'], "id='$id'");
			$node=dirs_get_dir_list_node_html($r);
			return serialize_data('error|node|note|val_id', '', $node, $note, $id);
		}
		else
			return serialize_data('error|node|note', 'В справочнике уже есть такое значение', '', '');
	}
	else {
		$i=get_data('id', $_o['cms_directories_data'], "content='$val' and dir='$dir_id' and id!='$val_id'");
		if ($i===false) {
			query("update {$_o['cms_directories_data']} set content='$val', linked='$menu_id' where id='$val_id'");
			$r=get_data_array('*', $_o['cms_directories_data'], "id='$val_id'");
			return serialize_data('error|node|note|val_id', '', dirs_get_dir_list_node_html($r), '', $val_id);
		}
		else
			return serialize_data('error|node|note', 'В справочнике уже есть такое значение', '', '');
	}
}
//------------------------------------------------------------------------------
function dirs_add_items_list($dir_id, $vals)
{
	global $_o;

	$vals=explode('\n', $vals);
	foreach($vals as $v) {
		$v=stripslashes(trim($v));
		if ($v=='') continue;
		$v=iconv ('utf-8', $_o['html_charset'], $v);
        $id=get_data('id', $_o['cms_directories_data'], "content='$v' and dir='$dir_id'");
		if ($id===false) query("insert into {$_o['cms_directories_data']} (dir, content) values ('$dir_id', '$v')");
	}
}
//------------------------------------------------------------------------------
// Generate the HTML code for adding new directory item for other modules
//  $dir_id - ID of directory for adding new value
//  $func   - name of the javascript function which calling after adding the new value
function dirs_get_online_dir_value_add_html($dir_id, $func)
{
	global $_o;

	$dir_name=get_data('name', $_o['cms_directories'], "id='$dir_id'");
	$html=<<<stop
<div class="dir_online_value_add_container">
	<input type="hidden" id="dirs_dir_edit_id" value="$dir_id">
	<div class="hdr_row">Cправочник: $dir_name</div>
	<div class="hdr_row">Значение</div>
	<input type="text" id="dirs_value_content" value="" style="width: 98%;"/>
	<div class="dirs_link_menu_block">
		<input type="hidden" id="dirs_link_to_menu_id" value="0" />
		<input type="button" class="admin_tool_button" value="Связать с разделом" onClick="dirs_link_to_menu()" />
		<div id="dirs_link_menu_name"></div>
		<br>
	</div>
	<input type="button" value="Сохранить" onClick="dirs_dir_online_value_add_save($func)" />
</div>
stop;
	return $html;
}
//------------------------------------------------------------------------------
?>