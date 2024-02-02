<h3>All Packing Lists</h3>
<table>
	<?php 
	foreach ($lists as $list) {
		echo '<tr id=list_'.$list['id'].'><td>'.$list['name'].'</td><td><a href="?action=edit&id='.$list['id'].'">edit</td><td><a href="#" class="deleteList" data-value="'.$list['id'].'">delete</a></td></tr></td>';
		}
	?>
</table>