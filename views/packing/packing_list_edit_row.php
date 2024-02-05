
					<?php
					
							$items = $lists[$id]['items'] ?? '';
							$i = '0';
							if ($items){
							foreach ($items as $listItem) {
								$i++;
								?>
							<tr id="<?php echo $i; ?>">
								<td>
									<input type="text" class="form-control"  name="count_needed" value="<?php echo $listItem['count_needed'] ?? ''; ?>">
								</td>
								<td>
									<select name="item" id="item_<?php echo $i; ?>" class="js-multiple-select autocompleteItem form-control" multiple value="<?php echo $listItem['item'] ?? ''; ?>"></select>
								</td>
								<td>
									<select id="subcategory_<?php echo $i; ?>" class="autocomplete subcategory form-control" multiple data-table="item" value="<?php echo $listItem['subcategory'] ?? ''; ?>" name="subcategory" ></select>
								</td>
							</tr>
							<?php } 
						}
					?>
