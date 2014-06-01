<?php
global $_o;

$objectData = get_data_array(
	'o.id, o.type, o.name, o.note',
	"{$_o['cms_objects_table']} o JOIN {$_o['cms_objects_details']} d ON (d.node = o.id)",
	"d.typeId='section' AND d.value=".get_menu_item_id()
);
if (!$objectData)
{
	show_content_404();
	return;
}

set_page_title($objectData['name']);

$tableData = cms_get_objects_details($objectData['type'], $objectData['id'], 'prices', 100);
?>

<div class="text_container">
	<?php echo $objectData['note']; ?>
	<h3>Стоимсоть услуг</h3>
</div>
<table class="prices_container">
	<tbody>
	<tr>
		<th>Название услуги</th>
		<th>Описание услуги.</th>
		<th>Цена</th>
	</tr>

	<?php foreach ($tableData as $row): ?>
		<tr>
			<td><?php echo $row[0]; ?></td>
			<td><?php echo $row[1]; ?></td>
			<td class="price"><?php echo $row[2]; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>