<h3>All Packing Lists</h3>
<table>
	<?php 
	foreach ($lists as $list) {
		echo '<tr><td>'.$list['name'].'</td><td><a href="?action=edit&id='.$list['id'].'">edit</td></tr></td>';
		}
	?>
</table>