<?php
$_cms_banners_table='banners';

//$_cms_banners_image_sx=962;
//$_cms_banners_image_sy=270;
//$_cms_banners_crop_jpg_quality=80;

$_base_site_banners_images_path="$_base_site_root_path/data/banners";
$_base_site_banners_images_url="$_base_site_root_url/data/banners";

$_cms_banners_description=array(
//	'1'=>array('name'=>'Верхний баннер (841 x 280)', 'sx'=>841, 'sy'=>280, 'quality'=>85,
//		'menu_item'=>true, 'url'=>true, 'text'=>true, 'link'=>true),
	'1'=>array('name'=>'Верхний баннер (1150 x 280)', 'sx'=>1150, 'sy'=>280, 'quality'=>85,
		'menu_item'=>true, 'url'=>true, 'text'=>false, 'link'=>true),
	'2'=>array('name'=>'Боковой баннер (240 x 400)', 'sx'=>240, 'sy'=>400, 'quality'=>85,
		'menu_item'=>true, 'url'=>true, 'text'=>true, 'link'=>true),
	'3'=>array('name'=>'Баннер на карте объектов (240 x 400)', 'sx'=>240, 'sy'=>400, 'quality'=>85,
		'menu_item'=>true, 'url'=>true, 'text'=>true, 'link'=>true),
);
?>