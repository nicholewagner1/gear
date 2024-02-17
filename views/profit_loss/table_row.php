<tr id="row_<?= $id; ?>" class="<?= $income;?> <?= $is_future;?>">

    <td id="<?= 'cell_date_' . $id ?>">
        <span class="editable-text" data-table="profit_loss" data-id-field="id" data-field="date" data-itemid="<?= $id ?>">
            <?= $date ?>
        </span>
    </td>
    <td id="<?= 'cell_name_' . $id ?>">
        <span class="editable-text" data-table="profit_loss" data-id-field="id" data-field="name" data-itemid="<?= $id ?>">
            <?= $name ?>
        </span>
    </td>
    <td id="<?= 'cell_category_' . $id ?>">
        <span class="editable-select" data-table="profit_loss" data-id-field="id" data-field="category" data-itemid="<?= $id ?>">
            <?= $category ?>
        </span>
    </td>
    <td id="<?= 'cell_subcategory_' . $id ?>">
        <span class="editable-select" data-table="profit_loss" data-id-field="id" data-field="subcategory" data-itemid="<?= $id ?>">
            <?= $subcategory ?>
        </span>
    </td>
    <td id="<?= 'cell_amount_' . $id ?>">
        $<span class="editable-text" data-table="profit_loss" data-id-field="id" data-field="amount" data-itemid="<?= $id ?>">
            <?= $amount ?>
        </span>
    </td>

    <td id="<?= 'cell_paid_' . $id ?>" class="text-center">
        <span class="toggleUpdate text-white" data-id="<?= $id ?>" data-id-field="id" data-table="profit_loss" data-field="paid" data-filter="paid" data-toggle-value="<?= $paid; ?>">
            <i class="fa-solid fa-circle<?= $paidCheck;?>"></i><?= $paid; ?>
        </span>

    </td>
    <td id="<?= 'cell_tax_forms_' . $id ?>" class="text-center">
        <span class="toggleUpdate text-white" data-id="<?= $id ?>" data-id-field="id" data-table="profit_loss" data-field="tax_forms" data-filter="tax_forms" data-toggle-value="<?= $tax_forms; ?>">
            <i class="fa-solid fa-circle<?= $tax_forms_check;?>"></i><?= $paid; ?>
        </span>

    </td>

    <td id="<?= 'cell_action_' . $id ?>">
        <a href='profit_loss.php?action=edit&id=<?= $id ?>' class="" id="<?= $id ?>">
            <i class='fa-regular fa-pencil'></i></a>
        |
        <a href="#" class="delete" id="<?= $id ?>" data-value="<?= $id ?>" data-remove="#row_<?= $id ?>" data-field="id" data-table="profit_loss">
            <i class='fa-regular fa-trash'></i>
        </a>
    </td>
</tr>