<tr id="row_<?= $id; ?>" class="">

    <td id="<?= 'cell_name_' . $id ?>">
        <span class="editable-text" data-field="name" data-itemid="<?= $id ?>">
            <?= $name ?? ''; ?>
        </span>
    </td>
    <td id="<?= 'cell_city_' . $id ?>">
        <span class="editable-select" data-table="venue" data-id-field="venue_id" data-field="city" data-itemid="<?= $id ?>">
            <?= $city ?? 'edit'; ?>
        </span>
    </td>
    <td id="<?= 'cell_state_' . $id ?>">
        <span class="editable-select" data-table="venue" data-id-field="venue_id" data-field="state" data-itemid="<?= $id ?>">
            <?= $state ?? 'edit'; ?>
        </span>
    </td>

    <td id="<?= 'cell_venue_type_' . $id ?>">
        <span class="editable-select" data-table="venue" data-id-field="venue_id" data-field="venue_type" data-itemid="<?= $id ?>">
            <?= $type ?? 'edit'; ?>
        </span>
    </td>
    <td id="<?= 'cell_status_' . $id ?>">
        <span class="editable-select" data-table="venue" data-id-field="venue_id" data-field="status" data-itemid="<?= $id ?>">
            <?= $status ?? 'edit'; ?>
        </span>
    </td>
    <td id="<?= 'cell_action_' . $id ?>">
        <a href='venue.php?action=edit&id=<?= $id ?>' class="" id=<?= $id ?>>
            <i class='fa-regular fa-pencil'></i>
        </a>
    </td>
</tr>