<div class="col-md-4 m-.5 col-lg-3">
    <div class="card itemCard <?= $checked_in ?>" id="item_<?= $id; ?>">
        <div class="card-header">
            <h5 class="card-title"> <?= $name ?> </h5>

        </div>
        <img src='<?= $imageURL ?>' class="img-fluid card-img-top">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <?= $category ?>
                </div>
                <div class="col">
                    <?= $subcategory ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= $brand ?>
                </div>
                <div class="col">
                    <?= $model ?>
                </div>
                <div class="col">
                    <a href='item.php?action=edit&id=<?= $id ?>' class="" id=<?= $id ?>>
                        <i class='fa-regular fa-pencil'></i>
                    </a>
                </div>
                <div class="col">
                    <span id="checkInStatus_<?= $id;?>" class="checkInStatus" data-item-id="<?= $id;?>" data-toggle-value="<?= $items[0]['checked_in'] ?? '' ; ?>"><?php if ($items[0]['checked_in'] == 1) {?>
                        <i class="fa-solid text-success fa-house-circle-check"></i><?php } else { ?> <i class="fa-solid text-warning fa-house-circle-xmark"></i>
                        <?php } ?></span>
                </div>
            </div>

        </div>
    </div>
</div>