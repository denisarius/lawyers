// -----------------------------------------------------------------------------
var banners_widget_ajax_manager_url='{@widgets_url@}/{@widget_path@}/{@widget_id@}_widget_dm.php';
var common_widget_ajax_manager_url='{@widgets_url@}/common_widget_dm.php';
// -----------------------------------------------------------------------------
function banners_show_banners_list()
{
	lang=$("#banners_language").val();
	type=$("#banners_banner_type_id").val();
	res=execQuery(banners_widget_ajax_manager_url, {section : "bannersGetBannersListHtml", language: lang, type: type});
	if (res=='') return;
//	alert(res);
	res=eval('(' + res + ')');
	$("#banners_banners_list").html(res.html);
	$("#banners_banner_sx").val(res.sx);
	$("#banners_banner_sy").val(res.sy);
	$("#banners_banner_quality").val(res.quality);
	$('[id^=banners_visible_]').imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent:false});
}
// -----------------------------------------------------------------------------
function banners_language_changed()
{
	banners_show_banners_list();
}
// -----------------------------------------------------------------------------
function banners_banner_type_changed()
{
	banners_show_banners_list();
}
// -----------------------------------------------------------------------------
function banners_banner_add()
{
	sx=parseInt($("#banners_banner_sx").val(), 10);
	type=$("#banners_banner_type_id").val();
	if (sx<500) sx=500;
	admin_info_show('Добавление нового баннера', '<center><img src="images/'+loading_icon+'"></center>', sx+40);
	res=execQuery(banners_widget_ajax_manager_url, {section : "bannersGetBannerAddHtml", type:type});
	admin_info_change('', res);
	$("#banners_image_img").load(function(){ admin_info_center(); });
}
// -----------------------------------------------------------------------------
function banners_banner_edit_cancel()
{
	id=$("#banner_edit_banner_id").val();
	file=$("#banners_add_banner_image_file").val();
	execQuery(banners_widget_ajax_manager_url, {section : "bannersEditCancel", id:id, file: file});
	admin_info_close();
}
// -----------------------------------------------------------------------------
function banners_banner_add_image_load()
{
	admin_load_image(banners_banner_add_image_uploaded, "*.jpg;*.png;*.gif");
}
// -----------------------------------------------------------------------------
function banners_banner_add_image_uploaded(file)
{
	sx=parseInt($("#banners_banner_sx").val(), 10);
	sy=parseInt($("#banners_banner_sy").val(), 10);
	res=execQuery(common_widget_ajax_manager_url, {section: "commonTempImageProcess", file: file, sx: sx, sy: sy, max: 1, quality: 99});
	html="\
<div class='banners_banner_add_crop_container'>\
<img src='"+res+"' id='objects_croped_image' /><br>\
<input type='button' value='Обработать изображение' onClick='banners_banner_add_store_image()' />\
<input type='hidden' id='banners_add_crop_file' value='"+res+"'/>\
<input type='hidden' id='banners_add_image_coor_x' />\
<input type='hidden' id='banners_add_image_coor_y' />\
<input type='hidden' id='banners_add_image_coor_w' />\
<input type='hidden' id='banners_add_image_coor_h' />\
<div>\
";
	admin_info_show('Изменение размеров изображения', html, 400);
	$("#objects_croped_image").load(function(){
		admin_info_set_width($("#objects_croped_image").width()+40);
		admin_info_center();
		w=$("#objects_croped_image").width();
		h=$("#objects_croped_image").height();
/*
		dx=w/sx;
		sy=sy*dx;
		y=(h-sy)/2;
*/
		dx=w/sx; dy=h/sy;
		if (dx>dy) sy=sy*dx;
		else sx=sx*dy;
		y=(h-sy)/2;
		x=(w-sx)/2;
		$('#objects_croped_image').Jcrop({ allowSelect: false, allowResize: false, allowMove: true, setSelect: [x, y, x+sx, y+sy], onChange: banners_banner_add_update_coords });
//		$('#objects_croped_image').Jcrop({ allowSelect: false, allowResize: false, allowMove: true, setSelect: [0, y, w, y+sy], onChange: banners_banner_add_update_coords });
	});
}
// -----------------------------------------------------------------------------
function banners_banner_add_update_coords(c)
{
	$("#banners_add_image_coor_x").val(c.x);
	$("#banners_add_image_coor_y").val(c.y);
	$("#banners_add_image_coor_w").val(c.w);
	$("#banners_add_image_coor_h").val(c.h);
};
// -----------------------------------------------------------------------------
function banners_banner_add_store_image()
{
	x=$("#banners_add_image_coor_x").val();
	y=$("#banners_add_image_coor_y").val();
	w=$("#banners_add_image_coor_w").val();
	h=$("#banners_add_image_coor_h").val();
	file=$("#banners_add_crop_file").val();
	quality=$("#banners_banner_quality").val();
	res=execQuery(banners_widget_ajax_manager_url, {section : "bannersCropImage", file: file, x:x, y:y, w:w, h:h, quality: quality});
	res=eval('(' + res + ')');
	$("#banners_add_banner_image").attr('src', res.img);
	$("#banners_add_banner_image_file").val(res.file);
	admin_info_close();
}
// -----------------------------------------------------------------------------
function banners_edit_delete_link(field)
{
	$('#'+field+'id').val('');
	$('#'+field+'name').html('');
	$('#'+field+'block').hide();
}
// -----------------------------------------------------------------------------
function banners_banner_edit_save()
{
	id=$("#banner_edit_banner_id").val();
	img=$("#banners_add_banner_image").attr('src');
	if (img=='images/space.gif')
	{
		show_message_warning('Сохранение баннера', 'Необходимо загрузить изображение');
		return;
	}
	lang=$("#banners_language").val();
	file=$("#banners_add_banner_image_file").val();
	type=$("#banners_banner_type_id").val();
	menu_item=$("#banner_edit_menu_item_id").val();
	link=$("#banner_edit_link_id").val();
	text=$("#banner_edit_text").val().trim();
	url=$("#banner_edit_url").val().trim();
	if (id!=-1) {
		// Записываем изменения существующего баннера
		res=execQuery(banners_widget_ajax_manager_url, {section : "bannersEditBannerSave", id: id, language: lang, file: file, type:type, menu_item:menu_item, text:text, url:url, menu_link: link});
	}
	else {
		// Записываем новый баннер
		res=execQuery(banners_widget_ajax_manager_url, {section : "bannersAddBannerSave", language: lang, file: file, type:type, menu_item:menu_item, text:text, url:url, menu_link: link});
	}
	admin_info_close();
	$("#banners_banners_list").html(res);
	$('[id^=banners_visible_]').imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent:false});
}
// -----------------------------------------------------------------------------
function banners_banner_delete(banner_id)
{
	lang=$("#banners_language").val();
	type=$("#banners_banner_type_id").val();
	res=execQuery(banners_widget_ajax_manager_url, {section : "bannersDeleteBanner", language: lang, type:type, id: banner_id});
	$("#banners_banners_list").html(res);
	$('[id^=banners_visible_]').imagecbox({image: "/images/controls/checkbox_green_24.png", track_parent:false});
}
// -----------------------------------------------------------------------------
function banners_edit_menu_link_select()
{
	admin_info_show('Выберите подраздел', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetLinkMenuHTML", func: 'banners_edit_menu_link_selected'});
	admin_info_change('', res, null);
}
// -----------------------------------------------------------------------------
function banners_edit_menu_link_selected(id, menu, menu_item)
{
	admin_info_close();
	$("#banner_edit_link_id").val(id);
	$("#banner_edit_link_name").html(menu_item);
	$("#banner_edit_link_block").show();
}
//------------------------------------------------------------------------------
function banners_edit_menu_item_select()
{
	admin_info_show('Выберите подраздел', '<center><img src="images/'+loading_icon+'"></center>');
	res=execQuery(common_widget_ajax_manager_url, {section : "commonGetLinkMenuHTML", func: 'banners_edit_menu_item_selected'});
	admin_info_change('', res, null);
}
// -----------------------------------------------------------------------------
function banners_edit_menu_item_selected(id, menu, menu_item)
{
	admin_info_close();
	$("#banner_edit_menu_item_id").val(id);
	$("#banner_edit_menu_item_name").html(menu_item);
	$("#banner_edit_menu_item_block").show();
}
//------------------------------------------------------------------------------
function banners_toggle_visible(id)
{
	img="<img src='images/"+loading_icon_24+"' id='banners_visible_loader_"+id+"' style='vertical-align: text-bottom;'>";

	check_img_el=$("#banners_visible_"+id+"_img");
	check_img_el.after(img);
	check_img_el.css("display", "none");
	if ($("#banners_visible_"+id).is(":checked")) ch=0;
	else ch=1;
	res=execQuery(banners_widget_ajax_manager_url, {section : "bannersSetNewsVisible", id: id, visible: ch});
	$("#banners_visible_loader_"+id).remove();
	check_img_el.css("display", "inline");
}
//------------------------------------------------------------------------------
function banners_banner_edit(id)
{
	sx=parseInt($("#banners_banner_sx").val(), 10);
	type=$("#banners_banner_type_id").val();
	if (sx<500) sx=500;
	admin_info_show('Изменение баннера', '<div style="text-align: center"><img src="images/'+loading_icon+'"></div>', sx+40);
	res=execQuery(banners_widget_ajax_manager_url, {section : "bannersGetEditHTML", type:type, id: id});
	admin_info_change('', res);
	$("#banners_image_img").load(function(){ admin_info_center(); });
}
//------------------------------------------------------------------------------
