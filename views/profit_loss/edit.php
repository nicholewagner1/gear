<form id="profitLossForm">
    <input type="hidden" name="id" value="<?= $id ?? ''; ?>" />

    <div class="form-group row">
        <div class="col">
            <label for="date">Date:</label>
            <input type="date" class="form-control" name="date" value="<?= $date ?? ''; ?>" required />
        </div>
        <div class="col">
            <label for="name">Name:</label>
            <select data-table="profit_loss" class="autocomplete name form-control" multiple id="name" name="name" value="<?= $name ?? ''; ?>" /></select>
        </div>
    </div>

    <div class="form-group row">
        <div class="col">
            <label for="category">Category:</label>
            <select data-table="profit_loss" class="autocomplete category form-control" multiple id="category" name="category" value="<?= $category ?? ''; ?>" /></select>
        </div>
        <div class="col">
            <label for="category">Subcategory:</label>

            <select data-table="profit_loss" class="autocomplete subcategory form-control" multiple id="subcategory" name="subcategory" value="<?= $subcategory ?? ''; ?>" /></select>

        </div>
        <div class="col">
            <label for="amount">Amount:</label>
            <input type="number" id="amount" class="form-control" name="amount" value="<?= $amount ?? ''; ?>" required />
        </div>
        <div class="col">

            <label for="paid">Paid:</label>
            <span class="toggleUpdate text-white" data-id="<?= $id ?>" data-id-field="id" data-table="profit_loss" data-field="paid" data-filter="paid" data-toggle-value="<?= $paid ; ?>">
                <i class="fa-solid fa-circle<?= $paidCheck ?? '';?>"></i> </span>
            <input type="number" id="paid" data-start-value="<?= $paid;?>" class="form-control" name="paid" value="" />
        </div>
        <div class="col">
            <label for="tax_forms">Tax Forms:</label>
            <span class="toggleUpdate text-white" data-id="<?= $id ?>" data-id-field="id" data-table="profit_loss" data-field="tax_forms" data-filter="tax_forms" data-toggle-value="<?= $tax_forms; ?>">
                <i class="fa-solid fa-circle<?= $tax_forms_check ?? '';?>"></i> </span>
            <input type="number" id="tax_forms" data-start-value="<?= $tax_forms;?>" class="form-control" name="tax_forms" value="" />

        </div>
    </div>
    <div class="form-group">
        <label for="profit_loss_notes">Notes:</label>
        <textarea class="form-control" name="profit_loss_notes"><?= $notes ?? ''; ?></textarea>
    </div>



    <div class="form-group">
        <label for="account">Account:</label>
        <select data-table="profit_loss" class="autocomplete account form-control" multiple id="account" name="account" value="<?= $account ?? ''; ?>" /></select>
    </div>

    <div id="gigInfo" class="hidden mt-3">
        <!-- Additional fields for insertGigInfo function here-->
        <div class="form-group">
            <input type="hidden" name="gig_id" value="<?= $gig_id ?? ''; ?>" />

            <label for="venue_id">Venue ID:</label>
            <select id="venue_id" data-table="venue" data-return="id" class="autocomplete venue_id form-control" multiple name="venue_id" value="<?= $venue_id ?? ''; ?>" /></select>
        </div>

        <div class="form-group row">
            <div class="col">
                <label for="venue_payout">Venue Payout:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                    </div><input type="number" class="gig_math form-control" id="venue_payout" name="venue_payout" value="<?= $venue_payout ?? ''; ?>" />
                </div>
            </div>
            <div class="col">

                <label for="merch">Merch:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                    </div>
                    <input type="number" class="gig_math form-control" id="merch" name="merch" value="<?= $merch ?? ''; ?>" />
                </div>
            </div>

            <div class="col">
                <label for="tips">Tips:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                    </div>
                    <input type="number" class="gig_math form-control" id="tips" name="tips" value="<?= $tips ?? ''; ?>" />
                </div>
            </div>

            <div class="col">
                <label for="cost_to_play">Cost to Play:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                    </div>
                    <input type="number" class="form-control" name="cost_to_play" value="<?= $cost_to_play ?? ''; ?>" />
                </div>
            </div>
            <div class="col">
               <label for="booking_fee_percent">Booking Fee %:</label>
               <div class="input-group">
               
                   <input type="number" class="gig_math form-control" id="booking_fee_percent" name="booking_fee_percent" value="<?= $booking_fee_percent ?? ''; ?>" />
                   <div class="input-group-append">
                       <span class="input-group-text" id="inputGroupPrepend">%</span>
                   </div>
               </div>
            
            </div>
  <div class="col">
                <label for="cost_to_play">Booking Fee:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                    </div>
                    <input type="number" class="form-control" id="booking_fee" name="booking_fee" value="<?= $booking_fee ?? ''; ?>" />
                </div>
            </div>
            <div class="col">
                <label for="show_length">Show Length:</label>
                <div class="input-group">

                    <input type="number" class="form-control" name="show_length" value="<?= $show_length ?? ''; ?>" />
                    <div class="input-group-append">
                        <span class="input-group-text" id="inputGroupPrepend">min</span>
                    </div>
                </div>
            </div>
        </div>



        <div class="form-group">
            <label for="gig_notes">Gig Notes:</label>
            <textarea class="form-control" name="gig_notes"><?= $gig_notes ?? ''; ?></textarea>
        </div>
    </div>

    <button id="profitLossSubmit" class="mt-3 btn btn-primary">Submit</button>
</form>