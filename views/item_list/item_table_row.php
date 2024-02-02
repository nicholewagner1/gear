<tr id="item_<?= $id; ?>" class="<?= $checked_in ?>">
    <td id="<?= 'cell_photo_' . $id ?>">
   
                        <img src="<?= $imageURL ?>" width="100" height="100">
               
    </td>
    <td id="<?= 'cell_name_' . $id ?>">
        <span class="editable-text" data-field="name" data-itemid="<?= $id ?>">
            <?= $name ?>
        </span>
    </td>
    <td id="<?= 'cell_category_' . $id ?>">
        <span class="editable-select" data-field="category" data-itemid="<?= $id ?>">
            <?= $category ?>
        </span>
    </td>
    <td id="<?= 'cell_subcategory_' . $id ?>">
        <span class="editable-select" data-field="subcategory" data-itemid="<?= $id ?>">
            <?= $subcategory ?>
        </span>
    </td>
    <td id="<?= 'cell_brand_' . $id ?>">
        <span class="editable-select" data-field="brand" data-itemid="<?= $id ?>">
            <?= $brand ?>
        </span>
    </td>
    <td id="<?= 'cell_model_' . $id ?>">
        <span class="editable-text" data-field="model" data-itemid="<?= $id ?>">
            <?= $model ?>
        </span>
    </td>
    <td id="<?= 'cell_cost_' . $id ?>">
        <span class="editable-select" data-field="cost" data-itemid="<?= $id ?>">
            <?= $cost ?>
        </span>
    </td>
    <td id="<?= 'cell_checked_in_' . $id ?>">
        <span class="" data-field="checked_in" data-itemid="<?= $id ?>">
            <?= $checked_in ?>
        </span>
    </td>
    <td id="<?= 'cell_action_' . $id ?>">
        <a href='item.php?action=edit&id=<?= $id ?>' class="" id=<?= $id ?>>
            <i class='fa-regular fa-pencil'></i>
        </a>
    </td>
</tr>
