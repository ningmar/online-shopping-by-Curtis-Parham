<h3 class="text-center">Popular Items</h3>
<?php 
$popQ = $db->query("select * from cart where paid = 1 order by id desc limit 5");
$resul = array();
while ($row = mysqli_fetch_assoc($popQ)) {
	$resul[] = $row;
}
$row_count = $popQ->num_rows;
$used_ids = array();
for ($i=0; $i < $row_count; $i++) { 
	$json_items = $resul[$i]['items'];
	$items = json_decode($json_items,true);
	foreach ($items as $item) {
		if (!in_array($item['id'], $used_ids)) {
			$used_ids[] = $item['id'];
		}
	}
}
?>

<div id="recent_widget">
	<table class="table table-condensed">
		<?php foreach ($used_ids as $id): 
           $prodQ = $db->query("select * from products where id = '{$id}'");
           $prod = mysqli_fetch_assoc($prodQ);
		?>
		<tr>
			<td>
				<?=substr($prod['title'], 0.15); ?>
			</td>
			<td>
				<a class="text-primary" onclick="detailmodal('<?=$id;?>')">View</a>
			</td>
		</tr>
        <?php endforeach; ?>
	</table>
</div>