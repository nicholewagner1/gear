<div class="row">
    <div class="col">
        <h3><?=$packedItems[0]['name']; ?></h3>
        <table>
            <tr>
                <td>Item</td>
                <td>Count</td>
            </tr>
            <?php
                    foreach ($packedItems[0]['packed'] as $packed) {
                        $name = $packed['subcategory'] != '' ? $packed['subcategory'] : $packed['item_name'];
                        $count = $packed['count_category'] != '0' ? $packed['count_category'] : $packed['packed'];
                        $countNeeded = $packed['count_needed'] != '0' ? $packed['count_needed'] : $packed['count_needed'];

                        echo "<tr><td>".$name."</td><td>".$count."/".$countNeeded."</td></tr>";
                    }

                    foreach ($packedItems[0]['missing'] as $missing) {
                        $name = $missing['subcategory'] != '' ? $missing['subcategory'] : $missing['item_name'];
                        $count = $missing['count_category'] != '0' ? $missing['count_category'] : $missing['packed'];
                        $countNeeded = $missing['count_needed'] != '0' ? $missing['count_needed'] : $missing['count_needed'];
                        echo "<tr class='missing'><td>".$name."</td><td>".$count."/".$countNeeded."</td></tr>";
                    }
                ?>
        </table>
    </div>
    <div class="col">
        <h4>All Packed Items</h4>
        <table>
            <?php
                if ($checkedOutItems) {
                    //var_dump($checkedOutItems);
                    foreach ($checkedOutItems as $item) {
                        echo '<tr><td><a href="/item.php?action=edit&id='.$item['id'].'">'.$item['name'].'</a></tr></td>';
                    }
                }
                ?>
        </table>
    </div>
</div>