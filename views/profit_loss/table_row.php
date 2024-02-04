<tr id="row_<?= $id; ?>" class="<?= $income;?>">
   
    <td id="<?= 'cell_date_' . $id ?>">
        <span class="editable-text" data-field="date" data-itemid="<?= $id ?>">
            <?= $date ?>
        </span>
    </td>
    <td id="<?= 'cell_name_' . $id ?>">
        <span class="editable-text" data-field="name" data-itemid="<?= $id ?>">
            <?= $name ?>
        </span>
    </td>
    <td id="<?= 'cell_type_' . $id ?>">
        <span class="editable-select" data-field="type" data-itemid="<?= $id ?>">
            <?= $type ?>
        </span>
    </td>
    <td id="<?= 'cell_amount_' . $id ?>">
        <span class="editable-text" data-field="amount" data-itemid="<?= $id ?>">
           $<?= $amount ?>
        </span>
    </td>
   
    <td id="<?= 'cell_paid_' . $id ?>">
        <span class="" data-field="paid" data-itemid="<?= $id ?>">
        <i class="fa-solid fa-circle<?= $paidCheck;?>"></i>        </span>
    </td>
    <td id="<?= 'cell_details_' . $id ?>"  colspan=6>
        <span data-field="details" data-itemid="<?= $id ?>">
            <?= $account; ?>
        </span>
    </td>
    <td id="<?= 'cell_action_' . $id ?>">
        <a href='profit_loss.php?action=edit&id=<?= $id ?>' class="" id=<?= $id ?>>
            <i class='fa-regular fa-pencil'></i>
        </a>
    </td>
</tr>
