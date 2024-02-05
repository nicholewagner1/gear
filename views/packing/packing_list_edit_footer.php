
				</tbody>
			</table>
	
	</div>
</div>
<div class="row m-3">
	<div class="col">
		<div class="btn btn-secondary" id="itemNew">
			<i class="fa-regular fa-square-plus"></i> New Item
		</div>
	</div>
	<div class="col">
		<input class="btn btn-primary" type="submit" value="Update List">
	</div>
	<div class="col">
		<a href="#" class=" btn btn-danger delete" id="<?= $id;?>" data-remove="" data-field="id" data-table="list" data-value="<?= $id;?>"<i class="fa-regular fa-trash"></i> Delete</a>
</div>
	</form>
	
	
	<?php if ($_GET['action'] == 'clone'){ ?>
		<script>
			$(document).ready(function () {

			$('#id').val('');
			var currentName = $('#name').val();
			var newName = currentName +' - COPY';
			$('#name').val(newName);

			});
		</script>	
	<?php }
	?>