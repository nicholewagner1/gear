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
    <td id="<?= 'cell_type_' . $id ?>">
        <span class="editable-select" data-table="profit_loss" data-id-field="id" data-field="type" data-itemid="<?= $id ?>">
            <?= $type ?>
        </span>
    </td>
    <td id="<?= 'cell_amount_' . $id ?>">
        <span class="editable-text" data-table="profit_loss" data-id-field="id" data-field="amount" data-itemid="<?= $id ?>">
           $<?= $amount ?>
        </span>
    </td>
   
    <td id="<?= 'cell_paid_' . $id ?>">
        <span class="toggleUpdate text-white" data-id="<?= $id ?>" data-id-field="id" data-table="profit_loss" data-field="paid" data-filter="paid" data-toggle-value="<?= $paid; ?>">
        <i class="fa-solid fa-circle<?= $paidCheck;?>"></i><?= $paid; ?>
           </span>
         
    </td>

    <td id="<?= 'cell_action_' . $id ?>">
        <a href='profit_loss.php?action=edit&id=<?= $id ?>' class="" id="<?= $id ?>">
            <i class='fa-regular fa-pencil'></i></a>
     <span class="delete" id="<?= $id ?>" data-value="<?= $id ?>" data-remove="#row_<?= $id ?>" data-field="id" data-table="profit_loss">
            <i class='fa-regular fa-trash'></i>
     </span>
    </td>
</tr>
