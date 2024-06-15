<h3>All Packing Lists</h3>
<table class="table" id="packingListTable" width="100%">
    <thead>
        <tr>
            <th>Name</th>
            <th></th>
            <th></th>
            <th></th>

        </tr>
    </thead>
    <tbody> <?php
    foreach ($lists as $list) {
        echo '<tr id=list_'.$list['id'].'><td><a href="?action=view&id='.$list['id'].'">'.$list['name'].'</a></td><td><a href="?action=edit&id='.$list['id'].'"><i class="fa-regular fa-pencil"></i></a></td><td><a href="?action=clone&id='.$list['id'].'"><i class="fa-regular fa-clone"></i></a></td><td><a href="#" class="delete" id="'.$list['id'].'" data-remove="#list_'.$list['id'].'" data-field="id" data-table="list" data-value="'.$list['id'].'"><i class="fa-regular fa-trash"></i></a></td></tr></td>';
    }
    ?>
</table>