<h2>Add/Edit Item</h2>
<form id="addEditItemForm" method="post">
    <div class="form-group">
        <div class="row mt-3">
            <div class="col">
                <input type="text" hidden id="id" class="" name="id" value="<?= $id ?>">
                <label for="date">Date Acquired:</label>
                <input type="date" class="form-control" name="date_acquired" value="<?= $date_acquired ?>">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="name">Item Name:</label>
        <input class="form-control" type="text" value="<?php echo $items[0]['name'] ?? ''; ?>" name="name" required>
    </div>
    <div class="form-group">
        <div class="row mt-3">
            <div class="col">
                <div class="row mb-4">

                    <?php
                        $imageURLs = '';
                        if ($images) {
                            foreach ($images as $image) {
                                if ($image['type'] == 'photo') {
                                    echo '<div class="col-lg-3 col-md-4 col-sm-12">';
                                    echo "<div class='card'> <div class='card-body'>";

                                    echo "<div class='cardImg'><a href='/images/items/".$image['url']."' data-toggle='lightbox'> <img src='/images/items/".$image['url']."' class=''></a></div>";
                                    echo " <div class='card-footer'><div class='row'>";
                                    if ($image['thumbnail'] == '1') {
                                        echo "<div class='col thumbnail' id='thumb_".$image['item_id']."' data-url='".$image['url']."' data-set='".$image['thumbnail']."'  data-item-id='".$image['item_id']."'><i class='fa-solid fa-thumbtack text-primary'></i></div>";
                                    } elseif ($image['serial'] == '1') {
                                        echo "<div class='col serial' id='serial_".$image['item_id']."' data-url='".$image['url']."' data-set='".$image['serial']."' data-item-id='".$image['item_id']."'><i class='fa-solid fa-key text-primary'></i></div>";
                                    } else {
                                        echo "<div class='col thumbnail' id='thumb_".$image['item_id']."' data-url='".$image['url']."' data-item-id='".$image['item_id']."' data-set=".$image['thumbnail']." ><i class='fa-solid fa-thumbtack text-secondary'></i></div>";
                                        echo "<div class='serial col' id='serial_".$image['item_id']."' data-url='".$image['url']."' data-set='".$image['serial']."'  data-item-id='".$image['item_id']."'><i class='fa-solid fa-key text-secondary'></i></div>";
                                    }
                                    echo "<div class='col deleteImage' id='delete_".$image['item_id']."' data-url='".$image['url']."' data-item-id='".$image['item_id']."'><i class='fa-solid fa-trash text-danger'></i></div>";
                                    $imageURLs .= $image['url']. ',';

                                    echo "</div></div></div></div></div>"; //end card
                                }
                            }
                        } ?>
                    <input type="text" id="photo" hidden name="photo" value="<?= $imageURLs ?>">
                </div>
                <label class="form-label" for="photoUpload">Photos:</label>
                <input type="file" id="photoUpload" class="form-control" name="photoUpload" multiple="multiple" accept="image/*">
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-3">
            <label for="status">Status:</label>
            <select id="status" class="js-multiple-select autocomplete status form-control" data-table="item" multiple value="<?php echo $items[0]['status'] ?? ''; ?>" name="status"></select>
        </div>
        <div class="col-sm-3">
            <label for="year">Year:</label>
            <input type="text" id="year" class="form-control" name="year" value="<?php echo $items[0]['year'] ?? ''; ?>">
        </div>
        <div class="col-sm-3">
            <label for="serial_number">Serial Number:</label>
            <input type="text" id="serial_number" class="form-control" name="serial_number" value="<?php echo $items[0]['serial_number'] ?? ''; ?>">
        </div>
        <div class="col-sm-3">
            <label for="asset_tag">Asset Tag: <span class="checkInStatus" id="checkInStatus_<?= $id;?>" data-item-id="<?= $id;?>" data-toggle-value="<?= $items[0]['checked_in'] ?? '' ; ?>"><?php if ($items[0]['checked_in'] == 1) {?>
                    <i class="fa-solid text-success fa-house-circle-check"></i><?php } else { ?> <i class="fa-solid text-warning fa-house-circle-xmark"></i>
                    <?php } ?></span></label>
            <input type="text" id="asset_tag" class="form-control" name="asset_tag" value="<?php echo $items[0]['asset_tag'] ?? ''; ?>">
        </div>
    </div>
    </div>
    <div class="form-group">
        <div class="row mt-3">
            <div class="col-sm-3">
                <label for="brand">Brand:</label>
                <select id="brand" class="autocomplete size form-control" multiple data-table="item" value="<?php echo $items[0]['brand'] ?? ''; ?>" name="brand"></select>
            </div>
            <div class="col-sm-3">
                <label for="model">Model:</label>
                <input type="text" id="model" class=" model form-control" multiple value="<?php echo $items[0]['model'] ?? ''; ?>" name="model">
            </div>
            <div class="col-sm-3">
                <label for="category">Category:</label>
                <select id="category" class="js-multiple-select autocomplete size form-control" data-table="item" multiple value="<?php echo $items[0]['category'] ?? ''; ?>" name="category"></select><br>
            </div>
            <div class="col-sm-3">
                <label for="subcategory">Subcategory:</label>
                <select id="subcategory" data-table="item" class="autocomplete size form-control" multiple value="<?php echo $items[0]['subcategory'] ?? ''; ?>" name="subcategory"></select><br>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row mt-3">
            <div class="col-sm-3">
                <label for="purchase_location">Purchase Location</label>
                <select id="purchase_location" data-table="item" class="autocomplete size form-control" multiple value="<?php echo $items[0]['purchase_location'] ?? ''; ?>" name="purchase_location"></select>
            </div>
            <div class="col-sm-3">
                <label for="purchase_price">Purchase Price:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                    </div>
                    <input type="text" id="purchase_price" class=" size form-control" value="<?php echo $items[0]['purchase_price'] ?? ''; ?>" name="purchase_price"></input>
                </div>
            </div>
            <div class="col-sm-3">
                <label for="replacement_value">Replacement Value</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                    </div>
                    <input type="text" id="replacement_value" class="replacement_value form-control" value="<?php echo $items[0]['replacement_value'] ?? ''; ?>" name="replacement_value"></input>
                </div>
                <input type="text" hidden id="insured" class="insured form-control" value="<?php echo $items[0]['insured'] ?? ''; ?>" name="insured"></input>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-3">
                <div class="custom-file">
                    <label class="form-label" for="documentUpload">Documents:</label>
                    <input type="file" id="documentUpload" class="form-control" name="documentUpload" multiple="multiple" accept="image/*,application/pdf">
                </div>
                <input type="text" id="document" hidden class="custom-file-input" name="document" value="<?php echo $documentURLs ?? ''; ?>">
            </div>

            <?php
                        if ($id != '' && $images) {
                            echo "<div class='col mt-3'><h6 class='form-label'>Uploaded Documents</h6>";
                            $documentURLs = '';
                            foreach ($images as $document) {
                                if ($document['type'] == 'document') {
                                    echo "<a href='/images/items/".$document['url']."' class='tiny'>".$document['url']."</a> <br>";
                                }
                                $documentURLs .= $document['url']. ',';
                            }
                            echo "</div>";
                        }?>


        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <label for="notes">Notes</label>
            <textarea id="notes" class="notes form-control" value="" name="notes"><?php echo $items[0]['notes'] ?? ''; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="row m-3">
            <div class="col">
                <button type="button" id="addEditItemFormButton" class="btn btn-primary mt-3">Submit</button>
            </div>
            <div class="col">
                <button id="deleteItem" type="button" data-value="<?= $id ?>" class=" float-right btn btn-danger mt-3">Delete Item</button>
            </div>
        </div>
    </div>
</form>