<tr id="row_<?= $id; ?>" class="">

    <td id="<?= 'cell_date_' . $id ?>">

        <?= $date ?? ''; ?>

    </td>
    <td id="<?= 'cell_name_' . $id ?>">

        <?= $name ?? ''; ?>

    </td>
    <td id="<?= 'cell_notes_' . $id ?>">
        <span class="editable-text" data-table="gig" data-id-field="gig_id" data-field="gig_notes" data-itemid="<?= $id ?>">
            <?= $gig_notes ?? ' -- '; ?>
        </span>
    </td>
    <td id="<?= 'cell_action_' . $id ?>">
        <a href='profit_loss.php?action=edit&id=<?= $id ?>' class="" id=<?= $id ?>>
            <i class='fa-regular fa-pencil'></i>
        </a>
    </td>
</tr>