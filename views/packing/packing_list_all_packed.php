<?php
foreach ($packedItems[0]['packed'] as $packed) {
	
	$name = $packed['subcategory'] != '' ? $packed['subcategory'] : $packed['item_name'];
	$count = $packed['count_category'] != '0' ? $packed['count_category'] : $packed['packed'];
	$countNeeded = $packed['count_needed'] != '0' ? $packed['count_needed'] : $packed['count_needed'];

	echo "<tr><td>".$name."</td><td>".$count."/".$countNeeded."</td></tr>";
}

foreach ($items[0]['missing'] as $missing) {
	$name = $missing['subcategory'] != '' ? $missing['subcategory'] : $missing['item_name'];
	$count = $missing['count_category'] != '0' ? $missing['count_category'] : $missing['packed'];
	$countNeeded = $missing['count_needed'] != '0' ? $missing['count_needed'] : $missing['count_needed'];

	echo "<tr class='missing'><td>".$name."</td><td>".$count."/".$countNeeded."</td></tr>";

	}

?>
<h3>All Packed Items</h3>
	<table>
		<?php 
		foreach ($checkedOutItems as $item) {
			echo '<tr><td>'.$item['name'].'</tr></td>';
			}
		?>
	</table>