<tr id="item_<?= $id; ?>" class="<?= $checked_in ?>">
    <td id="<?= 'cell_photo_' . $id ?>">
   
                        <img src="<?= $imageURL ?>" width="100" height="100">
               
    </td>
    <td id="<?= 'cell_name_' . $id ?>">
        <span class="editable-text" data-table="item" data-id-field="id" data-field="name" data-itemid="<?= $id ?>">
            <?= $name ?>
        </span>
    </td>
    <td id="<?= 'cell_category_' . $id ?>">
        <span class="editable-select" data-table="item" data-id-field="id" data-field="category" data-itemid="<?= $id ?>">
            <?= $category ?>
        </span>
    </td>
    <td id="<?= 'cell_subcategory_' . $id ?>">
        <span class="editable-select" data-table="item" data-id-field="id" data-field="subcategory" data-itemid="<?= $id ?>">
            <?= $subcategory ?>
        </span>
    </td>
    <td id="<?= 'cell_brand_' . $id ?>">
        <span class="editable-select" data-table="item" data-id-field="id" data-field="brand" data-itemid="<?= $id ?>">
            <?= $brand ?>
        </span>
    </td>
    <td id="<?= 'cell_model_' . $id ?>">
        <span class="editable-text" data-table="item" data-id-field="id" data-field="model" data-itemid="<?= $id ?>">
            <?= $model ?>
        </span>
    </td>
    <td id="<?= 'cell_cost_' . $id ?>" class="text-center">
        <span class="editable-select" data-table="item" data-id-field="id" data-field="cost" data-itemid="<?= $id ?>">
            $<?= $cost ?>
        </span>
    </td>
    <td id="<?= 'cell_checked_in_' . $id ?>"  class="text-center">
       <span class="checkInStatus" data-item-id="<?= $id;?>" data-toggle-value="<?= $checked_in; ?>"><?php if ($checked_in == 1) {?><i class="fa-solid text-success fa-house-circle-check"></i><?php } else { ?> <i class="fa-solid text-warning fa-house-circle-xmark"></i><?php } ?></span>
    </td>
    <td id="<?='cell_insured_'. $id ?>" class="text-center">
    <span class="toggleUpdate text-white" data-id="<?= $id ?>" data-id-field="id" data-table="item" data-field="insured" data-filter="insured" data-toggle-value="<?= $insured; ?>">
    <i class="fa-solid fa-circle<?= $insuredCheck;?>"></i><?= $insured; ?>
     </span>
    </td>
    <td id="<?= 'cell_action_' . $id ?>" class="text-center">
        <a href='item.php?action=edit&id=<?= $id ?>' class="" id="<?= $id ?>">
            <i class='fa-regular fa-pencil'></i>
        </a>
    </td>
</tr>
